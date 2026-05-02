<?php
/**
 * controllers/AdminController.php
 * Toutes les actions du dashboard administrateur
 * MAJ : lien_meet réunions + liste présences + statistiques
 */

require_once __DIR__ . '/../models/evenementModel.php';
require_once __DIR__ . '/../models/reunionModel.php';
require_once __DIR__ . '/../models/DemandeModel.php';
require_once __DIR__ . '/../models/TacheModel.php';
require_once __DIR__ . '/../models/UtilisateurModel.php';
require_once __DIR__ . '/../models/Presencemodel.php';
require_once __DIR__ . '/../controllers/authController.php';

class AdminController
{
    private EvenementModel   $evenementModel;
    private ReunionModel     $reunionModel;
    private DemandeModel     $demandeModel;
    private TacheModel       $tacheModel;
    private UtilisateurModel $utilisateurModel;
    private PresenceModel    $presenceModel;

    public function __construct()
    {
        AuthController::exigerAdmin();

        $this->evenementModel   = new EvenementModel();
        $this->reunionModel     = new ReunionModel();
        $this->demandeModel     = new DemandeModel();
        $this->tacheModel       = new TacheModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->presenceModel    = new PresenceModel();
    }

    // ════════════════════════════════════════════════════════
    //  DASHBOARD — Vue d'ensemble
    // ════════════════════════════════════════════════════════
    public function dashboard(): void
    {
        $stats = [
            'evenements' => $this->evenementModel->compter(),
            'reunions'   => $this->reunionModel->compter(),
            'membres'    => $this->utilisateurModel->compterMembres(),
            'demandes'   => $this->demandeModel->compterEnAttente(),
        ];

        $evenements_recents  = $this->evenementModel->getTous();
        $demandes_en_attente = $this->demandeModel->getEnAttente();

        $tab           = $_GET['tab'] ?? 'overview';
        $flash_success = $_SESSION['flash_success'] ?? null;
        $flash_error   = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        // Données pour chaque onglet
        $evenements = $this->evenementModel->getTous();
        $reunions   = $this->reunionModel->getTous();
        $demandes   = $this->demandeModel->getTous();
        $taches     = $this->tacheModel->getTous();
        $membres    = $this->utilisateurModel->listeMembres();

        // ── Statistiques enrichies pour rapports ────────────
        $statsEvenements  = $this->evenementModel->statsParType();
        $statsDemandes    = $this->demandeModel->stats();
        $statsTaches      = $this->tacheModel->stats();
        $tauxPresence     = $this->presenceModel->tauxPresenceGlobal();
        $tousMembres      = $this->utilisateurModel->getTousMembres();

        // Données présence si onglet présence
        $presences        = [];
        $reunionPresence  = null;
        if ($tab === 'presences') {
            $idReunion = (int)($_GET['reunion_id'] ?? 0);
            if ($idReunion) {
                $presences       = $this->presenceModel->getParReunion($idReunion);
                $reunionPresence = $this->reunionModel->getParId($idReunion);
            }
        }

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // ════════════════════════════════════════════════════════
    //  ÉVÉNEMENTS
    // ════════════════════════════════════════════════════════
    public function ajouterEvenement(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_dashboard&tab=events');
            exit;
        }

        $data = [
            'titre'           => trim($_POST['titre'] ?? ''),
            'description'     => trim($_POST['description'] ?? ''),
            'date_evenement'  => $_POST['date_evenement'] ?? '',
            'heure'           => $_POST['heure'] ?? null,
            'lieu'            => trim($_POST['lieu'] ?? ''),
            'type'            => $_POST['type'] ?? 'public',
            'max_participants' => (int) ($_POST['max_participants'] ?? 30),
            'id_createur'     => $_SESSION['user']['id'],
        ];

        if (empty($data['titre']) || empty($data['date_evenement'])) {
            $_SESSION['flash_error'] = 'Titre et date sont obligatoires.';
            header('Location: index.php?page=admin_dashboard&tab=events');
            exit;
        }

        $this->evenementModel->ajouter($data);
        $_SESSION['flash_success'] = 'Événement ajouté avec succès !';
        header('Location: index.php?page=admin_dashboard&tab=events');
        exit;
    }

    public function modifierEvenement(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_dashboard&tab=events');
            exit;
        }

        $id   = (int) ($_POST['id'] ?? 0);
        $data = [
            'titre'           => trim($_POST['titre'] ?? ''),
            'description'     => trim($_POST['description'] ?? ''),
            'date_evenement'  => $_POST['date_evenement'] ?? '',
            'heure'           => $_POST['heure'] ?? null,
            'lieu'            => trim($_POST['lieu'] ?? ''),
            'type'            => $_POST['type'] ?? 'public',
            'max_participants' => (int) ($_POST['max_participants'] ?? 30),
        ];

        if (!$id || empty($data['titre'])) {
            $_SESSION['flash_error'] = 'Données invalides.';
            header('Location: index.php?page=admin_dashboard&tab=events');
            exit;
        }

        $this->evenementModel->modifier($id, $data);
        $_SESSION['flash_success'] = 'Événement modifié.';
        header('Location: index.php?page=admin_dashboard&tab=events');
        exit;
    }

    public function supprimerEvenement(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->evenementModel->supprimer($id);
            $_SESSION['flash_success'] = 'Événement supprimé.';
        }
        header('Location: index.php?page=admin_dashboard&tab=events');
        exit;
    }

    // ════════════════════════════════════════════════════════
    //  RÉUNIONS (avec lien_meet)
    // ════════════════════════════════════════════════════════
    public function ajouterReunion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_dashboard&tab=meetings');
            exit;
        }

        $data = [
            'titre'         => trim($_POST['titre'] ?? ''),
            'date_reunion'  => $_POST['date_reunion'] ?? '',
            'heure'         => $_POST['heure'] ?? null,
            'lieu'          => trim($_POST['lieu'] ?? ''),
            'ordre_du_jour' => trim($_POST['ordre_du_jour'] ?? ''),
            'lien_meet'     => trim($_POST['lien_meet'] ?? '') ?: null,
            'type'          => $_POST['type'] ?? 'bureau',
            'id_createur'   => $_SESSION['user']['id'],
        ];

        if (empty($data['titre']) || empty($data['date_reunion'])) {
            $_SESSION['flash_error'] = 'Titre et date obligatoires.';
            header('Location: index.php?page=admin_dashboard&tab=meetings');
            exit;
        }

        $this->reunionModel->ajouter($data);
        $_SESSION['flash_success'] = 'Réunion planifiée !';
        header('Location: index.php?page=admin_dashboard&tab=meetings');
        exit;
    }

    public function modifierReunion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_dashboard&tab=meetings');
            exit;
        }

        $id   = (int) ($_POST['id'] ?? 0);
        $data = [
            'titre'         => trim($_POST['titre'] ?? ''),
            'date_reunion'  => $_POST['date_reunion'] ?? '',
            'heure'         => $_POST['heure'] ?? null,
            'lieu'          => trim($_POST['lieu'] ?? ''),
            'ordre_du_jour' => trim($_POST['ordre_du_jour'] ?? ''),
            'lien_meet'     => trim($_POST['lien_meet'] ?? '') ?: null,
            'type'          => $_POST['type'] ?? 'bureau',
        ];

        if (!$id || empty($data['titre'])) {
            $_SESSION['flash_error'] = 'Données invalides.';
            header('Location: index.php?page=admin_dashboard&tab=meetings');
            exit;
        }

        $this->reunionModel->modifier($id, $data);
        $_SESSION['flash_success'] = 'Réunion modifiée.';
        header('Location: index.php?page=admin_dashboard&tab=meetings');
        exit;
    }

    public function supprimerReunion(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->reunionModel->supprimer($id);
            $_SESSION['flash_success'] = 'Réunion supprimée.';
        }
        header('Location: index.php?page=admin_dashboard&tab=meetings');
        exit;
    }

    // ════════════════════════════════════════════════════════
    //  PRÉSENCES
    // ════════════════════════════════════════════════════════
    public function gererPresences(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idReunion = (int) ($_POST['id_reunion'] ?? 0);
            $presences = $_POST['presences'] ?? [];  // [ id_membre => 'present' ]

            if ($idReunion) {
                // Récupérer tous les membres et marquer absent par défaut
                $membres = $this->utilisateurModel->listeMembres();
                $map = [];
                foreach ($membres as $m) {
                    $map[$m['id']] = isset($presences[$m['id']]) ? 'present' : 'absent';
                }
                $this->presenceModel->enregistrerTout($idReunion, $map);
                $_SESSION['flash_success'] = 'Liste de présence enregistrée !';
            }
            header('Location: index.php?page=admin_dashboard&tab=presences&reunion_id=' . $idReunion);
            exit;
        }

        header('Location: index.php?page=admin_dashboard&tab=presences');
        exit;
    }

    // ════════════════════════════════════════════════════════
//  DEMANDES D'ADHÉSION
// ════════════════════════════════════════════════════════
public function traiterDemande(): void
{
    $id     = (int) ($_GET['id']     ?? 0);
    $action = $_GET['action'] ?? '';

    if (!$id || !in_array($action, ['accepte', 'refuse'])) {
        header('Location: index.php?page=admin_dashboard&tab=requests');
        exit;
    }

    $demande = $this->demandeModel->getParId($id);

    if ($demande && $action === 'accepte') {

        // Vérifier si l'email existe déjà dans utilisateurs
        $existant = $this->utilisateurModel->trouverParEmail($demande['email']);

        if (!$existant) {
            $mdp = !empty($demande['mot_de_passe'])
                ? $demande['mot_de_passe']
                : password_hash('joker2024', PASSWORD_BCRYPT);

            $this->utilisateurModel->creerAvecHash(
                $demande['nom'],
                $demande['email'],
                $mdp,
                'membre',
                $demande['telephone'] ?? ''
            );
        }

        $this->demandeModel->changerStatut($id, 'accepte');
        $_SESSION['flash_success'] = "✅ {$demande['nom']} est maintenant membre du club !";

    } elseif ($demande && $action === 'refuse') {
        $this->demandeModel->changerStatut($id, 'refuse');
        $_SESSION['flash_success'] = "Demande de {$demande['nom']} refusée.";
    }

    header('Location: index.php?page=admin_dashboard&tab=requests');
    exit;
}

// ════════════════════════════════════════════════════════
//  MEMBRES — MODIFIER
// ════════════════════════════════════════════════════════
public function modifierMembre(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=admin_dashboard&tab=members');
        exit;
    }

    $id       = (int) ($_POST['id'] ?? 0);
    $nom      = trim($_POST['nom']       ?? '');
    $email    = trim($_POST['email']     ?? '');
    $tel      = trim($_POST['telephone'] ?? '');

    if ($id && $nom && $email) {
        $this->utilisateurModel->modifier($id, $nom, $email, $tel);
        $_SESSION['flash_success'] = 'Membre modifié avec succès.';
    } else {
        $_SESSION['flash_error'] = 'Données invalides.';
    }

    header('Location: index.php?page=admin_dashboard&tab=members');
    exit;
}

// ════════════════════════════════════════════════════════
//  MEMBRES — SUPPRIMER
// ════════════════════════════════════════════════════════
public function supprimerMembre(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    if ($id) {
        $this->utilisateurModel->supprimer($id);
        $_SESSION['flash_success'] = 'Membre supprimé.';
    }
    header('Location: index.php?page=admin_dashboard&tab=members');
    exit;
}
    // ════════════════════════════════════════════════════════
    //  TÂCHES
    // ════════════════════════════════════════════════════════
    public function ajouterTache(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_dashboard&tab=tasks');
            exit;
        }

        $data = [
            'titre'      => trim($_POST['titre'] ?? ''),
            'id_assigne' => (int) ($_POST['id_assigne'] ?? 0),
            'deadline'   => $_POST['deadline'] ?? null,
            'priorite'   => $_POST['priorite'] ?? 'moyenne',
        ];

        if (empty($data['titre'])) {
            $_SESSION['flash_error'] = 'Titre de tâche obligatoire.';
            header('Location: index.php?page=admin_dashboard&tab=tasks');
            exit;
        }

        $this->tacheModel->ajouter($data);
        $_SESSION['flash_success'] = 'Tâche assignée !';
        header('Location: index.php?page=admin_dashboard&tab=tasks');
        exit;
    }

    public function toggleTache(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->tacheModel->toggleStatut($id);
        }
        header('Location: index.php?page=admin_dashboard&tab=tasks');
        exit;
    }

    public function supprimerTache(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->tacheModel->supprimer($id);
            $_SESSION['flash_success'] = 'Tâche supprimée.';
        }
        header('Location: index.php?page=admin_dashboard&tab=tasks');
        exit;
    }
}
