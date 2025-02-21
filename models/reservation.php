<?php


class reservation {
private $bdd;
    function __construct($bdd)
	{
		$this->bdd = $bdd;
        
	}
    // Méthodes pour les réservations
    public function creerReservation($utilisateur_id, $salle_id, $horaire_id, $nb_participants, $prix_total) {
        $req = $this->bdd->prepare("INSERT INTO reservations (utilisateur_id, salle_id, horaire_id, nb_participants, prix_total) 
                                   VALUES (:utilisateur_id, :salle_id, :horaire_id, :nb_participants, :prix_total)");
        
        $req->bindParam(':utilisateur_id', $utilisateur_id);
        $req->bindParam(':salle_id', $salle_id);
        $req->bindParam(':horaire_id', $horaire_id);
        $req->bindParam(':nb_participants', $nb_participants);
        $req->bindParam(':prix_total', $prix_total);

        if ($req->execute()) {
            // Mettre à jour la disponibilité de l'horaire
            $this->updateHoraireDisponibilite($horaire_id, false);
            return $this->bdd->lastInsertId();
        }
        return false;
    }

    public function getReservationsByUtilisateur($utilisateur_id) {
        $req = $this->bdd->prepare("SELECT r.id, r.salle_id, r.horaire_id, r.utilisateur_id, r.nb_participants, r.prix_total, s.nom, h.heure_debut, h.heure_fin 
                                    FROM reservations r 
                                    JOIN salles s ON r.salle_id = s.id 
                                    JOIN horaires h ON r.horaire_id = h.id 
                                    WHERE r.utilisateur_id = ?
                                    ORDER BY h.heure_debut DESC");
        $req->execute([$utilisateur_id]);
        return $req->fetchAll();
    }


    //fonctionnalité annuler une reservation en plus
    public function annulerReservation($reservation_id) {
        // Récupérer l'horaire_id avant de supprimer la réservation
        $req = $this->bdd->prepare("SELECT horaire_id FROM reservations WHERE id = ?");
        $req->execute([$reservation_id]);
        $horaire_id = $req->fetch()['horaire_id'];

        // Supprimer la réservation
        $req = $this->bdd->prepare("DELETE FROM reservations WHERE id = ?");
        if ($req->execute([$reservation_id])) {
            // Remettre l'horaire comme disponible
            return $this->updateHoraireDisponibilite($horaire_id, true);
        }
        return false;
    }

    
    public function getAllReservations() {
        $req = $this->bdd->prepare("SELECT r.id, r.salle_id, r.horaire_id, r.utilisateur_id, r.nb_participants, r.prix_total, r.status_id AS stat, s.nom, h.heure_debut, h.heure_fin, u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, t.etat AS status_nom
                                    FROM reservations r 
                                    JOIN salles s ON r.salle_id = s.id 
                                    JOIN horaires h ON r.horaire_id = h.id 
                                    JOIN utilisateurs u ON r.utilisateur_id = u.id
                                    JOIN status_res t ON r.status_id = t.id
                                    WHERE h.heure_debut > NOW()
                                    ORDER BY h.heure_debut ASC");
        $req->execute();
        return $req->fetchAll();
    }

    public function getReservationsSansGamemaster() {
        $req = $this->bdd->prepare("SELECT r.id, r.salle_id, r.horaire_id, r.utilisateur_id, 
                                          r.nb_participants, r.prix_total, s.nom, 
                                          h.heure_debut, h.heure_fin,
                                          u.nom as utilisateur_nom, u.prenom as utilisateur_prenom
                                   FROM reservations r 
                                   JOIN salles s ON r.salle_id = s.id 
                                   JOIN horaires h ON r.horaire_id = h.id 
                                   JOIN utilisateurs u ON r.utilisateur_id = u.id
                                   LEFT JOIN gamemaster_assignments ga ON r.id = ga.reservation_id 
                                   WHERE ga.id IS NULL 
                                   AND h.heure_debut > NOW()
                                   ORDER BY h.heure_debut ASC");
        $req->execute();
        return $req->fetchAll();
    }


    private function updateHoraireDisponibilite($horaire_id, $disponible) {
        $req = $this->bdd->prepare("UPDATE horaires SET disponible = ? WHERE id = ?");
        return $req->execute([$disponible ? 1 : 0 , $horaire_id]);
    }

    public function reservationbyGM($gamemaster_id)
    {
        $req = $this->bdd->prepare("SELECT r.*, s.nom as salle_nom, h.heure_debut, h.heure_fin,
                                          u.nom as utilisateur_nom, u.prenom as utilisateur_prenom
                                   FROM reservations r
                                   JOIN gamemaster_assignments ga ON r.id = ga.reservation_id
                                   JOIN salles s ON r.salle_id = s.id
                                   JOIN horaires h ON r.horaire_id = h.id
                                   JOIN utilisateurs u ON r.utilisateur_id = u.id
                                   WHERE ga.gamemaster_id = :gamemaster_id
                                   ORDER BY h.heure_debut ASC");
        $req->bindParam(':gamemaster_id', $gamemaster_id);
        $req->execute();
        return $req->fetchAll();
    }   


}

?>