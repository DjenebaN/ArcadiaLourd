<?php
// Test d'affichage des données
echo "<pre style='color: white; background: black; padding: 20px; margin: 20px;'>";
var_dump($salles);
echo "</pre>";

// Initialisation des variables
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $nb_personnes = $_POST['nb_personnes'] ?? '';
    $salle = $_POST['salle'] ?? '';

    // Validation basique
    if (empty($nom) || empty($email) || empty($telephone) || empty($date) || empty($heure) || empty($nb_personnes) || empty($salle)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        // Ici, vous pouvez ajouter le code pour enregistrer la réservation dans la base de données
        $success_message = "Votre réservation a été enregistrée avec succès ! Nous vous contacterons bientôt pour la confirmation.";
    }
}

// Heures disponibles pour les réservations
$heures_disponibles = [
    "10:00", "11:30", "14:00", "15:30", "17:00", "18:30"
];

// Salles disponibles
$salles = [
    [
        "id" => "salle1",
        "nom" => "La Crypte Mystérieuse",
        "description" => "Plongez dans les mystères des souterrains du château",
        "min_joueurs" => 2,
        "max_joueurs" => 6,
        "difficulte" => "Moyen",
        "duree" => "60 minutes",
        "image" => "images/crypte.jpg"
    ],
    [
        "id" => "salle2",
        "nom" => "Le Bureau du Comte",
        "description" => "Découvrez les secrets bien gardés du Comte de Durfort",
        "min_joueurs" => 3,
        "max_joueurs" => 8,
        "difficulte" => "Difficile",
        "duree" => "75 minutes",
        "image" => "images/bureau.jpg"
    ],
    [
        "id" => "salle3",
        "nom" => "La Tour de l'Alchimiste",
        "description" => "Résolvez les énigmes de l'ancien alchimiste du château",
        "min_joueurs" => 2,
        "max_joueurs" => 5,
        "difficulte" => "Expert",
        "duree" => "90 minutes",
        "image" => "images/tour.jpg"
    ]
];
?>

<div class="reservation-section">
    <div class="container">
        <h1 class="reservation-title">Réservez votre escape game</h1>

        <?php if($success_message): ?>
            <div class="alert alert-success fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if($error_message): ?>
            <div class="alert alert-danger fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Salles disponibles -->
            <div class="col-lg-7 mb-4">
                <div class="rooms-selection">
                    <h2 class="section-title text-center mb-4">Choisissez votre salle</h2>
                <div class="row g-4">
                    <?php foreach($salles as $salle): ?>
                        <div class="col-md-6">
                            <div class="room-card">
                                <div class="room-card-inner">
                                    <!-- Front of the card -->
                                    <div class="room-card-front">
                                        <div class="room-image">
                                            <img src="<?php echo $salle['image']; ?>" alt="<?php echo $salle['nom']; ?>">
                                        </div>
                                        <div class="room-difficulty">
                                            <i class="fas fa-star"></i>
                                            <?php echo $salle['difficulte']; ?>
                                        </div>
                                        <div class="room-content-front">
                                            <h3 class="room-title"><?php echo $salle['nom']; ?></h3>
                                            <p class="room-description"><?php echo $salle['description']; ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Back of the card -->
                                    <div class="room-card-back">
                                        <h3 class="room-title"><?php echo $salle['nom']; ?></h3>
                                        <p class="room-description"><?php echo $salle['description']; ?></p>
                                        <div class="room-details">
                                            <div class="room-detail">
                                                <i class="fas fa-users"></i>
                                                <span><?php echo $salle['min_joueurs']; ?>-<?php echo $salle['max_joueurs']; ?> joueurs</span>
                                            </div>
                                            <div class="room-detail">
                                                <i class="fas fa-clock"></i>
                                                <span><?php echo $salle['duree']; ?></span>
                                            </div>
                                            <div class="room-detail">
                                                <i class="fas fa-brain"></i>
                                                <span>Niveau <?php echo $salle['difficulte']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Formulaire de réservation -->
        <div class="col-lg-5">
            <div class="reservation-form">
                <h2 class="section-title text-center mb-4">Réserver votre créneau</h2>
                <form method="POST" class="needs-validation" novalidate>
                    <!-- Sélection de la salle -->
                    <div class="form-floating mb-4">
                        <select class="form-select" id="salle" name="salle" required>
                            <option value="">Choisir une salle...</option>
                            <?php foreach($salles as $salle): ?>
                                <option value="<?php echo $salle['id']; ?>"><?php echo $salle['nom']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="salle">Sélectionnez une salle</label>
                    </div>

                    <!-- Description de la salle -->
                    <div class="mb-4">
                        <?php foreach($salles as $salle): ?>
                            <div class="p-4 mb-4" style="background: rgba(0, 0, 0, 0.5); border-radius: 10px; border: 1px solid var(--accent-color);">
                                <h3 style="color: var(--accent-color); margin-bottom: 15px;"><?php echo $salle['nom']; ?></h3>
                                <p style="color: #fff; margin-bottom: 20px; line-height: 1.6;"><?php echo $salle['description']; ?></p>
                                <div style="color: var(--accent-color); display: flex; justify-content: space-between;">
                                    <span><i class="fas fa-users"></i> <?php echo $salle['min_joueurs']; ?>-<?php echo $salle['max_joueurs']; ?> joueurs</span>
                                    <span><i class="fas fa-clock"></i> <?php echo $salle['duree']; ?></span>
                                    <span><i class="fas fa-brain"></i> Niveau <?php echo $salle['difficulte']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Résumé de la salle sélectionnée -->
                    <div id="room-details" class="room-details-card mb-4" style="display: none;">
                        <div class="room-image"></div>
                        <div class="room-info">
                            <h3 class="room-name" style="color: var(--accent-color); font-size: 1.5rem; margin-bottom: 1rem;"></h3>
                            <p class="room-description" style="color: #fff; margin-bottom: 1.5rem; line-height: 1.6;"></p>
                            <div class="room-specs" style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                <div class="spec" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-users" style="color: var(--accent-color);"></i>
                                    <span class="players" style="color: #fff;"></span>
                                </div>
                                <div class="spec" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-clock" style="color: var(--accent-color);"></i>
                                    <span class="duration" style="color: #fff;"></span>
                                </div>
                                <div class="spec" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-brain" style="color: var(--accent-color);"></i>
                                    <span class="difficulty" style="color: #fff;"></span>
                                </div>
                            </div>

                            <!-- Créneaux horaires -->
                            <div class="time-slots-section mt-4">
                                <h4 class="time-slots-title">Créneaux disponibles</h4>
                                <div class="time-slots">
                                    <?php foreach($heures_disponibles as $heure): ?>
                                        <div class="time-slot" onclick="selectTimeSlot(this, '<?php echo $heure; ?>')">
                                            <i class="far fa-clock"></i>
                                            <span><?php echo $heure; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" id="heure" name="heure" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                        <label for="nom">Votre nom</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                        <label for="email">Votre email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Votre téléphone" required>
                        <label for="telephone">Votre téléphone</label>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Choisissez votre créneau</label>
                            <div class="time-slots">
                                <?php foreach($heures_disponibles as $heure): ?>
                                <div class="time-slot" onclick="selectTimeSlot(this, '<?php echo $heure; ?>')">
                                    <i class="far fa-clock mb-2"></i>
                                    <div><?php echo $heure; ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" id="heure" name="heure" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="nb_personnes" name="nb_personnes" required>
                                    <option value="">Choisir...</option>
                                    <?php for($i = 2; $i <= 8; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> personnes</option>
                                    <?php endfor; ?>
                                </select>
                                <label for="nb_personnes">Nombre de joueurs</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="salle" name="salle" required>
                                    <option value="">Choisir...</option>
                                    <?php foreach($salles as $salle): ?>
                                        <option value="<?php echo $salle['id']; ?>"><?php echo $salle['nom']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="salle">Salle</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-reserve">
                        <i class="fas fa-calendar-check me-2"></i>
                        Réserver maintenant
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDescription(roomId) {
    console.log('Affichage de la description pour:', roomId);
    
    // Masquer toutes les descriptions
    document.querySelectorAll('.description').forEach(desc => {
        desc.style.display = 'none';
    });

    const descBox = document.getElementById('description-box');
    const selectedDesc = document.getElementById('desc-' + roomId);

    console.log('Description box:', descBox);
    console.log('Selected description:', selectedDesc);

    if (roomId && selectedDesc) {
        // Afficher le conteneur principal
        descBox.style.display = 'block';
        descBox.style.opacity = '0';

        // Afficher la description sélectionnée
        selectedDesc.style.display = 'block';

        // Animation
        requestAnimationFrame(() => {
            descBox.style.opacity = '1';
        });
    } else {
        // Masquer le conteneur
        descBox.style.opacity = '0';
        setTimeout(() => {
            descBox.style.display = 'none';
        }, 300);
    }
}


// Sélection du créneau horaire
function selectTimeSlot(element, time) {
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.classList.remove('selected');
    });
    element.classList.add('selected');
    document.getElementById('heure').value = time;
}

// Validation du formulaire
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();


</script>
