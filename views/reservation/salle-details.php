<?php
include('controllers/affichage/afficherEscapegame.php');

// Récupérer l'ID de la salle depuis l'URL
$salle_id = isset($_GET['id']) ? $_GET['id'] : null;

// Récupérer les détails de la salle
$salle = $escapegame->getSalleById($salle_id);

if (!$salle) {
    header('Location: index.php?page=reservation');
    exit;
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg" style="border-radius: 15px; background: rgba(0, 0, 0, 0.8); border: 1px solid var(--accent-color);">
                <?php if (!empty($salle['image'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($salle['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($salle['nom']) ?>" 
                         style="height: 400px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <?php endif; ?>
                
                <div class="card-body p-5">
                    <h1 class="card-title mb-4" style="color: var(--accent-color);">
                        <?= htmlspecialchars($salle['nom']) ?>
                    </h1>
                    
                    <div class="mb-4">
                        <h4 class="text-white mb-3">Description</h4>
                        <p class="text-white" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($salle['description'])) ?>
                        </p>
                    </div>

                    <div class="mb-4" style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 2rem;">
                        <h4 class="mb-3" style="color: var(--accent-color);">Histoire de la salle</h4>
                        <p class="text-white" style="line-height: 1.8; font-style: italic;">
                            <?php if (!empty($salle['histoire'])): ?>
                                <?= nl2br(htmlspecialchars($salle['histoire'])) ?>
                            <?php else: ?>
                                <em>L'histoire de cette salle reste mystérieuse...</em>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box p-3 text-center" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px;">
                                <i class="fas fa-users fa-2x mb-2" style="color: var(--accent-color);"></i>
                                <h5 class="text-white">Joueurs</h5>
                                <p class="text-white mb-0"><?= $salle['nb_joueurs_min'] ?> - <?= $salle['nb_joueurs_max'] ?> personnes</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box p-3 text-center" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px;">
                                <i class="fas fa-clock fa-2x mb-2" style="color: var(--accent-color);"></i>
                                <h5 class="text-white">Durée</h5>
                                <p class="text-white mb-0"><?= $salle['duree'] ?> minutes</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box p-3 text-center" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px;">
                                <i class="fas fa-euro-sign fa-2x mb-2" style="color: var(--accent-color);"></i>
                                <h5 class="text-white">Prix</h5>
                                <p class="text-white mb-0"><?= $salle['prix'] ?>€ / groupe</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <?php
                        // Récupérer la page source depuis l'URL
                        $source = isset($_GET['source']) ? $_GET['source'] : 'reservation';
                        $returnUrl = $source === 'accueil' ? 'index.php' : 'index.php?page=reservation';
                        ?>
                        <a href="<?= $returnUrl ?>" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <a href="index.php?page=horairesSalle&id=<?= $salle['id'] ?>&source=<?= isset($_GET['source']) ? $_GET['source'] : 'reservation' ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-alt me-2"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
