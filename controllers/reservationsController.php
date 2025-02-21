<?php

include('../bdd/Database.php');

include('../models/reservation.php');

if(isset($_POST['action'])) {
	
    $reservationController = new reservationsController($bdd);

    switch ($_POST['action']) {

        case 'ajouter':
        $reservationController->create();
            break;

        case 'annuler':
        $reservationController->annuler();
            break;

        case 'update':
		 $reservationController->update();
            break;

        default:
            # code...
            break;
    }
}

class reservationsController
{
    private $reservations;

    function __construct($bdd)
    {
        $this->reservations = new reservation($bdd);
    }

    public function create()
    {
        $this->reservations->creerReservation($_POST['utilisateur_id'], $_POST['salle_id'], $_POST['horaire_id'], $_POST['nb_participants'], $_POST['prix_total']);
        
        header('Location: /projetfin/index.php?page=gestionReservations&success=ReservationAjouter');
    }


    public function annuler()
    {
        $this->reservations->annulerReservation($_POST['id']);

        header('Location: /projetfin/index.php?page=gestionReservations&success=ReservationAnnuler');
    }

    public function afficherSallesbyprix()
    {
        

    }

    


}

?>