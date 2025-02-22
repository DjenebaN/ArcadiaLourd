package com.reservation.controller;

import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.control.PasswordField;
import com.reservation.model.Utilisateur;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import com.reservation.database.DatabaseConnection;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.application.Platform;

public class ProfilController {
    @FXML private TextField nomField;
    @FXML private TextField prenomField;
    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private PasswordField newPasswordField;
    @FXML private PasswordField confirmPasswordField;
    @FXML private Label messageLabel;
    @FXML private Label roleLabel;
    @FXML private Label userIdLabel;

    private Utilisateur utilisateurConnecte;

    @FXML
    public void initialize() {
        // Cette méthode sera appelée après le chargement du FXML
        clearFields();
    }

    public void setUtilisateur(Utilisateur utilisateur) {
        this.utilisateurConnecte = utilisateur;
        if (utilisateur != null) {
            // Utiliser Platform.runLater pour s'assurer que l'interface est prête
            Platform.runLater(() -> {
                chargerDonneesUtilisateur();
                messageLabel.setText("Bienvenue " + utilisateur.getPrenom() + " " + utilisateur.getNom() + " !");
                messageLabel.setStyle("-fx-text-fill: green;");
            });
        }
    }

    private void chargerDonneesUtilisateur() {
        if (utilisateurConnecte != null) {
            // Informations de base
            nomField.setText(utilisateurConnecte.getNom());
            prenomField.setText(utilisateurConnecte.getPrenom());
            emailField.setText(utilisateurConnecte.getEmail());
            
            // ID utilisateur
            userIdLabel.setText("ID Utilisateur: " + utilisateurConnecte.getId());
            
            // Rôle avec icône
            String roleIcon = "👑"; // Icône pour admin
            String role = "Administrateur";
            if (utilisateurConnecte.estClient()) {
                roleIcon = "👤";
                role = "Client";
            } else if (utilisateurConnecte.estGameMaster()) {
                roleIcon = "🎮";
                role = "Game Master";
            }
            roleLabel.setText(roleIcon + " " + role);
            
            // Masquer le mot de passe pour des raisons de sécurité
            passwordField.setText("********");
            
            // Activer les champs
            nomField.setDisable(false);
            prenomField.setDisable(false);
            emailField.setDisable(false);
        } else {
            // Si pas d'utilisateur, désactiver les champs
            nomField.setDisable(true);
            prenomField.setDisable(true);
            emailField.setDisable(true);
            passwordField.setDisable(true);
            messageLabel.setText("Erreur: Aucun utilisateur connecté");
            messageLabel.setStyle("-fx-text-fill: red;");
        }
    }

    @FXML
    private void sauvegarderProfil() {
        if (!validerChamps()) {
            return;
        }

        // Vérifier si un nouveau mot de passe est fourni
        String nouveauMotDePasse = newPasswordField.getText().trim();
        if (!nouveauMotDePasse.isEmpty()) {
            if (!nouveauMotDePasse.equals(confirmPasswordField.getText())) {
                afficherMessage("Les mots de passe ne correspondent pas", true);
                return;
            }
        }

        try {
            String sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?" +
                        (nouveauMotDePasse.isEmpty() ? "" : ", mot_de_passe = ?") +
                        " WHERE id = ?";

            try (Connection conn = DatabaseConnection.getConnection();
                 PreparedStatement pstmt = conn.prepareStatement(sql)) {
                
                pstmt.setString(1, nomField.getText().trim());
                pstmt.setString(2, prenomField.getText().trim());
                pstmt.setString(3, emailField.getText().trim());
                
                if (!nouveauMotDePasse.isEmpty()) {
                    pstmt.setString(4, nouveauMotDePasse);
                    pstmt.setInt(5, utilisateurConnecte.getId());
                } else {
                    pstmt.setInt(4, utilisateurConnecte.getId());
                }

                int lignesModifiees = pstmt.executeUpdate();
                if (lignesModifiees > 0) {
                    // Mise à jour réussie
                    utilisateurConnecte.setNom(nomField.getText().trim());
                    utilisateurConnecte.setPrenom(prenomField.getText().trim());
                    utilisateurConnecte.setEmail(emailField.getText().trim());
                    if (!nouveauMotDePasse.isEmpty()) {
                        utilisateurConnecte.setMotDePasse(nouveauMotDePasse);
                    }
                    afficherMessage("Profil mis à jour avec succès", false);
                    newPasswordField.clear();
                    confirmPasswordField.clear();
                }
            }
        } catch (SQLException e) {
            afficherMessage("Erreur lors de la mise à jour du profil: " + e.getMessage(), true);
        }
    }

    @FXML
    private void annulerModifications() {
        chargerDonneesUtilisateur();
        clearFields();
        messageLabel.setText("");
    }

    private void clearFields() {
        newPasswordField.clear();
        confirmPasswordField.clear();
    }

    private boolean validerChamps() {
        if (nomField.getText().trim().isEmpty() || 
            prenomField.getText().trim().isEmpty() || 
            emailField.getText().trim().isEmpty()) {
            afficherMessage("Tous les champs obligatoires doivent être remplis", true);
            return false;
        }
        return true;
    }

    private void afficherMessage(String message, boolean erreur) {
        messageLabel.setText(message);
        messageLabel.setStyle(erreur ? "-fx-text-fill: red;" : "-fx-text-fill: green;");
    }
}
