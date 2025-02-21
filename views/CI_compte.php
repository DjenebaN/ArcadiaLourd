<!-- Inscription et connection-->
<!--
<center>
    <form method="POST" action="controllers/utilisateurController.php">
        <h1>Se connecter</h1>
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" name="mot_de_passe" required>
        <input type="hidden" name="action" value="connexion">
        <button type="submit" name="connexion">Se connecter</button>
    </form>

    <form action="controllers/utilisateurController.php" method="post">
        <h1>S'inscrire</h1>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required><br>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br>
        <label for="telephone">Téléphone :</label>
        <input type="tel" name="telephone" id="telephone" required><br>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" id="mot_de_passe" required><br>
        <input type="hidden" name="action" value="ajouter">
        <input type="submit" value="S'inscrire">
    </form>
</center>
-->

<div class="container mt-5">
    <div class="row justify-content-center">
        <?php if (isset($_GET['success']) && $_GET['success'] == 'inscription'): ?>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Félicitations!</strong> Votre inscription a été effectuée avec succès. Vous pouvez maintenant vous connecter.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Se connecter</h2>
                    <form method="post" action="controllers/utilisateurController.php">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="login-email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="login-password" name="mot_de_passe" required>
                        </div>
                        <input type="hidden" name="action" value="connexion">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" name="connexion">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulaire d'inscription -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">S'inscrire</h2>
                    <form action="controllers/utilisateurController.php" method="post">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" id="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="prenom" id="prenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" id="telephone" required>
                        </div>
                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="mot_de_passe" id="mot_de_passe" required>
                        </div>
                        <input type="hidden" name="action" value="ajouter">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>