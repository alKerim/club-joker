<?php
/**
 * models/DemandeModel.php
 * Gestion des demandes d'adhésion — PDO + POO
 */

require_once __DIR__ . '/../config/database.php';

class DemandeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // ── Toutes les demandes ──────────────────────────────────
    public function getTous(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM demandes_adhesion ORDER BY date_demande DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Demandes en attente ──────────────────────────────────
    public function getEnAttente(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM demandes_adhesion
             WHERE statut = 'en_attente'
             ORDER BY date_demande DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Compter les demandes en attente ──────────────────────
    public function compterEnAttente(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS total FROM demandes_adhesion WHERE statut = 'en_attente'"
        );
        $stmt->execute();
        return (int) $stmt->fetch()['total'];
    }

    // ── Une demande par ID ───────────────────────────────────
    public function getParId(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM demandes_adhesion WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── Créer une demande d'adhésion ─────────────────────────
    public function creer(string $nom, string $email,
                          string $telephone = '', string $message = ''): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO demandes_adhesion (nom, email, telephone, message, statut)
             VALUES (:nom, :email, :tel, :msg, 'en_attente')"
        );
        return $stmt->execute([
            ':nom'   => $nom,
            ':email' => $email,
            ':tel'   => $telephone,
            ':msg'   => $message,
        ]);
    }

    // ── Changer le statut d'une demande ──────────────────────
    public function changerStatut(int $id, string $statut): bool
    {
        // Statuts valides : en_attente | accepte | refuse
        $statutsValides = ['en_attente', 'accepte', 'refuse'];
        if (!in_array($statut, $statutsValides)) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "UPDATE demandes_adhesion SET statut = :statut WHERE id = :id"
        );
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    // ── Supprimer une demande ────────────────────────────────
    public function supprimer(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM demandes_adhesion WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // ── Statistiques des demandes ────────────────────────────
    public function stats(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT statut, COUNT(*) AS total
             FROM demandes_adhesion
             GROUP BY statut"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}