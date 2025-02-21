<?php 




include('views/commun/header.php');


//system de routing

$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

switch ($page) {
	case 'connectioninscription':
		include('views/CI_compte.php');
		break;
	
	case 'deconnexion':
		include('views/commun/logout.php');
		break;
	
	case 'reservation':
		include('views/reservation/reservation.php');
		break;

	case 'horairesSalle':
		include('views/reservation/horairesSalle.php');
		break;

	case 'reservationHoraire':
		include('views/reservation/reservationHoraire.php');
		break;
	
	case 'faq':
		include('views/faq.php');
		break;
	
	case 'contact':
		include('views/contact.php');
		break;
	//Compte utilisateur
	case 'compte':
		include('views/compte.php');
		break;
	
	case 'modifierProfil':
		include('views/modifierCompte.php');
		break;

	case 'mesreservations':
		include('views/mesreservations.php');
		break;	
	//Compte admin

	case 'gestionSalles':
		include('views/admin/gestionSalles.php');
		break;

	case 'gestionGamemasters':
		include('views/admin/gestionGamemaster.php');
		break;

	case 'gestionReservations':
		include('views/admin/gestionReservations.php');
		break;

	//Compte gm

	case 'assignationGM':
		include('views/gameMaster/assignationGM.php');
		break;
	
		case 'salle-details':
		include('views/reservation/salle-details.php');
		break;


	default:
		include('views/accueil.php');
		break;
}


include('views/commun/footer.php');


?>