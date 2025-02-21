<?php


class EscapeGame {
    private $bdd;

    function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Méthodes pour les salles
    public function ajouterSalle($nom, $theme_id, $description, $histoire, $duree, $nb_joueurs_min, $nb_joueurs_max, $prix, $image) {
        $req = $this->bdd->prepare("INSERT INTO salles (nom, theme_id, description, histoire, duree, nb_joueurs_min, nb_joueurs_max, prix, image) 
                                   VALUES (:nom, :theme_id, :description, :histoire, :duree, :nb_joueurs_min, :nb_joueurs_max, :prix, :image)");
        $req->bindParam(':nom', $nom);
        $req->bindParam(':theme_id', $theme_id);
        $req->bindParam(':description', $description);
        $req->bindParam(':histoire', $histoire);
        $req->bindParam(':duree', $duree);
        $req->bindParam(':nb_joueurs_min', $nb_joueurs_min);
        $req->bindParam(':nb_joueurs_max', $nb_joueurs_max);
        $req->bindParam(':prix', $prix);
        $req->bindParam(':image', $image);

        return $req->execute();
    }

    public function getAllSalles() {
        $req = $this->bdd->prepare("SELECT * FROM salles");
        $req->execute();
        return $req->fetchAll();
    }

    public function getSalleById($id) {
        $req = $this->bdd->prepare("SELECT * FROM salles WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        return $req->fetch();
    }

    public function updateSalle($id, $nom, $theme_id, $description, $duree, $nb_joueurs_min, $nb_joueurs_max, $prix, $image) {
        $req = $this->bdd->prepare("UPDATE salles 
                                   SET nom = :nom, 
                                       theme_id = :theme_id, 
                                       description = :description, 
                                       duree = :duree, 
                                       nb_joueurs_min = :nb_joueurs_min, 
                                       nb_joueurs_max = :nb_joueurs_max, 
                                       prix = :prix,
                                       image = :image 
                                   WHERE id = :id");
        
        $req->bindParam(':id', $id);
        $req->bindParam(':nom', $nom);
        $req->bindParam(':theme_id', $theme_id);
        $req->bindParam(':description', $description);
        $req->bindParam(':duree', $duree);
        $req->bindParam(':nb_joueurs_min', $nb_joueurs_min);
        $req->bindParam(':nb_joueurs_max', $nb_joueurs_max);
        $req->bindParam(':prix', $prix);
        $req->bindParam(':image', $image);

        return $req->execute();
    }

    public function getImage($salle_id) {
        $req = $this->bdd->prepare("SELECT image FROM salles WHERE id = :id");
        $req->bindParam(':id', $salle_id);
        $req->execute();
        return $req->fetch()['image'];
    }

    public function supprimerSalle($id) {
        $req = $this->bdd->prepare("DELETE FROM salles WHERE id = :id");
        $req->bindParam(':id', $id);
        return $req->execute();
    }

    
    // Récupère les salles par tranche de prix
    public function getSallesByPriceRange($min, $max) {
        $req = $this->bdd->prepare("SELECT * FROM salles WHERE prix >= :min AND prix <= :max");
        $req->bindParam(':min', $min);
        $req->bindParam(':max', $max);
        $req->execute();
        return $req->fetchAll();
    }

    
    // Récupère les salles par tranche de joueurs
    public function getSallesByPlayerRange($min, $max) {
        $req = $this->bdd->prepare("SELECT * FROM salles WHERE nb_joueurs_min >= :min AND nb_joueurs_max <= :max");
        $req->bindParam(':min', $min);
        $req->bindParam(':max', $max);
        $req->execute();
        return $req->fetchAll();
    }

    

    // Méthodes pour les thèmes
    public function getAllThemes() {
        $req = $this->bdd->prepare("SELECT * FROM themes");
        $req->execute();
        return $req->fetchAll();
    }

    public function ajouterTheme($nom, $description) {
        $req = $this->bdd->prepare("INSERT INTO themes (nom, description) VALUES (:nom, :description)");
        $req->bindParam(':nom', $nom);
        $req->bindParam(':description', $description);
        return $req->execute();
    }

    public function supprimerTheme($id) {
        $req = $this->bdd->prepare("DELETE FROM themes WHERE id = :id");
        $req->bindParam(':id', $id);
        return $req->execute();
    }

    public function updateTheme($id, $nom, $description) {
        $req = $this->bdd->prepare("UPDATE themes 
                                   SET nom = :nom, 
                                       description = :description 
                                   WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->bindParam(':nom', $nom);
        $req->bindParam(':description', $description);
        return $req->execute();
    }

    // Méthodes pour les horaires
    public function getHorairesBySalle($salle_id) {
        $req = $this->bdd->prepare("SELECT * FROM horaires WHERE salle_id = :salle_id AND disponible = 1");
        $req->bindParam(':salle_id', $salle_id);
        $req->execute();
        return $req->fetchAll();
    }

    public function getHoraireById($id) {
        $req = $this->bdd->prepare("SELECT * FROM horaires WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        return $req->fetch();
    }

    public function ajouterHoraire($salle_id, $heure_debut, $heure_fin) {
        $req = $this->bdd->prepare("INSERT INTO horaires (salle_id, heure_debut, heure_fin) 
                                   VALUES (:salle_id, :heure_debut, :heure_fin)");
        $req->bindParam(':salle_id', $salle_id);
        $req->bindParam(':heure_debut', $heure_debut);
        $req->bindParam(':heure_fin', $heure_fin);
        return $req->execute();
    }

    public function updateHoraireDisponibilite($id, $disponible) {
        $req = $this->bdd->prepare("UPDATE horaires SET disponible = :disponible WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->bindParam(':disponible', $disponible);
        return $req->execute();
    }

    public function ajouterHoraireRecurent($salle_id, $heure_debut, $heure_fin, $jour, $mois) {
        try {
            // Créer les horaires pour les 4 prochains mois
            for ($i = 0; $i < 4; $i++) {
                $date = new DateTime();
                $date->modify('first day of +' . $i . ' month'); // Premier jour du mois suivant
                $date->modify('+' . ($jour - 1) . ' days'); // Ajouter le nombre de jours pour atteindre la date souhaitée

                // Vérifier si le jour existe dans ce mois
                if ($date->format('d') == $jour) {
                    $debut = $date->format('Y-m-d') . ' ' . $heure_debut . ':00';
                    $fin = $date->format('Y-m-d') . ' ' . $heure_fin . ':00';

                    // Utiliser la fonction existante pour ajouter l'horaire
                    $this->ajouterHoraire($salle_id, $debut, $fin);
                }
            }
            return true;
        } catch (Exception $e) {
            // Gérer l'erreur
            error_log("Erreur lors de l'ajout d'horaires récurrents : " . $e->getMessage());
            return false;
        }
    }

    public function updateHoraire($id, $heure_debut, $heure_fin) {
        $req = $this->bdd->prepare("UPDATE horaires 
                                   SET heure_debut = :heure_debut, 
                                       heure_fin = :heure_fin 
                                   WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->bindParam(':heure_debut', $heure_debut);
        $req->bindParam(':heure_fin', $heure_fin);
        return $req->execute();
    }

    public function supprimerHoraire($id) {
        $req = $this->bdd->prepare("DELETE FROM horaires WHERE id = :id");
        $req->bindParam(':id', $id);
        return $req->execute();
    }

    public function afficherHoraires() {
        $req = $this->bdd->prepare("SELECT * FROM horaires ORDER BY salle_id");
        $req->execute();
        return $req->fetchAll();
    }

    public function getHoraireBySalleId($salle_id) {
        $now = date('Y-m-d H:i:s');
        $req = $this->bdd->prepare("SELECT * FROM horaires WHERE salle_id = :salle_id AND heure_debut >= :now AND disponible = 1 ORDER BY heure_debut");
        $req->bindParam(':salle_id', $salle_id);
        $req->bindParam(':now', $now);
        $req->execute();
        return $req->fetchAll();
    }

    public function ajouterHorairesHebdomadaires($salle_id, $date_debut, $heure_debut, $heure_fin) {
        try {
            $date = new DateTime($date_debut);
            
            // Créer un horaire pour chaque jour de la semaine
            for ($i = 0; $i < 7; $i++) {
                $debut = $date->format('Y-m-d') . ' ' . $heure_debut . ':00';
                $fin = $date->format('Y-m-d') . ' ' . $heure_fin . ':00';
                
                // Ajouter l'horaire pour ce jour
                $this->ajouterHoraire($salle_id, $debut, $fin);
                
                // Passer au jour suivant
                $date->modify('+1 day');
            }
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout d'horaires hebdomadaires : " . $e->getMessage());
            return false;
        }
    }

    // Méthodes pour les réservations = CRUD
    public function creerReservation($utilisateur_id, $salle_id, $horaire_id, $nb_participants, $prix_total) {
        $req = $this->bdd->prepare("INSERT INTO reservations (utilisateur_id, salle_id, horaire_id, nb_participants, prix_total) 
                                   VALUES (:utilisateur_id, :salle_id, :horaire_id, :nb_participants, :prix_total)");
        
        $req->bindParam(':utilisateur_id', $utilisateur_id);
        $req->bindParam(':salle_id', $salle_id);
        $req->bindParam(':horaire_id', $horaire_id);
        $req->bindParam(':nb_participants', $nb_participants);
        $req->bindParam(':prix_total', $prix_total);

        if ($req->execute()) {
            $this->updateHoraireDisponibilite($horaire_id, 0);
            return $this->bdd->lastInsertId();
        }
        return false;
    }

    public function getReservationsByUtilisateur($utilisateur_id) {
        $req = $this->bdd->prepare("SELECT * FROM reservations WHERE utilisateur_id = :utilisateur_id");
        $req->bindParam(':utilisateur_id', $utilisateur_id);
        $req->execute();
        return $req->fetchAll();
    }

    public function annulerReservation($reservation_id) {
        $req = $this->bdd->prepare("SELECT horaire_id FROM reservations WHERE id = :id");
        $req->bindParam(':id', $reservation_id);
        $req->execute();
        $horaire_id = $req->fetch()['horaire_id'];

        $req = $this->bdd->prepare("DELETE FROM reservations WHERE id = :id");
        $req->bindParam(':id', $reservation_id);
        if ($req->execute()) {
            return $this->updateHoraireDisponibilite($horaire_id, 1);
        }
        return false;
    }

}
?>