//CRUD

<?php

include('../bdd/Database.php');

include('../models/escapegame.php');

if(isset($_POST['action'])) {
	
    $sallesController = new sallesController($bdd);

    switch ($_POST['action']) {

        case 'ajouter':
            $sallesController->create();
            break;

        case 'supprimer':
            $sallesController->delete();
            break;

        case 'update':
		 $sallesController->update();
            break;

        case 'ajouterHoraire':
         $sallesController->ajouterHoraire();
            break;

        case 'ajouterHoraireRecurent':
         $sallesController->ajouterHoraireRecurent();
            break;

        case 'ajouterHorairesHebdomadaires':
         $sallesController->ajouterHorairesHebdomadaires();
            break;
            
        case 'supprimerHoraire':
         $sallesController->supprimerHoraire(); 
            break;

        case 'getsallebyprixrange':
         $sallesController->getsallebyprixrange($_POST['min_price'], $_POST['max_price']);
            break;

        case 'getsallebyplayerrange':
         $sallesController->getsallebyplayerrange($_POST['min_players'], $_POST['max_players']);
            break;

        default:
            # code...
            break;
    }
}

class sallesController
{
    private $salles;

    function __construct($bdd)
    {
        $this->salles = new EscapeGame($bdd);
    }

    public function create()
    {
        if(isset($_POST['image'])) {
            $this->salles->ajouterSalle($_POST['nom'], $_POST['theme_id'], $_POST['description'], $_POST['duree'], $_POST['nb_joueurs_min'], $_POST['nb_joueurs_max'], $_POST['prix'], $_POST['image']);

            header('Location: /projetfin/index.php?page=gestionSalles&success=salleAjouter');
        }else{
            $this->salles->ajouterSalle($_POST['nom'], $_POST['theme_id'], $_POST['description'], $_POST['duree'], $_POST['nb_joueurs_min'], $_POST['nb_joueurs_max'], $_POST['prix'], '');

            header('Location: /projetfin/index.php?page=gestionSalles&success=salleAjouter');
        }
    }

    public function update()
    {
        if(isset($_POST['image'])) {
            $this->salles->updateSalle($_POST['id'], $_POST['nom'], $_POST['theme_id'], $_POST['description'], $_POST['duree'], $_POST['nb_joueurs_min'], $_POST['nb_joueurs_max'], $_POST['prix'], $_POST['image']);

            header('Location: /projetfin/index.php?page=gestionSalles&success=salleModifier');
        }else{
            $this->salles->updateSalle($_POST['id'], $_POST['nom'], $_POST['theme_id'], $_POST['description'], $_POST['duree'], $_POST['nb_joueurs_min'], $_POST['nb_joueurs_max'], $_POST['prix'], '');

            header('Location: /projetfin/index.php?page=gestionSalles&success=salleModifier');
        }
    }

    public function delete()
    {
        $this->salles->supprimerSalle($_POST['id']);

        header('Location: /projetfin/index.php?page=gestionSalles&success=salleSupprimer');
    }

    public function ajouterHoraire()
    {
        // Combiner la date avec les heures
        $date = $_POST['date'];
        $heure_debut = $date . ' ' . $_POST['heure_debut'] . ':00';
        $heure_fin = $date . ' ' . $_POST['heure_fin'] . ':00';

        $this->salles->ajouterHoraire($_POST['salle_id'], $heure_debut, $heure_fin);

        header('Location:/projetfin/index.php?page=gestionSalles&success=horaireAjouter');
    }

    public function ajouterHoraireRecurent()
    {
        if (isset($_POST['salle_id'], $_POST['heure_debut'], $_POST['heure_fin'], $_POST['jour'], $_POST['mois'])) {
            $this->salles->ajouterHoraireRecurent(
                $_POST['salle_id'],
                $_POST['heure_debut'],
                $_POST['heure_fin'],
                $_POST['jour'],
                $_POST['mois']
            );
            header('Location: /projetfin/index.php?page=gestionSalles&success=horaireRecurentAjouter');
        } else {
            header('Location: /projetfin/index.php?page=gestionSalles&error=formulaireIncomplet');
        }
        exit();
    }

    public function ajouterHorairesHebdomadaires()
    {
        if (isset($_POST['salle_id'], $_POST['date_debut'], $_POST['heure_debut'], $_POST['heure_fin'])) {
            $success = $this->salles->ajouterHorairesHebdomadaires(
                $_POST['salle_id'],
                $_POST['date_debut'],
                $_POST['heure_debut'],
                $_POST['heure_fin']
            );
            
            if ($success) {
                header('Location: /projetfin/index.php?page=gestionSalles&success=horairesHebdomadairesAjoutes');
            } else {
                header('Location: /projetfin/index.php?page=gestionSalles&error=erreurHorairesHebdomadaires');
            }
        } else {
            header('Location: /projetfin/index.php?page=gestionSalles&error=formulaireIncomplet');
        }
        exit();
    }

    public function supprimerHoraire()
    {
        $this->salles->supprimerHoraire($_POST['id']);

        header('Location: /projetfin/index.php?page=gestionSalles&success=horaireSupprimer');
    }

    

    public function getsallebyprixrange()
    {
        header('Location: /projetfin/index.php?page=reservation&min_price=' . $_POST['min_price'] . '&max_price=' . $_POST['max_price']);
    }

    public function getsallebyplayerrange()
    {
        header('Location: /projetfin/index.php?page=reservation&min_players=' . $_POST['min_players'] . '&max_players=' . $_POST['max_players']);
    }

    


}

?>