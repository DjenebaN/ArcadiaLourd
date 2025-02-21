<?php
include('controllers/affichage/afficherEscapegame.php');


?>

<div class="container py-5">
    <h1 class="display-4 text-center mb-5">Réserver une salle</h1>
    

    <form action="controllers/sallesController.php" method="post">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="min_price" class="form-label">Prix minimum</label>
                <input type="number" class="form-control" id="min_price" name="min_price" min="0" step="1">
            </div>
            <div class="col-md-4">
                <label for="max_price" class="form-label">Prix maximum</label>
                <input type="number" class="form-control" id="max_price" name="max_price" min="0" step="1">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4" name="action" value="getsallebyprixrange">Filtrer par prix</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="min_players" class="form-label">Nombre de joueurs minimum</label>
                <input type="number" class="form-control" id="min_players" name="min_players" min="1" step="1">
            </div>
            <div class="col-md-4">
                <label for="max_players" class="form-label">Nombre de joueurs maximum</label>
                <input type="number" class="form-control" id="max_players" name="max_players" min="1" step="1">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4" name="action" value="getsallebyplayerrange">Filtrer par nombre de joueurs</button>
            </div>
        </div>
    </form>
    

    



    <?php if (empty($allsalles)): ?>
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Aucune salle disponible.
        </div>
    <?php else: ?>
        <!-- Liste des salles -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($allsalles as $salle): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm hover-card">
                        <?php if (!empty($salle['image'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($salle['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($salle['nom']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-door-closed fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h2 class="card-title h4 mb-3"><?= htmlspecialchars($salle['nom']) ?></h2>
                            <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($salle['description']) ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="small text-muted">
                                    <p class="mb-1">
                                        <i class="fas fa-users me-2"></i>
                                        <?= $salle['nb_joueurs_min'] ?> - <?= $salle['nb_joueurs_max'] ?> joueurs
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <?= $salle['duree'] ?> minutes
                                    </p>
                                </div>
                                <div class="h4 mb-0 text-primary fw-bold">
                                    <?= $salle['prix'] ?>€
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="index.php?page=salle-details&id=<?= $salle['id'] ?>" class="btn btn-outline-primary mb-2">
                                    <i class="fas fa-info-circle me-2"></i>Plus d'informations
                                </a>
                                <a href="index.php?page=horairesSalle&id=<?= $salle['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-calendar-alt me-2"></i>Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none;
    border-radius: 15px;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.card-img-top {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.btn-primary {
    border-radius: 30px;
    padding: 12px 20px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>
