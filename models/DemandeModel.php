<?php
/**
 * models/DemandeModel.php
 * FIX : accepter() crée l'utilisateur dans la table utilisateurs
 */

require_once __DIR__ . '/../config/database.php';

class DemandeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getTous(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_adhesion ORDER BY date_demande DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEnAttente(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_adhesion WHERE statut = 'en_attente' ORDER BY date_demande DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function compterEnAttente(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM demandes_adhesion WHERE statut = 'en_attente'");
        $stmt->execute();
        return (int) $stmt->fetch()['total'];
    }

    public function getParEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_adhesion WHERE email = :email AND statut = 'en_attente' LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Vérifie toute demande non refusée (en_attente ou acceptée)
    public function getParEmailActif(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_adhesion WHERE email = :email AND statut IN ('en_attente','accepte') LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getParId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM demandes_adhesion WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function creer(string $nom, string $email,
                      string $telephone = '', string $message = '',
                      string $motDePasse = ''): bool
    {
        $hash = !empty($motDePasse) ? password_hash($motDePasse, PASSWORD_BCRYPT) : null;
        $stmt = $this->pdo->prepare(
            "INSERT INTO demandes_adhesion (nom, email, telephone, message, mot_de_passe, statut)
             VALUES (:nom, :email, :tel, :msg, :mdp, 'en_attente')"
        );
        return $stmt->execute([':nom'=>$nom,':email'=>$email,':tel'=>$telephone,':msg'=>$message,':mdp'=>$hash]);
    }

    // FIX MAJEUR : accepter cree l'utilisateur
    public function accepter(int $id): bool
{
        $demande = $this->getParId($id);
        if (!$demande) return false;

        $this->pdo->beginTransaction();
        try {
            $s1 = $this->pdo->prepare("UPDATE demandes_adhesion SET statut = 'accepte' WHERE id = :id");
            $s1->execute([':id' => $id]);

            $sCheck = $this->pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email LIMIT 1");
            $sCheck->execute([':email' => $demande['email']]);
            $existant = $sCheck->fetch();

            if (!$existant) {
                // ✅ Le hash vient de creer() — NE PAS re-hasher
                $mdp = !empty($demande['mot_de_passe'])
                    ? $demande['mot_de_passe']
                    : password_hash('joker2024', PASSWORD_BCRYPT);

                $s2 = $this->pdo->prepare(
                    "INSERT INTO utilisateurs (nom, email, mot_de_passe, role, telephone, date_inscription)
                    VALUES (:nom, :email, :mdp, 'membre', :tel, CURDATE())"
                );
                $s2->execute([
                    ':nom'   => $demande['nom'],
                    ':email' => $demande['email'],
                    ':mdp'   => $mdp,   // ← hash direct, pas password_hash() ici
                    ':tel'   => $demande['telephone'] ?? ''
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function refuser(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE demandes_adhesion SET statut = 'refuse' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function changerStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, ['en_attente','accepte','refuse'])) return false;
        $stmt = $this->pdo->prepare("UPDATE demandes_adhesion SET statut = :statut WHERE id = :id");
        return $stmt->execute([':statut'=>$statut,':id'=>$id]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM demandes_adhesion WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function stats(): array
    {
        $stmt = $this->pdo->prepare("SELECT statut, COUNT(*) AS total FROM demandes_adhesion GROUP BY statut");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function compterVisiteurs(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total FROM demandes_adhesion");
        $stmt->execute();
        return (int) $stmt->fetch()['total'];
    }

    public function statsParMois(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT DATE_FORMAT(date_demande,'%Y-%m') AS mois, COUNT(*) AS total
             FROM demandes_adhesion
             GROUP BY mois ORDER BY mois DESC LIMIT 6"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
