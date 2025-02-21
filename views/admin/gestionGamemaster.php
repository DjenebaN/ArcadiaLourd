<?php
include('controllers/affichage/afficherUtilisateur.php');
?>

<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-4 mb-1">Gestion des Game Masters</h1>
                    <p class="text-muted">Gérez les accès et les permissions des Game Masters</p>
                </div>
                <div class="badge bg-primary p-3">
                    <i class="fas fa-users-cog fa-2x"></i>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 bg-primary text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Total Utilisateurs</h6>
                                    <h2 class="mb-0"><?= count($allUsers) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-success text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Game Masters Actifs</h6>
                                    <h2 class="mb-0"><?= count(array_filter($allUsers, function($user) { return $user['type_utilisateur'] == 2; })) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-user-shield fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-info text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Utilisateurs Standards</h6>
                                    <h2 class="mb-0"><?= count(array_filter($allUsers, function($user) { return $user['type_utilisateur'] == 1; })) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white pt-4 pb-3 border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control bg-light border-0 py-3" id="searchInput" 
                                       placeholder="Rechercher un utilisateur..." aria-label="Rechercher">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 'GameMasterAjouter'): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>L'ajout du Game Master a été effectué avec succès.</div>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-4">Utilisateur</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col" class="text-center">Statut</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $user): ?>
                                <tr class="searchable-item align-middle">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-envelope text-muted me-2"></i>
                                            <?= htmlspecialchars($user['email']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($user['type_utilisateur'] == 2): ?>
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Game Master
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                <i class="fas fa-user me-1"></i>Utilisateur
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <form method="post" action="controllers/gamemasterController.php" class="d-inline">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <?php if ($user['type_utilisateur'] == 2): ?>
                                                <input type="hidden" name="action" value="supprimer">
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-4" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir retirer les droits de Game Master à cet utilisateur ?')">
                                                    <i class="fas fa-user-minus me-2"></i>Retirer accès
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="ajouter">
                                                <button type="submit" class="btn btn-outline-primary btn-sm px-4">
                                                    <i class="fas fa-user-plus me-2"></i>Donner accès
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    document.querySelectorAll('.searchable-item').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});
</script>
