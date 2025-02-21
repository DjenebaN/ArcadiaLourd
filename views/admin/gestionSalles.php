<?php
include('controllers/affichage/afficherEscapegame.php');

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['type_utilisateur'] != 3) {
    header('Location: index.php?page=accueil');
    exit();
}
?>

<div class="container-fluid py-5">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-4 mb-1">Gestion des Salles</h1>
                    <p class="text-muted">Gérez vos salles d'escape game et leurs horaires</p>
                </div>
                <div class="badge bg-primary p-3">
                    <i class="fas fa-door-open fa-2x"></i>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 bg-primary text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Total Salles</h6>
                                    <h2 class="mb-0"><?= count($allsalles) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-door-closed fa-2x text-white"></i>
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
                                    <h6 class="text-white-50 mb-2">Capacité Totale</h6>
                                    <h2 class="mb-0"><?= array_sum(array_column($allsalles, 'nb_joueurs_max')) ?> joueurs</h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-white"></i>
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
                                    <h6 class="text-white-50 mb-2">Durée Moyenne</h6>
                                    <h2 class="mb-0"><?= round(array_sum(array_column($allsalles, 'duree')) / count($allsalles)) ?> min</h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-clock fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets principaux -->
    <ul class="nav nav-pills nav-fill gap-2 mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-3" id="salles-tab" data-bs-toggle="tab" data-bs-target="#salles" type="button" role="tab">
                <i class="fas fa-door-closed me-2"></i>Gestion des Salles
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-3" id="horaires-tab" data-bs-toggle="tab" data-bs-target="#horaires" type="button" role="tab">
                <i class="fas fa-clock me-2"></i>Gestion des Horaires
            </button>
        </li>
    </ul>

    <!-- Contenu des onglets principaux -->
    <div class="tab-content" id="mainTabsContent">
        <!-- Onglet Gestion des Salles -->
        <div class="tab-pane fade show active" id="salles" role="tabpanel">
            <!-- Sous-onglets pour les salles -->
            <ul class="nav nav-pills nav-fill gap-2 mb-4" id="sallesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="liste-salles-tab" data-bs-toggle="pill" data-bs-target="#liste-salles" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>Liste des Salles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="ajouter-salle-tab" data-bs-toggle="pill" data-bs-target="#ajouter-salle" type="button" role="tab">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter une Salle
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="modifier-salle-tab" data-bs-toggle="pill" data-bs-target="#modifier-salle" type="button" role="tab">
                        <i class="fas fa-edit me-2"></i>Modifier une Salle
                    </button>
                </li>
            </ul>

            <!-- Contenu des sous-onglets salles -->
            <div class="tab-content" id="sallesTabsContent">
                <!-- Liste des salles -->
                <div class="tab-pane fade show active" id="liste-salles" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 ps-4">Salle</th>
                                            <th class="border-0">Description</th>
                                            <th class="border-0 text-center">Durée</th>
                                            <th class="border-0 text-center">Capacité</th>
                                            <th class="border-0 text-center">Prix</th>
                                            <th class="border-0 text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allsalles as $salle): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded-circle p-2 me-3">
                                                            <i class="fas fa-door-closed text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?= htmlspecialchars($salle['nom']) ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-muted mb-0" style="max-width: 300px;">
                                                        <?= htmlspecialchars(substr($salle['description'], 0, 100)) ?>...
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                                        <i class="fas fa-clock me-1"></i><?= $salle['duree'] ?> min
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <div class="badge bg-success rounded-pill px-3 py-2 mb-1">
                                                            <i class="fas fa-users me-1"></i><?= $salle['nb_joueurs_min'] ?> - <?= $salle['nb_joueurs_max'] ?> joueurs
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                                        <i class="fas fa-euro-sign me-1"></i><?= $salle['prix'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                onclick="window.location.href='#modifier-salle'">
                                                            <i class="fas fa-edit me-2"></i>Modifier
                                                        </button>
                                                        <form method="post" action="controllers/sallesController.php" class="d-inline ms-2">
                                                            <input type="hidden" name="action" value="supprimer">
                                                            <input type="hidden" name="id" value="<?= $salle['id'] ?>">
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                                                <i class="fas fa-trash me-2"></i>Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ajouter une salle -->
                <div class="tab-pane fade" id="ajouter-salle" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-plus-circle text-primary me-2"></i>Ajouter une nouvelle salle
                                </h5>
                            </div>
                            <form method="post" action="controllers/sallesController.php" enctype="multipart/form-data" class="row g-4"
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label fw-bold mb-2">
                                        <i class="fas fa-signature text-primary me-2"></i>Nom de la salle
                                    </label>
                                    <input type="text" class="form-control form-control-lg bg-light border-0" 
                                           id="nom" name="nom" required placeholder="Ex: La Crypte Mystérieuse">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold mb-2">
                                        <i class="fas fa-align-left text-primary me-2"></i>Description
                                    </label>
                                    <textarea class="form-control bg-light border-0" id="description" 
                                              name="description" rows="4" required 
                                              placeholder="Décrivez l'ambiance et l'histoire de la salle..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold mb-2">
                                        <i class="fas fa-image text-primary me-2"></i>Image de la salle
                                    </label>
                                    <input type="file" class="form-control bg-light border-0" 
                                           id="image" name="image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="theme" class="form-label fw-bold mb-2">
                                        <i class="fas fa-theater-masks text-primary me-2"></i>Thème
                                    </label>
                                    <select class="form-select bg-light border-0" id="theme" name="theme_id" required>
                                        <option value="">Sélectionnez un thème</option>
                                        <?php foreach ($allThemes as $theme): ?>
                                            <option value="<?= $theme['id'] ?>"><?= htmlspecialchars($theme['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duree" class="form-label fw-bold mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>Durée de la partie
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control bg-light border-0" 
                                               id="duree" name="duree" required placeholder="60">
                                        <span class="input-group-text bg-light border-0">minutes</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="fas fa-users text-primary me-2"></i>Capacité de la salle
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0">Min</span>
                                                <input type="number" class="form-control bg-light border-0" 
                                                       id="nb_joueurs_min" name="nb_joueurs_min" required placeholder="2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0">Max</span>
                                                <input type="number" class="form-control bg-light border-0" 
                                                       id="nb_joueurs_max" name="nb_joueurs_max" required placeholder="6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="prix" class="form-label fw-bold mb-2">
                                        <i class="fas fa-euro-sign text-primary me-2"></i>Prix de la partie
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control bg-light border-0" 
                                               id="prix" name="prix" required placeholder="120">
                                        <span class="input-group-text bg-light border-0">€</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <input type="hidden" name="action" value="ajouter">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-plus-circle me-2"></i>Créer la salle
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modifier une salle -->
                <div class="tab-pane fade" id="modifier-salle" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-edit text-primary me-2"></i>Modifier une salle existante
                                </h5>
                            </div>
                            <form method="post" action="controllers/sallesController.php" enctype="multipart/form-data" class="row g-4">
                                <div class="col-12 mb-4">
                                    <label for="salle_id" class="form-label fw-bold mb-2">
                                        <i class="fas fa-door-closed text-primary me-2"></i>Sélectionner la salle à modifier
                                    </label>
                                    <select class="form-select form-select-lg bg-light border-0" id="salle_id" name="id" required>
                                        <option value="">Sélectionner une salle</option>
                                        <?php foreach ($allsalles as $salle): ?>
                                            <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label fw-bold mb-2">
                                            <i class="fas fa-signature text-primary me-2"></i>Nom de la salle
                                        </label>
                                        <input type="text" class="form-control form-control-lg bg-light border-0" 
                                               id="nom" name="nom" required placeholder="Ex: La Crypte Mystérieuse">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-bold mb-2">
                                            <i class="fas fa-align-left text-primary me-2"></i>Description
                                        </label>
                                        <textarea class="form-control bg-light border-0" id="description" 
                                                  name="description" rows="4" required 
                                                  placeholder="Décrivez l'ambiance et l'histoire de la salle..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label fw-bold mb-2">
                                            <i class="fas fa-image text-primary me-2"></i>Image de la salle
                                        </label>
                                        <input type="file" class="form-control bg-light border-0" 
                                               id="image" name="image" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="theme_edit" class="form-label fw-bold mb-2">
                                            <i class="fas fa-theater-masks text-primary me-2"></i>Thème
                                        </label>
                                        <select class="form-select bg-light border-0" id="theme_edit" name="theme_id" required>
                                            <option value="">Sélectionnez un thème</option>
                                            <?php foreach ($allThemes as $theme): ?>
                                                <option value="<?= $theme['id'] ?>" <?= ($salle['theme_id'] == $theme['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($theme['nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duree" class="form-label fw-bold mb-2">
                                            <i class="fas fa-clock text-primary me-2"></i>Durée de la partie
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-light border-0" 
                                                   id="duree" name="duree" required placeholder="60">
                                            <span class="input-group-text bg-light border-0">minutes</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-users text-primary me-2"></i>Capacité de la salle
                                        </label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0">Min</span>
                                                    <input type="number" class="form-control bg-light border-0" 
                                                           id="nb_joueurs_min" name="nb_joueurs_min" required placeholder="2">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0">Max</span>
                                                    <input type="number" class="form-control bg-light border-0" 
                                                           id="nb_joueurs_max" name="nb_joueurs_max" required placeholder="6">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (€)</label>
                                    <input type="number" class="form-control" id="prix" name="prix" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="update">
                        <button type="submit" class="btn btn-primary">Modifier la salle</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Onglet Gestion des Horaires -->
        <div class="tab-pane fade" id="horaires" role="tabpanel">
            <!-- Sous-onglets pour les horaires -->
            <ul class="nav nav-pills mb-3" id="horairesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ponctuel-tab" data-bs-toggle="pill" data-bs-target="#ponctuel" type="button" role="tab">
                        Horaire Ponctuel
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="recurrent-tab" data-bs-toggle="pill" data-bs-target="#recurrent" type="button" role="tab">
                        Horaire Récurrent
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="hebdomadaire-tab" data-bs-toggle="pill" data-bs-target="#hebdomadaire" type="button" role="tab">
                        Horaires Hebdomadaires
                    </button>
                </li>
            </ul>

            <!-- Contenu des sous-onglets horaires -->
            <div class="tab-content" id="horairesTabsContent">
                <!-- Horaire Ponctuel -->
                <div class="tab-pane fade show active" id="ponctuel" role="tabpanel">
                    <form method="post" action="controllers/sallesController.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salle_id" class="form-label">Sélectionner la salle</label>
                                    <select class="form-select" id="salle_id" name="salle_id" required>
                                        <option value="">Sélectionner une salle</option>
                                        <?php foreach ($allsalles as $salle): ?>
                                            <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="heure_debut" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="heure_fin" class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="action" value="ajouterHoraire">
                        <button type="submit" class="btn btn-primary">Ajouter l'horaire</button>
                    </form>
                </div>

                <!-- Horaire Récurrent -->
                <div class="tab-pane fade" id="recurrent" role="tabpanel">
                    <form method="post" action="controllers/sallesController.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salle_id_rec" class="form-label">Sélectionner la salle</label>
                                    <select class="form-select" id="salle_id_rec" name="salle_id" required>
                                        <option value="">Sélectionner une salle</option>
                                        <?php foreach ($allsalles as $salle): ?>
                                            <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jour" class="form-label">Jour du mois</label>
                                    <select class="form-select" id="jour" name="jour" required>
                                        <?php for($i = 1; $i <= 31; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mois" class="form-label">Mois</label>
                                    <select class="form-select" id="mois" name="mois" required>
                                        <?php
                                        $mois = [
                                            1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
                                            4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                                            7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
                                            10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                        ];
                                        foreach($mois as $num => $nom): ?>
                                            <option value="<?= $num ?>"><?= $nom ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="heure_debut_rec" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" id="heure_debut_rec" name="heure_debut" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="heure_fin_rec" class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" id="heure_fin_rec" name="heure_fin" required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Cet horaire sera créé pour les 4 prochains mois, le même jour de chaque mois.
                        </div>

                        <input type="hidden" name="action" value="ajouterHoraireRecurent">
                        <button type="submit" class="btn btn-primary">Ajouter l'horaire récurrent</button>
                    </form>
                </div>

                <!-- Horaires Hebdomadaires -->
                <div class="tab-pane fade" id="hebdomadaire" role="tabpanel">
                    <form method="post" action="controllers/sallesController.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salle_id_hebdo" class="form-label">Sélectionner la salle</label>
                                    <select class="form-select" id="salle_id_hebdo" name="salle_id" required>
                                        <option value="">Sélectionner une salle</option>
                                        <?php foreach ($allsalles as $salle): ?>
                                            <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date_debut" class="form-label">Date de début de la semaine</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="heure_debut_hebdo" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" id="heure_debut_hebdo" name="heure_debut" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="heure_fin_hebdo" class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" id="heure_fin_hebdo" name="heure_fin" required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Des horaires seront créés automatiquement pour les 7 jours suivant la date de début, à la même heure chaque jour.
                        </div>

                        <input type="hidden" name="action" value="ajouterHorairesHebdomadaires">
                        <button type="submit" class="btn btn-primary">Ajouter les horaires hebdomadaires</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
