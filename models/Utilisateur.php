<?php

//CRUD 

class Utilisateur 
{
	private $bdd;
	function __construct($bdd)
	{
		$this->bdd = $bdd;
	}

	public function ajouterUtilisateur($nom, $prenom, $email, $mot_de_passe, $telephone, $type_utilisateur)
	{
		$req = $this->bdd->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, type_utilisateur) 
                                   VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :type_utilisateur)");
		$req->bindParam(':nom', $nom);
		$req->bindParam(':prenom', $prenom);
		$req->bindParam(':email', $email);
		$req->bindParam(':mot_de_passe', password_hash($mot_de_passe, PASSWORD_DEFAULT));
		$req->bindParam(':telephone', $telephone);
		$req->bindParam(':type_utilisateur', $type_utilisateur);

		return $req->execute();
	}

	public function allUtilisateur()
	{
		$req = $this->bdd->prepare("SELECT * FROM utilisateurs");
		$req->execute();
		return $req->fetchAll();
	}



	public function supprimerUtilisateur($id)
	{
		$req = $this->bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
		return $req->execute([$id]);
	}

    public function updateUtilisateur($nom, $prenom, $email, $telephone, $type_utilisateur, $id)
    {
        $stmt = $this->bdd->prepare("UPDATE utilisateurs 
                                    SET nom = :nom, 
                                        prenom = :prenom, 
                                        email = :email,
                                        telephone = :telephone,
                                        type_utilisateur = :type_utilisateur 
                                    WHERE id = :id");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':type_utilisateur', $type_utilisateur);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getUtilisateurById($id) {
		$stmt = $this->bdd->prepare('SELECT id, nom, prenom, email, telephone, date_inscription 
									 FROM utilisateurs 
									 WHERE id = ?');
		$stmt->execute([$id]);
		return $stmt->fetch();
    }


    public function verifierLogin($email, $mot_de_passe) {
		$stmt = $this->bdd->prepare('SELECT * FROM utilisateurs WHERE email = ?');
		$stmt->execute([$email]);
		$utilisateur = $stmt->fetch();
	
		if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
			return $utilisateur;
		}
		return false;
	}

    public function countUtilisateurs() {
        $stmt = $this->bdd->prepare('SELECT COUNT(*) as total FROM utilisateurs');
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}

?>