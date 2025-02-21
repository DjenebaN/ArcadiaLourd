
<?php
//CRUD gestion des gameMaster

class GameMaster
{
 private   $bdd;
	function __construct($bdd)
	{
		$this->bdd = $bdd;
	}

    public function giveMaster($id)
    {
        $req = $this->bdd->prepare("UPDATE utilisateurs SET type_utilisateur = 2 WHERE id = :id");
        $req->bindParam(':id', $id);
        return $req->execute();
    }

    public function deleteMaster($id)
    {
        $req = $this->bdd->prepare("UPDATE utilisateurs SET type_utilisateur = 1 WHERE id = :id");
        $req->bindParam(':id', $id);
        return $req->execute();
    }

    public function assignationMaster($reservation_id, $gamemaster_id)
    {
        $req = $this->bdd->prepare("INSERT INTO gamemaster_assignments (reservation_id, gamemaster_id, statutGM) 
                                   VALUES (:reservation_id, :gamemaster_id, 1)");
        $req->bindParam(':reservation_id', $reservation_id);
        $req->bindParam(':gamemaster_id', $gamemaster_id);
        return $req->execute();
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