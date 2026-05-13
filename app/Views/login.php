<?php namespace App\Views; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Connexion - Système RH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
/* ═══════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════ */
:root{
  --ink:      #1c2b1e;
  --forest:   #2d5a3d;
  --forest2:  #3d7a52;
  --leaf:     #5fa876;
  --mint:     #d4ede0;
  --cream:    #f8f6f1;
  --white:    #ffffff;
  --border:   #dde8e1;
  --muted:    #7a8f80;
  --danger:   #c0392b;
  --danger-bg:#fdf0ee;
  --danger-br:#f0b8b2;
  --warn:     #b8750a;
  --warn-bg:  #fef9ee;
  --warn-br:  #f5d98a;
  --success:  #1e6b3f;
  --success-bg:#edf7f2;
  --success-br:#8fd4aa;
  --info:     #1a4f7a;
  --info-bg:  #eaf2fb;
  --info-br:  #8fbde8;
  --sidebar-w:240px;
  --topbar-h: 62px;
}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
code,pre,.mono{font-family:'DM Mono',monospace}

/* ─── MOTIF GÉOMÉTRIQUE ────────────────── */
.geo-bg{
  position:relative;
  overflow:hidden;
}
.geo-bg::before{
  content:'';
  position:absolute;inset:0;
  background-image:
    repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(45,90,61,.04) 40px),
    repeating-linear-gradient(90deg,transparent,transparent 39px,rgba(45,90,61,.04) 40px);
  pointer-events:none;
  z-index:0;
}
.geo-bg>*{position:relative;z-index:1}

/* ─── AUTH ─────────────────────────────── */
.auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--ink)}
.auth-page.geo-bg::before{background-image:repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(255,255,255,.03) 40px),repeating-linear-gradient(90deg,transparent,transparent 39px,rgba(255,255,255,.03) 40px)}
.auth-split{display:grid;grid-template-columns:1fr 420px;max-width:900px;width:100%;border-radius:16px;overflow:hidden;background:var(--white)}
.auth-left{background:var(--forest);padding:3rem;display:flex;flex-direction:column;justify-content:space-between}
.auth-left-brand{font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--white);letter-spacing:-.5px;margin:0}
.auth-left-brand span{display:block;font-size:.85rem;font-weight:300;font-family:'DM Sans',sans-serif;color:rgba(255,255,255,.5);margin-top:4px;letter-spacing:0}
.auth-left-text{color:rgba(255,255,255,.6);font-size:.875rem;line-height:1.7}
.auth-left-text strong{color:var(--white);display:block;font-size:1.25rem;font-family:'Playfair Display',serif;margin-bottom:.5rem}
.auth-right{padding:2.5rem}
.auth-title{font-size:1.3rem;font-weight:700;margin:0 0 .25rem}
.auth-sub{font-size:.85rem;color:var(--muted);margin:0 0 1.75rem}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);transition:border-color .15s,box-shadow .15s}
.f-input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(45,90,61,.1);outline:none}
.f-group{margin-bottom:1rem}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.btn-primary{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:11px 20px;font-weight:500;font-size:.9rem;cursor:pointer;transition:background .15s;font-family:'DM Sans',sans-serif;width:100%}
.btn-primary:hover{background:var(--forest2)}
.auth-footer{text-align:center;margin-top:1.25rem;font-size:.8rem;color:var(--muted)}
.auth-footer a{color:var(--forest);text-decoration:none;font-weight:500}
.auth-roles{display:flex;flex-direction:column;gap:8px;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.1)}
.role-pill{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:8px 12px;display:flex;align-items:center;gap:10px}
.role-pill i{color:var(--leaf);font-size:1.1rem}
.role-pill-name{font-size:.8rem;font-weight:500;color:var(--white)}
.role-pill-cred{font-size:.72rem;color:rgba(255,255,255,.4);font-family:'DM Mono',monospace}

/* ─── FLASH MESSAGES ────────────────────── */
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.flash-warn{background:var(--warn-bg);color:var(--warn);border-color:var(--warn-br)}
.flash-info{background:var(--info-bg);color:var(--info);border-color:var(--info-br)}
</style>
</head>
<body>

<section id="page-login">
<div class="auth-page geo-bg">
<div class="auth-split">

  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Bienvenue sur votre espace RH.</strong>
        Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
      </p>
    </div>
    <div class="auth-roles">
      <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de démonstration</div>
      <div class="role-pill">
        <i class="bi bi-shield-check"></i>
        <div><div class="role-pill-name">Administrateur</div><div class="role-pill-cred">admin@techmada.mg · admin123</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person-check"></i>
        <div><div class="role-pill-name">Responsable RH</div><div class="role-pill-cred">rh@techmada.mg · rh123</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person"></i>
        <div><div class="role-pill-name">Employé</div><div class="role-pill-cred">employe@techmada.mg · employe123</div></div>
      </div>
    </div>
  </div>

  <!-- Panneau droit -->
  <div class="auth-right">
    <p class="auth-title">Connexion</p>
    <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

    <!-- Flashdata CI4 — erreur -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
      <?= csrf_field() ?>
      <div class="f-group">
        <label class="f-label">Adresse email</label>
        <input type="email" name="email" class="f-input" placeholder="vous@techmada.mg" value="<?= old('email') ?>" required/>
      </div>
      <div class="f-group">
        <label class="f-label">Mot de passe</label>
        <input type="password" name="password" class="f-input" placeholder="••••••••" required/>
      </div>
      <button type="submit" class="btn-primary" style="margin-top:.5rem">
        Se connecter <i class="bi bi-arrow-right-short"></i>
      </button>
    </form>
  </div>

</div>
</div>
</section>
</body>
</html>