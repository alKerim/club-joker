<?php
/**
 * models/PresenceModel.php
 * Gestion des présences aux réunions — PDO + POO
 */

require_once __DIR__ . '/../config/database.php';

class PresenceModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // ── Récupérer les présences d'une réunion (avec nom membre) ──
    public function getParReunion(int $idReunion): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT u.id AS id_membre, u.nom, u.email,
                    COALESCE(p.statut, 'absent') AS statut
             FROM utilisateurs u
             LEFT JOIN presences p
               ON p.id_membre = u.id AND p.id_reunion = :id_reunion
             WHERE u.role = 'membre' 
             ORDER BY u.nom ASC"
        );
        $stmt->execute([':id_reunion' => $idReunion]);
        return $stmt->fetchAll();
    }

    // ── Enregistrer ou mettre à jour une présence ─────────────
    public function enregistrer(int $idReunion, int $idMembre, string $statut): bool
{
        $statutsValides = ['present', 'absent'];
        if (!in_array($statut, $statutsValides)) return false;

        $stmt = $this->pdo->prepare(
            "INSERT INTO presences (id_reunion, id_membre, statut)
            VALUES (:id_reunion, :id_membre, :statut)
            ON DUPLICATE KEY UPDATE statut = VALUES(statut)"
        );
        return $stmt->execute([
            ':id_reunion' => $idReunion,
            ':id_membre'  => $idMembre,
            ':statut'     => $statut,
        ]);
}

    // ── Enregistrer toute la liste d'une réunion en une fois ──
    public function enregistrerTout(int $idReunion, array $presences): bool
    {
        // $presences = [ id_membre => 'present'|'absent', ... ]
        foreach ($presences as $idMembre => $statut) {
            $this->enregistrer($idReunion, (int)$idMembre, $statut);
        }
        return true;
    }

    // ── Stats présences pour une réunion ──────────────────────
    public function statsReunion(int $idReunion): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT statut, COUNT(*) AS total
             FROM presences
             WHERE id_reunion = :id_reunion
             GROUP BY statut"
        );
        $stmt->execute([':id_reunion' => $idReunion]);
        $rows = $stmt->fetchAll();
        $result = ['present' => 0, 'absent' => 0];
        foreach ($rows as $r) {
            $result[$r['statut']] = (int)$r['total'];
        }
        return $result;
    }

    // ── Taux de présence global tous membres ──────────────────
    public function tauxPresenceGlobal(): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT
               SUM(statut = 'present') AS presents,
               COUNT(*) AS total
             FROM presences"
        );
        $stmt->execute();
        $r = $stmt->fetch();
        if (!$r || (int)$r['total'] === 0) return 0.0;
        return round((int)$r['presents'] / (int)$r['total'] * 100, 1);
    }
}