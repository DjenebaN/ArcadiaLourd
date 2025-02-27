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
import com.reservation.model.Utilisateur;
import java.sql.SQLException;

public class LoginController {

    @FXML
    private TextField usernameField; 

    @FXML
    private PasswordField passwordField; 
    
    @FXML
    private void handleLogin(ActionEvent event) {
        String email = usernameField.getText().trim();
        String password = passwordField.getText().trim();

        if (email.isEmpty() || password.isEmpty()) {
            showAlert("Champs requis", "Veuillez remplir tous les champs.", Alert.AlertType.WARNING);
            return;
        }

        try {
            Utilisateur utilisateur = Utilisateur.connexion(email, password);
            
            if (utilisateur != null) {
                if (utilisateur.estAdmin()) {
                    // L'utilisateur est un admin, on ouvre le dashboard
                    openDashboard(utilisateur);
                } else {
                    // L'utilisateur n'est pas un admin
                    showAlert("Accès refusé", "Seuls les administrateurs peuvent accéder à cette interface.", Alert.AlertType.ERROR);
                }
            } else {
                // Identifiants incorrects
                showAlert("Échec de connexion", "Email ou mot de passe incorrect.", Alert.AlertType.ERROR);
            }
        } catch (SQLException e) {
            e.printStackTrace();
            showAlert("Erreur de connexion", "Une erreur est survenue lors de la tentative de connexion : " + e.getMessage(), Alert.AlertType.ERROR);
        }
    }

    // Charge la scène d'accueil après une connexion réussie
    private void openDashboard(Utilisateur utilisateur) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/admin_dashboard.fxml"));
            Parent root = loader.load();
            
            // Récupérer le contrôleur du dashboard et lui passer l'utilisateur
            AdminDashboardController dashboardController = loader.getController();
            dashboardController.setUtilisateur(utilisateur);
    
            Stage stage = (Stage) usernameField.getScene().getWindow();
            Scene scene = new Scene(root);
            stage.setScene(scene);
            stage.setTitle("Administration - Escape Game");
            stage.show();
        } catch (Exception e) {
            e.printStackTrace();
            showAlert("Erreur", "Une erreur est survenue lors du chargement du tableau de bord : " + e.getMessage(), Alert.AlertType.ERROR);
        }
    }

    // Méthode pour afficher une alerte
    private void showAlert(String title, String message, Alert.AlertType alertType) {
        Alert alert = new Alert(alertType);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}
