<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content animate-fade-in">
        <h1 class="hero-title">Osez l'Aventure</h1>
        <p class="hero-subtitle">Plongez dans des univers mystérieux et relevez des défis extraordinaires</p>
        <a href="index.php?page=reservation" class="btn btn-primary">Commencer l'Aventure</a>
    </div>
</div>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-users feature-icon"></i>
                    <h3>Expérience Immersive</h3>
                    <p>Formez votre équipe de 2 à 6 joueurs et plongez dans une aventure inoubliable</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-clock feature-icon"></i>
                    <h3>Course Contre la Montre</h3>
                    <p>60 minutes pour percer les mystères et vous échapper. Chaque seconde compte !</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-brain feature-icon"></i>
                    <h3>Énigmes Captivantes</h3>
                    <p>Des puzzles ingénieux qui mettront à l'épreuve votre esprit d'équipe et votre créativité</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rooms Preview Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 animate-fade-in">Nos Univers d'Évasion</h2>
        <div class="row g-4">
            <?php
            include('controllers/affichage/afficherEscapegame.php');
            $salles = $escapegame->getAllSalles();
            foreach ($salles as $salle):
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="room-card">
                    <?php if (empty($salle['image'])): ?>
                        <img src="image/default_salle.jpeg" class="room-image" alt="<?= htmlspecialchars($salle['nom']) ?>">
                    <?php else: ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($salle['image']) ?>" class="room-image" alt="<?= htmlspecialchars($salle['nom']) ?>">
                    <?php endif; ?>
                    <div class="room-content">
                        <h3 class="room-title"><?= htmlspecialchars($salle['nom']) ?></h3>
                        <p class="room-description"><?= !empty($salle['description']) ? htmlspecialchars($salle['description']) : "Plongez dans une aventure palpitante et immersive. Résolvez des énigmes complexes, découvrez des passages secrets et travaillez en équipe pour accomplir votre mission dans le temps imparti. Une expérience unique qui mettra à l'épreuve votre logique et votre esprit d'équipe." ?></p>
                        <div class="room-details">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary"><?= $salle['nb_joueurs_min'] ?>-<?= $salle['nb_joueurs_max'] ?> joueurs</span>
                                <span class="badge bg-primary"><?= $salle['duree'] ?? 60 ?> minutes</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="difficulty"><i class="fas fa-star-half-alt"></i> Difficulté moyenne</span>
                                <span class="room-price"><?= $salle['prix'] ?>€</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="index.php?page=salle-details&id=<?= $salle['id'] ?>&source=accueil" class="btn btn-outline-primary flex-grow-1">Plus d'informations</a>
                            <a href="index.php?page=horairesSalle&id=<?= $salle['id'] ?>&source=accueil" class="btn btn-primary flex-grow-1">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ce que disent nos joueurs</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="mb-3">"Une expérience incroyable ! Les énigmes sont bien pensées et l'ambiance est parfaite. On reviendra !"</p>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <h6 class="mb-0">Sophie L.</h6>
                                <small class="text-muted">Joueuse passionnée</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="mb-3">"Parfait pour une sortie entre amis ! Le game master était super et nous a bien guidés quand nécessaire."</p>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <h6 class="mb-0">Thomas M.</h6>
                                <small class="text-muted">Amateur d'énigmes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="mb-3">"Les décors sont magnifiques et l'immersion est totale. Une excellente activité en famille !"</p>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <h6 class="mb-0">Marie P.</h6>
                                <small class="text-muted">Mère de famille</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à relever le défi ?</h2>
        <p class="lead mb-4">Réservez maintenant et vivez une expérience inoubliable</p>
        <a href="index.php?page=reservation" class="btn btn-light btn-lg">Réserver une salle</a>
    </div>
</section>

<!-- Add custom CSS -->
<style>
.hero-image {
    position: relative;
    z-index: 1;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background-color: white !important;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-outline-primary {
    color: #007bff;
    border-color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
