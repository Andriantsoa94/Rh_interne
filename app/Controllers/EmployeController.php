<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class EmployeController extends BaseController
{
    public function dashboard(): string
    {
        $db = $this->db();
        $user = $this->getCurrentUser();

        $soldes = $db->table('soldes')
            ->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $user['id'])
            ->where('soldes.annee', date('Y'))
            ->orderBy('types_conge.libelle', 'ASC')
            ->get()
            ->getResultArray();

        $soldes = $this->decorateSoldes($soldes);

        $conges = $db->table('conges')
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $user['id'])
            ->orderBy('conges.date_debut', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $conges = $this->decorateConges($conges);

        $stats = [
            'en_attente' => 0,
            'approuvee' => 0,
            'refusee' => 0,
        ];
        $rows = $db->table('conges')
            ->select('statut, COUNT(*) as total')
            ->where('employe_id', $user['id'])
            ->groupBy('statut')
            ->get()
            ->getResultArray();
        foreach ($rows as $row) {
            $stats[$row['statut']] = (int) $row['total'];
        }

        $stats['restant_total'] = 0;
        $stats['attribues_total'] = 0;
        foreach ($soldes as $solde) {
            if ((int) $solde['deductible'] === 1) {
                $stats['restant_total'] += (int) $solde['restant'];
                $stats['attribues_total'] += (int) $solde['jours_attribues'];
            }
        }

        return view('employe/dashboard', [
            'title' => 'Tableau de bord',
            'user' => $user,
            'avatar' => $this->getInitials($user),
            'soldes' => $soldes,
            'conges' => $conges,
            'stats' => $stats,
        ]);
    }

    public function index(): string
    {
        $db = $this->db();
        $user = $this->getCurrentUser();
        $statut = $this->request->getGet('statut');
        $allowed = ['en_attente', 'approuvee', 'refusee', 'annulee'];
        if (!in_array($statut, $allowed, true)) {
            $statut = 'tous';
        }

        $builder = $db->table('conges')
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $user['id'])
            ->orderBy('conges.date_debut', 'DESC');
        if ($statut !== 'tous') {
            $builder->where('conges.statut', $statut);
        }
        $conges = $builder->get()->getResultArray();
        $conges = $this->decorateConges($conges);

        return view('employe/index', [
            'title' => 'Mes demandes',
            'user' => $user,
            'avatar' => $this->getInitials($user),
            'conges' => $conges,
            'currentStatut' => $statut,
        ]);
    }

    public function create(): string
    {
        helper('form');
        $db = $this->db();
        $user = $this->getCurrentUser();

        $types = $db->table('types_conge')
            ->orderBy('libelle', 'ASC')
            ->get()
            ->getResultArray();

        $soldes = $db->table('soldes')
            ->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $user['id'])
            ->where('soldes.annee', date('Y'))
            ->get()
            ->getResultArray();
        $soldes = $this->decorateSoldes($soldes);

        $soldesByType = [];
        foreach ($soldes as $solde) {
            $soldesByType[$solde['type_conge_id']] = $solde;
        }
        foreach ($types as &$type) {
            $solde = $soldesByType[$type['id']] ?? null;
            $type['restant'] = $solde['restant'] ?? null;
        }
        unset($type);

        $computedDays = null;
        $start = old('date_debut');
        $end = old('date_fin');
        if ($start && $end) {
            $computedDays = $this->countBusinessDays($start, $end);
        }

        return view('employe/create', [
            'title' => 'Nouvelle demande',
            'user' => $user,
            'avatar' => $this->getInitials($user),
            'types' => $types,
            'soldes' => $soldes,
            'errors' => session()->getFlashdata('errors') ?? [],
            'computedDays' => $computedDays,
        ]);
    }

    public function store(): RedirectResponse
    {
        $db = $this->db();
        $user = $this->getCurrentUser();

        $typeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = trim((string) $this->request->getPost('motif'));

        $errors = [];
        if ($typeId <= 0) {
            $errors['type_conge_id'] = 'Le type de conge est requis.';
        }

        $start = \DateTimeImmutable::createFromFormat('Y-m-d', $dateDebut);
        $end = \DateTimeImmutable::createFromFormat('Y-m-d', $dateFin);
        if (!$start || $start->format('Y-m-d') !== $dateDebut) {
            $errors['date_debut'] = 'La date de debut est invalide.';
        }
        if (!$end || $end->format('Y-m-d') !== $dateFin) {
            $errors['date_fin'] = 'La date de fin est invalide.';
        }
        if ($start && $end && $end < $start) {
            $errors['date_fin'] = 'La date de fin doit etre apres la date de debut.';
        }

        $type = null;
        if ($typeId > 0) {
            $type = $db->table('types_conge')->where('id', $typeId)->get()->getRowArray();
            if (!$type) {
                $errors['type_conge_id'] = 'Type de conge introuvable.';
            }
        }

        if ($start && $end) {
            $overlap = $db->table('conges')
                ->where('employe_id', $user['id'])
                ->whereIn('statut', ['en_attente', 'approuvee'])
                ->groupStart()
                ->where('date_debut <=', $dateFin)
                ->where('date_fin >=', $dateDebut)
                ->groupEnd()
                ->countAllResults();
            if ($overlap > 0) {
                $errors['date_debut'] = 'Chevauchement detecte avec une demande existante.';
            }
        }

        $joursDemandes = 0;
        if ($start && $end) {
            $joursDemandes = $this->countBusinessDays($dateDebut, $dateFin);
            if ($joursDemandes <= 0) {
                $errors['date_debut'] = 'Le nombre de jours demandes est invalide.';
            }
        }

        if ($type && (int) $type['deductible'] === 1) {
            $solde = $db->table('soldes')
                ->where('employe_id', $user['id'])
                ->where('type_conge_id', $typeId)
                ->where('annee', date('Y'))
                ->get()
                ->getRowArray();
            if (!$solde) {
                $errors['type_conge_id'] = 'Aucun solde disponible pour ce type.';
            } else {
                $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
                if ($joursDemandes > $restant) {
                    $errors['type_conge_id'] = 'Solde insuffisant pour cette demande.';
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $db->table('conges')->insert([
            'employe_id' => $user['id'],
            'type_conge_id' => $typeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'motif' => $motif !== '' ? $motif : null,
            'statut' => 'en_attente',
        ]);

        return redirect()->to('/employe')->with('success', 'Votre demande a ete soumise.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $db = $this->db();
        $user = $this->getCurrentUser();

        $updated = $db->table('conges')
            ->where('id', $id)
            ->where('employe_id', $user['id'])
            ->where('statut', 'en_attente')
            ->update(['statut' => 'annulee']);

        if (!$updated) {
            return redirect()->to('/employe/conges')->with('error', 'Annulation impossible.');
        }

        return redirect()->to('/employe/conges')->with('success', 'Demande annulee.');
    }

    public function profil(): string
    {
        helper('form');
        $user = $this->getCurrentUser();

        return view('employe/profil', [
            'title' => 'Mon profil',
            'user' => $user,
            'avatar' => $this->getInitials($user),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function updateProfil(): RedirectResponse
    {
        $db = $this->db();
        $user = $this->getCurrentUser();

        $nom = trim((string) $this->request->getPost('nom'));
        $prenom = trim((string) $this->request->getPost('prenom'));
        $password = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');

        $errors = [];
        if ($nom === '' || $prenom === '') {
            $errors['nom'] = 'Le nom et le prenom sont requis.';
        }
        if ($password !== '' && $password !== $passwordConfirm) {
            $errors['password'] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $update = [
            'nom' => $nom,
            'prenom' => $prenom,
        ];
        if ($password !== '') {
            $update['password'] = $password;
        }

        $db->table('employes')->where('id', $user['id'])->update($update);

        return redirect()->to('/employe/profil')->with('success', 'Profil mis a jour.');
    }

    private function db()
    {
        return \Config\Database::connect();
    }

    private function getCurrentUser(): array
    {
        $sessionUser = session()->get('user');
        if (!$sessionUser || !isset($sessionUser['id'])) {
            $sessionUser = ['id' => 1, 'role' => 'employe'];
        }

        $user = $this->db()->table('employes')
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('employes.id', $sessionUser['id'])
            ->get()
            ->getRowArray();

        return $user ?: $sessionUser;
    }

    private function getInitials(array $user): string
    {
        $prenom = trim((string) ($user['prenom'] ?? ''));
        $nom = trim((string) ($user['nom'] ?? ''));
        $initials = '';
        if ($prenom !== '') {
            $initials .= strtoupper($prenom[0]);
        }
        if ($nom !== '') {
            $initials .= strtoupper($nom[0]);
        }

        return $initials !== '' ? $initials : 'U';
    }

    private function decorateSoldes(array $soldes): array
    {
        foreach ($soldes as &$solde) {
            $solde['restant'] = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            $ratio = (int) $solde['jours_attribues'] > 0 ? $solde['restant'] / (int) $solde['jours_attribues'] : 0;
            $solde['bar_width'] = (int) round($ratio * 100);
            $solde['bar_class'] = '';
            if ($ratio <= 0.2) {
                $solde['bar_class'] = 'danger';
            } elseif ($ratio <= 0.4) {
                $solde['bar_class'] = 'warn';
            }
            $solde['type_class'] = $this->mapTypeClass((string) $solde['libelle']);
        }
        unset($solde);

        return $soldes;
    }

    private function decorateConges(array $conges): array
    {
        foreach ($conges as &$conge) {
            $conge['duree'] = $this->countBusinessDays($conge['date_debut'], $conge['date_fin']);
            $conge['type_class'] = $this->mapTypeClass((string) $conge['libelle']);
            $conge['statut_class'] = $this->mapStatutClass((string) $conge['statut']);
            $conge['statut_label'] = $this->mapStatutLabel((string) $conge['statut']);
            $conge['date_debut_label'] = $this->formatDate($conge['date_debut']);
            $conge['date_fin_label'] = $this->formatDate($conge['date_fin']);
        }
        unset($conge);

        return $conges;
    }

    private function formatDate(string $date): string
    {
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
        if (!$dt) {
            return $date;
        }

        return $dt->format('d/m/Y');
    }

    private function mapTypeClass(string $libelle): string
    {
        $label = strtolower($libelle);
        if (strpos($label, 'annuel') !== false) {
            return 't-annuel';
        }
        if (strpos($label, 'maladie') !== false) {
            return 't-maladie';
        }
        if (strpos($label, 'special') !== false || strpos($label, 'sp') !== false) {
            return 't-special';
        }
        if (strpos($label, 'sans') !== false) {
            return 't-sans-solde';
        }

        return 't-special';
    }

    private function mapStatutClass(string $statut): string
    {
        if ($statut === 'approuvee') {
            return 's-approuvee';
        }
        if ($statut === 'refusee') {
            return 's-refusee';
        }
        if ($statut === 'annulee') {
            return 's-annulee';
        }

        return 's-attente';
    }

    private function mapStatutLabel(string $statut): string
    {
        return str_replace('_', ' ', $statut);
    }

    private function countBusinessDays(string $start, string $end): int
    {
        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', $start);
        $endDate = \DateTimeImmutable::createFromFormat('Y-m-d', $end);
        if (!$startDate || !$endDate || $endDate < $startDate) {
            return 0;
        }

        $days = 0;
        for ($date = $startDate; $date <= $endDate; $date = $date->modify('+1 day')) {
            $weekday = (int) $date->format('N');
            if ($weekday < 6) {
                $days++;
            }
        }

        return $days;
    }
}
