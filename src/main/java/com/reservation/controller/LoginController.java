package com.reservation.controller;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.TextField;
import javafx.scene.control.PasswordField;
import javafx.scene.control.Alert;
import javafx.stage.Stage;
import javafx.scene.Scene;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;

public class LoginController {

    @FXML
    private TextField usernameField; 

    @FXML
    private PasswordField passwordField; 
    
    @FXML
    private void handleLogin(ActionEvent event) {
        String username = usernameField.getText();
        String password = passwordField.getText();

        // Si l'authentification réussit, ouvrir l'écran d'accueil
        if (authenticate(username, password)) {
            openDashboard();
        } else {
            showAlert("Identifiants incorrects", "Veuillez réessayer.");
        }
    }

    // Simule une authentification de l'utilisateur
    private boolean authenticate(String username, String password) {
        // Ici tu peux remplacer la vérification statique par une vérification dans une base de données
        return "admin@gmail.com".equals(username) && "admin".equals(password);
    }

    // Charge la scène d'accueil après une connexion réussie
    private void openDashboard() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/accueil.fxml"));
            Parent root = loader.load();
    
            Stage stage = (Stage) usernameField.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Tableau de bord - Réservations");
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();  // Affiche l'exception dans la console
            showAlert("Erreur", "Une erreur est survenue lors du chargement de l'accueil : " + e.getMessage());
        }
    }
    

    // Méthode pour afficher une alerte en cas d'échec de la connexion
    private void showAlert(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}
