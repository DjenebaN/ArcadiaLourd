<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=connectioninscription');
    exit();
}

$user = $_SESSION['utilisateur'];
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Modifier mes informations</h3>
                </div>
                <div class="card-body">
                    <form action="controllers/UtilisateurController.php" method="post">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?= htmlspecialchars($user['nom']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                       value="<?= htmlspecialchars($user['prenom']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" 
                                   value="<?= htmlspecialchars($user['telephone']) ?>" 
                                   pattern="[0-9]{10}" title="Veuillez entrer un numéro de téléphone valide (10 chiffres)">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?page=compte" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php
                switch($_GET['error']) {
                    case 'email':
                        echo "Cette adresse email est déjà utilisée.";
                        break;
                    case 'update':
                        echo "Une erreur est survenue lors de la mise à jour de vos informations.";
                        break;
                    default:
                        echo "Une erreur est survenue.";
                }
                ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] == 'compteModifier'): ?>
            <div class="alert alert-success mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                Vos informations ont été mises à jour avec succès !
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>