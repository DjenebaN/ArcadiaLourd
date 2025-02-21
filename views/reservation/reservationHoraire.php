<?php


// Vérification de la connexion
if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=connectioninscription');
    exit();
}

// Vérification des paramètres
if (!isset($_GET['id']) || !isset($_GET['salle_id'])) {
    header('Location: index.php?page=reservation');
    exit();
}

require_once('bdd/Database.php');
include('controllers/affichage/afficherEscapegame.php');

// Récupération des informations
$salle = $escapegame->getSalleById($_GET['salle_id']);
$horaire = $escapegame->getHoraireById($_GET['id']);

if (!$salle || !$horaire) {
    header('Location: index.php?page=reservation');
    exit();
}

?>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Réserver <?= htmlspecialchars($salle['nom']) ?></h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($horaire['heure_debut'])) ?></p>
                        <p><strong>Horaire:</strong> <?= date('H:i', strtotime($horaire['heure_debut'])) ?> - <?= date('H:i', strtotime($horaire['heure_fin'])) ?></p>
                        <p><strong>Durée:</strong> <?= $salle['duree'] ?> minutes</p>
                        <p><strong>Prix:</strong> <?= $salle['prix'] ?>€</p>
                    </div>

                    <form action="controllers/reservationsController.php" method="post">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="utilisateur_id" value="<?= $_SESSION['utilisateur']['id'] ?>">
                        <input type="hidden" name="salle_id" value="<?= $salle['id'] ?>">
                        <input type="hidden" name="horaire_id" value="<?= $horaire['id'] ?>">
                        <input type="hidden" name="prix_total" value="<?= $salle['prix'] ?>">

                        <div class="mb-3">
                            <label for="nb_participants" class="form-label">Nombre de participants</label>
                            <input type="number" class="form-control" id="nb_participants" name="nb_participants" 
                                   min="<?= $salle['nb_joueurs_min'] ?>" max="<?= $salle['nb_joueurs_max'] ?>" 
                                   required>
                            <div class="form-text">
                                Entre <?= $salle['nb_joueurs_min'] ?> et <?= $salle['nb_joueurs_max'] ?> participants
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=horairesSalle&id=<?= $salle['id'] ?>" class="btn btn-secondary">Retour</a>
                            <input type="hidden" name="action" value="ajouter">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Confirmer la réservation
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

