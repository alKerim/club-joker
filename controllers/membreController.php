<?php
/**
 * controllers/MembreController.php
 * Dashboard membre + to-do list + formations
 */

require_once __DIR__ . '/../models/evenementModel.php';
require_once __DIR__ . '/../models/reunionModel.php';
require_once __DIR__ . '/../models/TacheModel.php';
require_once __DIR__ . '/../models/DemandeModel.php';
require_once __DIR__ . '/../controllers/authController.php';

class MembreController
{
    private EvenementModel $evenementModel;
    private ReunionModel   $reunionModel;
    private TacheModel     $tacheModel;

    public function __construct()
    {
        AuthController::exigerMembre();

        $this->evenementModel = new EvenementModel();
        $this->reunionModel   = new ReunionModel();
        $this->tacheModel     = new TacheModel();
    }

    // ── Dashboard membre ─────────────────────────────────────
    public function dashboard(): void
    {
        $uid = (int) $_SESSION['user']['id'];
        $tab = $_GET['tab'] ?? 'overview';

        $evenements  = $this->evenementModel->getTous();
        $reunions    = $this->reunionModel->getAVenir();
        $mes_taches  = $this->tacheModel->getTachesParMembre($uid);
        $todo_list   = $this->tacheModel->getTodoMembre($uid);

        // Construire la liste des événements où le membre est déjà inscrit
        $inscriptions_ids = [];
        foreach ($evenements as $e) {
            if ($this->evenementModel->dejaInscrit((int)$e['id'], $_SESSION['user']['email'], $uid)) {
                $inscriptions_ids[] = (int)$e['id'];
            }
        }

        $stats = [
            'evenements' => count($evenements),
            'reunions'   => count($reunions),
            'mes_taches' => count($mes_taches),
        ];

        $flash_success = $_SESSION['flash_success'] ?? null;
        $flash_error   = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require_once __DIR__ . '/../views/membre/dashboard.php';
    }

    // ── Ajouter todo personnel ───────────────────────────────
    public function ajouterTodo(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=membre_dashboard&tab=todo');
            exit;
        }

        $uid   = (int) $_SESSION['user']['id'];
        $titre = trim($_POST['titre'] ?? '');

        if (empty($titre)) {
            $_SESSION['flash_error'] = 'Saisissez une tâche.';
            header('Location: index.php?page=membre_dashboard&tab=todo');
            exit;
        }

        $this->tacheModel->ajouterTodo($uid, $titre);
        $_SESSION['flash_success'] = 'Tâche ajoutée !';
        header('Location: index.php?page=membre_dashboard&tab=todo');
        exit;
    }

    // ── Toggle todo ──────────────────────────────────────────
    public function toggleTodo(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->tacheModel->toggleStatut($id);
        }
        header('Location: index.php?page=membre_dashboard&tab=todo');
        exit;
    }

    // ── Supprimer todo ───────────────────────────────────────
    public function supprimerTodo(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->tacheModel->supprimer($id);
        }
        header('Location: index.php?page=membre_dashboard&tab=todo');
        exit;
    }

    // ── S'inscrire à une formation ───────────────────────────
    // (réutilise EvenementController::inscrire avec type formation)
    public function inscrireFormation(): void
    {
        $idFormation = (int) ($_GET['id'] ?? 0);
        if ($idFormation) {
            $_SESSION['flash_success'] = 'Inscription à la formation confirmée !';
        }
        header('Location: index.php?page=membre_dashboard&tab=formations');
        exit;
    }
}
