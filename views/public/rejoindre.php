<?php
/**
 * views/public/rejoindre.php
 * Page "Nous rejoindre" — formulaire de demande d'adhésion
 */
$titreePage   = 'Nous rejoindre';
$pageCourante = 'rejoindre';
require_once __DIR__ . '/../layout/header.php';
?>

<!-- Flash Messages -->
<?php if (!empty($flash_success)): ?>
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3"
     style="z-index:9999;border-radius:var(--radius);min-width:300px" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flash_success) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (!empty($flash_error)): ?>
<div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 end-0 m-3"
     style="z-index:9999;border-radius:var(--radius);min-width:300px" role="alert">
  <i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($flash_error) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="page-header">
  <div class="container" style="position:relative;z-index:2;">
    <h1 class="animate-fadeInUp"> Rejoindre le Club</h1>
    <p class="animate-fadeInUp delay-1">Envoyez votre candidature — nous vous contacterons rapidement.</p>
  </div>
</div>

<!-- Section Avantages + Formulaire -->
<section class="section-pad">
  <div class="container">
    <div class="row g-5 align-items-start">

      <!-- Avantages membres -->
      <div class="col-lg-5">
        <div class="section-heading mb-4">
          <span class="overline">Pourquoi nous rejoindre ?</span>
          <h2>Les avantages membres</h2>
          <div class="section-divider"></div>
        </div>
        <div class="d-flex flex-column gap-3">
          <div class="joker-card p-3 d-flex align-items-center gap-3">
            <div class="value-icon blue" style="min-width:44px"><i class="bi bi-calendar-check-fill"></i></div>
            <div>
              <h6 class="text-blue mb-1">Accès aux événements privés</h6>
              <p class="text-muted small mb-0">Workshops, conférences et soirées exclusives réservées aux membres.</p>
            </div>
          </div>
          <div class="joker-card p-3 d-flex align-items-center gap-3">
            <div class="value-icon red" style="min-width:44px"><i class="bi bi-people-fill"></i></div>
            <div>
              <h6 class="text-blue mb-1">Réseau professionnel</h6>
              <p class="text-muted small mb-0">Connectez-vous avec des étudiants et des professionnels du domaine.</p>
            </div>
          </div>
          <div class="joker-card p-3 d-flex align-items-center gap-3">
            <div class="value-icon gold" style="min-width:44px"><i class="bi bi-lightbulb-fill"></i></div>
            <div>
              <h6 class="text-blue mb-1">Projets innovants</h6>
              <p class="text-muted small mb-0">Participez à des hackathons et portez vos idées jusqu'à la réalisation.</p>
            </div>
          </div>
          <div class="joker-card p-3 d-flex align-items-center gap-3">
            <div class="value-icon red" style="min-width:44px"><i class="bi bi-person-workspace"></i></div>
            <div>
              <h6 class="text-blue mb-1">Espace membre dédié</h6>
              <p class="text-muted small mb-0">Dashboard personnel, to-do list, suivi des tâches et des réunions.</p>
            </div>
          </div>
        </div>

        <!-- Stats -->
        <div class="joker-card mt-4 p-4">
          <div class="row text-center g-3">
            <div class="col-4">
              <div class="stat-value" style="font-size:1.6rem">60+</div>
              <div class="stat-label">Membres</div>
            </div>
            <div class="col-4">
              <div class="stat-value" style="font-size:1.6rem">8</div>
              <div class="stat-label">Événements/an</div>
            </div>
            <div class="col-4">
              <div class="stat-value" style="font-size:1.6rem">9</div>
              <div class="stat-label">Années</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Formulaire de candidature -->
      <div class="col-lg-7">
        <div class="joker-card">
          <div class="card-body p-4 p-lg-5">
            <h4 class="text-blue mb-1">Formulaire de candidature</h4>
            <p class="text-muted small mb-4">Remplissez ce formulaire — l'admin examinera votre demande.</p>

            <form method="POST" action="index.php?page=rejoindre" class="form-joker">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nom complet *</label>
                  <input type="text" class="form-control" name="nom"
                         placeholder="Votre prénom et nom" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Téléphone</label>
                  <input type="tel" class="form-control" name="telephone" id="telephone"
                         placeholder="55 123 456"
                         pattern="[234579][0-9]{7}"
                         maxlength="8"
                         title="Numéro tunisien : 8 chiffres commençant par 2, 3, 4, 5, 7 ou 9">
                  <div class="invalid-feedback">Numéro invalide : 8 chiffres, commençant par 2, 3, 4, 5, 7 ou 9.</div>
                  <div class="form-text text-muted" style="font-size:0.75rem">Ex: 55123456 · 22456789</div>
                </div>
                <div class="col-12">
                  <label class="form-label">Email universitaire *</label>
                  <input type="email" class="form-control" name="email"
                         placeholder="votre@univ.tn" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Mot de passe *</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="mot_de_passe" id="mot_de_passe"
                           placeholder="Choisissez un mot de passe" required minlength="6">
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1"
                            onclick="togglePwd('mot_de_passe','eye1')" title="Voir/Cacher">
                      <i class="bi bi-eye" id="eye1"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Confirmer mot de passe *</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="mot_de_passe_confirm" id="mot_de_passe_confirm"
                           placeholder="Répétez le mot de passe" required minlength="6">
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1"
                            onclick="togglePwd('mot_de_passe_confirm','eye2')" title="Voir/Cacher">
                      <i class="bi bi-eye" id="eye2"></i>
                    </button>
                  </div>
                  <div class="form-text text-danger d-none" id="pwd-mismatch">Les mots de passe ne correspondent pas.</div>
                </div>
                <div class="col-12">
                  <label class="form-label">Pourquoi souhaitez-vous rejoindre le Club Joker ?</label>
                  <textarea class="form-control" name="message" rows="5"
                            placeholder="Parlez-nous de votre motivation, vos compétences, vos projets..."></textarea>
                </div>
                <div class="col-12 mt-2">
                  <button type="submit" class="btn btn-joker-blue w-100 py-2">
                    <i class="bi bi-send-fill me-2"></i>Envoyer ma candidature
                  </button>
                </div>
              </div>
            </form>

            <hr style="border-color:var(--beige-dark);margin:1.5rem 0">
            <p class="text-muted small text-center mb-0">
              Déjà membre ?
              <a href="index.php?page=login" class="text-blue fw-bold">Connectez-vous ici</a>
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA bas de page -->
<section style="background:var(--blue);padding:3rem 0">
  <div class="container text-center">
    <h4 style="color:var(--beige)" class="mb-2">Des questions ?</h4>
    <p style="color:rgba(245,236,215,0.7)" class="mb-3">
      Contactez-nous à <strong style="color:var(--beige)">joker@univ.tn</strong>
    </p>
    <a href="index.php?page=accueil" class="btn btn-joker-outline"
       style="color:var(--beige);border-color:rgba(245,236,215,0.4)">
      <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
    </a>
  </div>
</section>

<script>
function togglePwd(inputId, iconId) {
  var inp = document.getElementById(inputId);
  var ico = document.getElementById(iconId);
  if (inp.type === 'password') {
    inp.type = 'text';
    ico.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    inp.type = 'password';
    ico.classList.replace('bi-eye-slash', 'bi-eye');
  }
}

// Validation téléphone tunisien en temps réel (8 chiffres, commence par 2,3,4,5,7,9)
document.getElementById('telephone').addEventListener('input', function() {
  var clean = this.value.replace(/[\s\-]/g, '');
  var valid = /^[234579]\d{7}$/.test(clean);
  if (this.value !== '' && !valid) {
    this.classList.add('is-invalid');
  } else {
    this.classList.remove('is-invalid');
  }
});

// Vérification correspondance mots de passe
document.getElementById('mot_de_passe_confirm').addEventListener('input', function() {
  var mdp = document.getElementById('mot_de_passe').value;
  var msg = document.getElementById('pwd-mismatch');
  if (this.value && this.value !== mdp) {
    msg.classList.remove('d-none');
    this.classList.add('is-invalid');
  } else {
    msg.classList.add('d-none');
    this.classList.remove('is-invalid');
  }
});

// Bloquer soumission si mots de passe différents
document.querySelector('.form-joker').addEventListener('submit', function(e) {
  var mdp  = document.getElementById('mot_de_passe').value;
  var conf = document.getElementById('mot_de_passe_confirm').value;
  if (mdp !== conf) {
    e.preventDefault();
    document.getElementById('pwd-mismatch').classList.remove('d-none');
    document.getElementById('mot_de_passe_confirm').classList.add('is-invalid');
  }
  var tel = document.getElementById('telephone');
  if (tel.value !== '') {
    var clean = tel.value.replace(/[\s\-]/g, '');
    if (!/^[234579]\d{7}$/.test(clean)) {
      e.preventDefault();
      tel.classList.add('is-invalid');
    }
  }
});
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>