<?php


include('bdd/Database.php');
include('models/utilisateur.php');
$utilisateur = new utilisateur($bdd);


$allUsers = $utilisateur->allUtilisateur();
?>