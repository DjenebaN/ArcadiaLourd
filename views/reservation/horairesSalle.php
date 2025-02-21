<?php
if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=connectioninscription');
    exit();
}

include('controllers/affichage/afficherEscapegame.php');

$getsalle = $escapegame->getSalleById($_GET['id']);
$horaires = $escapegame->getHoraireBySalleId($_GET['id']);


// Regrouper les horaires par jour de la semaine
$horairesParJour = [];
$now = new DateTime();
$joursEnFrancais = [
    'Monday' => 'Lundi',
    'Tuesday' => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday' => 'Jeudi',
    'Friday' => 'Vendredi',
    'Saturday' => 'Samedi',
    'Sunday' => 'Dimanche'
];

foreach ($horaires as $horaire) {
    $horaire_date = new DateTime($horaire['heure_debut']);
    if ($horaire_date > $now && $horaire['disponible'] == 1) {
        $jourSemaine = $joursEnFrancais[$horaire_date->format('l')];
        $date = $horaire_date->format('d/m/Y');
        if (!isset($horairesParJour[$jourSemaine])) {
            $horairesParJour[$jourSemaine] = [];
        }
        if (!isset($horairesParJour[$jourSemaine][$date])) {
            $horairesParJour[$jourSemaine][$date] = [];
        }
        $horairesParJour[$jourSemaine][$date][] = $horaire;
    }
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <?php
            // Récupérer la source
            $source = isset($_GET['source']) ? $_GET['source'] : '';
            $returnUrl = $source === 'accueil' ? 'index.php' : 'index.php?page=reservation';
            ?>
            <a href="<?= $returnUrl ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <h1 class="mb-0">Horaires de la salle <?= htmlspecialchars($getsalle['nom']) ?></h1>
        </div>
    </div>

    <div class="accordion" id="accordionHoraires">
        <?php foreach ($horairesParJour as $jour => $dates): ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $jour ?>" aria-expanded="false">
                        <?= $jour ?>
                    </button>
                </h2>
                <div id="collapse<?= $jour ?>" class="accordion-collapse collapse" data-bs-parent="#accordionHoraires">
                    <div class="accordion-body">
                        <?php foreach ($dates as $date => $horairesJour): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <strong><?= $date ?></strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Début</th>
                                                    <th>Fin</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($horairesJour as $horaire): ?>
                                                    <tr>
                                                        <td><?= date('H:i', strtotime($horaire['heure_debut'])) ?></td>
                                                        <td><?= date('H:i', strtotime($horaire['heure_fin'])) ?></td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <?php if ($_SESSION['utilisateur']['type_utilisateur'] == 3): ?>
                                                                    <form action="controllers/sallesController.php" method="post" class="me-2">
                                                                        <input type="hidden" name="action" value="supprimerHoraire">
                                                                        <input type="hidden" name="id" value="<?= $horaire['id'] ?>">
                                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                                                                            <i class="fas fa-trash-alt me-2"></i>Supprimer
                                                                        </button>
                                                                    </form>
                                                                <?php endif; ?>
                                                                <a href="index.php?page=reservationHoraire&id=<?= $horaire['id'] ?>&salle_id=<?= $_GET['id'] ?>" 
                                                                   class="btn btn-primary">
                                                                    <i class="fas fa-calendar-check me-2"></i>Réserver
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: #e7f1ff;
    color: #0c63e4;
}
.btn-group {
    display: flex;
    gap: 10px;
}
.table > :not(caption) > * > * {
    padding: 1rem;
}
</style>
