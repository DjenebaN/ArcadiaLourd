<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $to = "jameslapada7@gmail.com"; // Remplacez par votre email
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    $email_content = "Nom: " . $name . "\n";
    $email_content .= "Email: " . $email . "\n\n";
    $email_content .= "Message:\n" . $message;
    
    if(mail($to, $subject, $email_content, $headers)) {
        $success_message = "Votre message a été envoyé avec succès !";
    } else {
        $error_message = "Une erreur s'est produite lors de l'envoi du message.";
    }
}
?>

<div class="container py-5">
    <h1 class="page-title mb-5">Contactez-nous</h1>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="contact-card p-4 mb-5">
                <p class="project-description text-center mb-4">
                    Le Château De Durfort, désireux de mettre en valeur son patrimoine unique, a choisi d'organiser des jeux d'escape game pour offrir une expérience immersive et ludique à ses visiteurs. Notre mission est de contribuer au succès de cette initiative en rendant l'accès aux jeux plus fluide et en valorisant cette belle structure historique.
                </p>

                <div class="row contact-info g-4 mb-4">
                    <div class="col-md-4">
                        <div class="info-item text-center">
                            <i class="fas fa-envelope mb-3"></i>
                            <h3>Email</h3>
                            <p>contact@notreprojet.com</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item text-center">
                            <i class="fas fa-phone mb-3"></i>
                            <h3>Téléphone</h3>
                            <p>+33 1 23 45 67 89</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item text-center">
                            <i class="fas fa-map-marker-alt mb-3"></i>
                            <h3>Adresse</h3>
                            <p>Château de Durfort<br>11390 Durfort</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-card p-4">
                <h2 class="text-center mb-4">Envoyez-nous un message</h2>
                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if(isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="contact-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom" required>
                                <label for="name">Votre nom</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                                <label for="email">Votre email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Sujet" required>
                                <label for="subject">Sujet</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="message" name="message" placeholder="Votre message" style="height: 150px" required></textarea>
                                <label for="message">Votre message</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Envoyer le message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="map-container rounded overflow-hidden shadow-sm">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d23337.206387264305!2d2.5106307743164127!3d43.01721300000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12b0290aef75ddb9%3A0x1f11ec066e061959!2sCh%C3%A2teau%20de%20Durfort!5e0!3m2!1sfr!2sfr!4v1733602233023!5m2!1sfr!2sfr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<style>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

.contact-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}

.contact-section h1 {
    font-size: 2.5rem;
    color: #222;
    margin-bottom: 20px;
}

.project-description {
    font-size: 1.2rem;
    color: #555;
    margin-bottom: 30px;
    line-height: 1.6;
}

.contact-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 30px;
}

.info-item {
    margin: 10px 20px;
    text-align: left;
}

.info-item h3 {
    font-size: 1.2rem;
    color: #444;
    margin-bottom: 10px;
}

.info-item p {
    font-size: 1rem;
    color: #666;
    margin: 0;
}

.map-container {
    margin-top: 20px;
}

.map-container iframe {
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
</style>