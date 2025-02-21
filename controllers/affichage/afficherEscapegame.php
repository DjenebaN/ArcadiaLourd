<?php


include('bdd/Database.php');
include('models/escapegame.php');
$escapegame = new EscapeGame($bdd);

// Récupération des réservations avec les informations des salles

$allThemes = $escapegame->getAllThemes();

if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $allsalles = $escapegame->getSallesByPriceRange($_GET['min_price'], $_GET['max_price']);
} elseif (isset($_GET['min_players']) && isset($_GET['max_players'])) {
    $allsalles = $escapegame->getSallesByPlayerRange($_GET['min_players'], $_GET['max_players']);
}else{
    $allsalles = $escapegame->getAllSalles();
}
?>

