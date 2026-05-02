<?php
/**
 * index.php — Front Controller (Point d'entrée unique)
 * Club Joker — Architecture MVC
 *
 * Toutes les URLs passent par : index.php?page=xxx&action=yyy
 */

// ── Démarrer la session ──────────────────────────────────────
session_start();

// ── Autoload des classes ─────────────────────────────────────
spl_autoload_register(function (string $classe) {
    $classes = [
        'AdminController'     => __DIR__ . '/controllers/adminController.php',
        'AuthController'      => __DIR__ . '/controllers/authController.php',
        'EvenementController' => __DIR__ . '/controllers/evenementController.php',
        'MembreController'    => __DIR__ . '/controllers/membreController.php',
        'ReunionController'   => __DIR__ . '/controllers/reunionController.php',
        'EvenementModel'      => __DIR__ . '/models/evenementModel.php',
        'PresenceModel'       => __DIR__ . '/models/Presencemodel.php',
        'ReunionModel'        => __DIR__ . '/models/reunionModel.php',
    ];

    if (isset($classes[$classe]) && file_exists($classes[$classe])) {
        require_once $classes[$classe];
        return;
    }

    $chemins = [
        __DIR__ . '/models/'      . $classe . '.php',
        __DIR__ . '/controllers/' . $classe . '.php',
    ];
    foreach ($chemins as $chemin) {
        if (file_exists($chemin)) {
            require_once $chemin;
            return;
        }
    }
});

// ── Récupérer la page et l'action demandées ──────────────────
$page   = $_GET['page']   ?? 'accueil';
$action = $_GET['action'] ?? null;

// ── Routeur principal ────────────────────────────────────────
switch ($page) {

    // ══════════════════════════════
    //  PAGES PUBLIQUES
    // ══════════════════════════════

    case 'accueil':
        require_once __DIR__ . '/models/evenementModel.php';
        require_once __DIR__ . '/models/DemandeModel.php';

        $evenementModel  = new EvenementModel();
        $demandeModel    = new DemandeModel();
        $evenements      = $evenementModel->getPublics(3);

        $flash_success   = $_SESSION['flash_success'] ?? null;
        $flash_error     = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require_once __DIR__ . '/views/public/accueil.php';
        break;

    case 'evenements':
        require_once __DIR__ . '/controllers/evenementController.php';
        $ctrl = new EvenementController();
        $ctrl->afficherListe();
        break;

    case 'inscrire_evenement':
        require_once __DIR__ . '/controllers/evenementController.php';
        $ctrl = new EvenementController();
        $ctrl->inscrire();
        break;

    case 'reunions':
        require_once __DIR__ . '/controllers/reunionController.php';
        $ctrl = new ReunionController();
        $ctrl->afficherListe();
        break;

    case 'rejoindre':
    require_once __DIR__ . '/models/DemandeModel.php';
    require_once __DIR__ . '/models/UtilisateurModel.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $demandeModel     = new DemandeModel();
        $utilisateurModel = new UtilisateurModel();
        $nom        = trim($_POST['nom']                ?? '');
        $email      = trim($_POST['email']              ?? '');
        $telephone  = trim($_POST['telephone']          ?? '');
        $message    = trim($_POST['message']            ?? '');
        $motDePasse = $_POST['mot_de_passe']            ?? '';
        $motDePasseConfirm = $_POST['mot_de_passe_confirm'] ?? '';

        // Validation téléphone : format tunisien (8 chiffres, commence par 2,3,4,5,7,9)
        $telError = false;
        if (!empty($telephone)) {
            $telClean = preg_replace('/[\s\-\(\)\+]/', '', $telephone);
            if (!ctype_digit($telClean) || strlen($telClean) !== 8 || !preg_match('/^[234579]/', $telClean)) {
                $telError = true;
            }
        }

        if (empty($nom) || empty($email)) {
            $_SESSION['flash_error'] = 'Nom et email sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Adresse email invalide.';
        } elseif ($telError) {
            $_SESSION['flash_error'] = 'Numéro de téléphone invalide. Saisissez 8 chiffres (ex: 55123456).';
        } elseif (empty($motDePasse)) {
            $_SESSION['flash_error'] = 'Le mot de passe est obligatoire.';
        } elseif (strlen($motDePasse) < 6) {
            $_SESSION['flash_error'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        } elseif ($motDePasse !== $motDePasseConfirm) {
            $_SESSION['flash_error'] = 'Les mots de passe ne correspondent pas.';
        } else {
            // Vérifier si déjà membre (compte actif)
            $membreExistant = $utilisateurModel->trouverParEmail($email);
            if ($membreExistant) {
                $_SESSION['flash_error'] = 'Vous êtes déjà membre du Club Joker. Veuillez vous connecter.';
            } else {
                // Vérifier si une demande en attente OU acceptée existe déjà avec cet email
                $demandeExistante = $demandeModel->getParEmailActif($email);
                if ($demandeExistante) {
                    if ($demandeExistante['statut'] === 'en_attente') {
                        $_SESSION['flash_error'] = 'Une demande avec cet email est déjà en cours de traitement. Veuillez patienter.';
                    } else {
                        $_SESSION['flash_error'] = 'Vous êtes déjà membre du Club Joker. Veuillez vous connecter.';
                    }
                } else {
                    $demandeModel->creer($nom, $email, $telephone, $message, $motDePasse);
                    $_SESSION['flash_success'] = 'Demande envoyée ! L\'admin examinera votre candidature.';
                }
            }
        }
        header('Location: index.php?page=rejoindre');
        exit;
    }
    $flash_success = $_SESSION['flash_success'] ?? null;
    $flash_error   = $_SESSION['flash_error']   ?? null;
    unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    require_once __DIR__ . '/views/public/rejoindre.php';
    break;

    // ══════════════════════════════
    //  AUTHENTIFICATION
    // ══════════════════════════════

    case 'login':
        require_once __DIR__ . '/controllers/authController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->traiterLogin();
        } else {
            $ctrl->afficherLogin();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/authController.php';
        $ctrl = new AuthController();
        $ctrl->deconnecter();
        break;

    // ══════════════════════════════
    //  DASHBOARD ADMIN
    // ══════════════════════════════

    case 'admin_dashboard':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->dashboard();
        break;

    case 'admin_ajouter_evenement':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->ajouterEvenement();
        break;

    case 'admin_modifier_evenement':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->modifierEvenement();
        break;

    case 'admin_supprimer_evenement':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->supprimerEvenement();
        break;

    case 'admin_ajouter_reunion':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->ajouterReunion();
        break;

    case 'admin_modifier_reunion':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->modifierReunion();
        break;

    case 'admin_supprimer_reunion':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->supprimerReunion();
        break;

    case 'admin_traiter_demande':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->traiterDemande();
        break;

    case 'admin_ajouter_tache':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->ajouterTache();
        break;

    case 'admin_toggle_tache':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->toggleTache();
        break;

    case 'admin':
        header('Location: index.php?page=admin_dashboard');
        exit;
        break;

    case 'admin_supprimer_tache':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->supprimerTache();
        break;
    
    case 'admin_modifier_membre':
    require_once __DIR__ . '/controllers/adminController.php';
    $ctrl = new AdminController();
    $ctrl->modifierMembre();
    break;

case 'admin_supprimer_membre':
    require_once __DIR__ . '/controllers/adminController.php';
    $ctrl = new AdminController();
    $ctrl->supprimerMembre();
    break;

    case 'admin_gerer_presences':
        require_once __DIR__ . '/controllers/adminController.php';
        $ctrl = new AdminController();
        $ctrl->gererPresences();
        break;

    // ══════════════════════════════
    //  DASHBOARD MEMBRE
    // ══════════════════════════════

    case 'membre_dashboard':
        require_once __DIR__ . '/controllers/membreController.php';
        $ctrl = new MembreController();
        $ctrl->dashboard();
        break;

    case 'membre_ajouter_todo':
        require_once __DIR__ . '/controllers/membreController.php';
        $ctrl = new MembreController();
        $ctrl->ajouterTodo();
        break;

    case 'membre_toggle_todo':
        require_once __DIR__ . '/controllers/membreController.php';
        $ctrl = new MembreController();
        $ctrl->toggleTodo();
        break;

    case 'membre_supprimer_todo':
        require_once __DIR__ . '/controllers/membreController.php';
        $ctrl = new MembreController();
        $ctrl->supprimerTodo();
        break;

    // ══════════════════════════════
    //  404
    // ══════════════════════════════

    default:
        require_once __DIR__ . '/models/evenementModel.php';
        require_once __DIR__ . '/models/DemandeModel.php';
        $evenementModel = new EvenementModel();
        $evenements     = $evenementModel->getPublics(3);
        $flash_success  = $_SESSION['flash_success'] ?? null;
        $flash_error    = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
        require_once __DIR__ . '/views/public/accueil.php';
        break;
}
