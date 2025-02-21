<?php

include('../bdd/Database.php');

include('../models/Utilisateur.php');

if(isset($_POST['action'])) {
	
    $utilisateurController = new UtilisateurController($bdd);

    switch ($_POST['action']) {

        case 'ajouter':
            $utilisateurController->create();
            break;

        case 'supprimer':
            $utilisateurController->delete();
            break;

        case 'connexion':
		 $utilisateurController->login();
            break;

        case 'update':
         $utilisateurController->update();
            break;

        default:
            # code...
            break;
    }
}

class UtilisateurController 
{
    private $utilisateur;
    private $typeUser = 1;
    function __construct($bdd)
    {
        $this->utilisateur = new Utilisateur($bdd);
    }

    public function create()
    {

        $this->utilisateur->ajouterUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mot_de_passe'], $_POST['telephone'], $this->typeUser);

        header('Location: /projetfin/index.php?page=connectioninscription&success=inscription');

    }

    public function update()
    {
        /* Mettre a jour le compte utilisateur APRES LA CONNEXION (d'ou le manque de mdp) */ 
        $this->utilisateur->updateUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $this->typeUser, $_POST['id']);

        header('Location:/projetfin/index.php?page=modifierProfil&success=compteModifier');

    }

    public function delete()
    {
        $this->utilisateur->supprimerUtilisateur($_POST['id']);
    }

    public function login()
    {
        $utilisateur = $this->utilisateur->verifierLogin($_POST['email'], $_POST['mot_de_passe']);

        if ($utilisateur){
            session_start();
            $_SESSION['utilisateur'] = $utilisateur;
            header('Location: /projetfin/index.php?page=accueil');
        }else{
            header('Location: /projetfin/index.php?page=connectioninscription');
        }
    }

    public function readOne($id){
        return $this->utilisateur->getUtilisateurById($id);
    }

    public function deconnexion()
    {
        session_destroy();
        header('Location: /projetfin/');
    }


}

?>
