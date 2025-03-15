package com.reservation.controller;

import com.reservation.model.Utilisateur;

import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.stage.Stage;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.beans.property.SimpleStringProperty;

import java.io.IOException;
import java.net.URL;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ResourceBundle;

import com.reservation.database.DatabaseConnection;
import com.reservation.database.UtilisateurD;
import javafx.application.Platform;

public class UtilisateurDashboardController implements Initializable {

    @FXML private TableView<Utilisateur> utilisateurTable;
    @FXML private TableColumn<Utilisateur, String> nomColumn;
    @FXML private TableColumn<Utilisateur, String> prenomColumn;
    @FXML private TableColumn<Utilisateur, String> emailColumn;
    @FXML private TableColumn<Utilisateur, String> typeColumn;
    @FXML private TextField searchField;  
    @FXML private Button cancelButton;
    @FXML private Button backButton;
    @FXML private TabPane mainTabPane;

    private UtilisateurD utilisateurD; 
    private ObservableList<Utilisateur> allUtilisateurs;  
    private Utilisateur utilisateur;

    public void setUtilisateur(Utilisateur utilisateur) {
        this.utilisateur = utilisateur;
        
        Platform.runLater(() -> {
            try {
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/profil.fxml"));
                Node profilContent = loader.load();
                
                ProfilController profilController = loader.getController();
                profilController.setUtilisateur(utilisateur);
                
                for (Tab tab : mainTabPane.getTabs()) {
                    if (tab.getText().equals("Profil")) {
                        tab.setContent(profilContent);
                        break;
                    }
                }
            } catch (IOException e) {
                e.printStackTrace();
                showError("Erreur lors du chargement du profil", e.getMessage());
            }
        });
    }

    private void showError(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        utilisateurD = new UtilisateurD();

        // Configuration des colonnes
        nomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNom()));
        prenomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getPrenom()));
        emailColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getEmail()));
        typeColumn.setCellValueFactory(cellData -> {
            int type = cellData.getValue().getTypeUtilisateur();
            String typeText = switch (type) {
                case 1 -> "gamemaster";
                case 2 -> "utilisateur";
                case 3 -> "admin";
                default -> "inconnu";
            };
            return new SimpleStringProperty(typeText);
        });

        loadTestData(); 

        setupButtons();
        setupTableSelection();
    }

    private void loadTestData() {
        ObservableList<Utilisateur> utilisateurs = utilisateurD.getAllUtilisateurs();
        utilisateurTable.setItems(utilisateurs); 
        allUtilisateurs = utilisateurs; 
    }

    private void setupButtons() {
        cancelButton.setDisable(true);
        cancelButton.setOnAction(e -> cancelUtilisateur());
        
        // Configuration du bouton retour
        backButton.setOnAction(e -> handleBack());
    }

    @FXML
    private void handleBack() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/accueil.fxml"));
            Parent root = loader.load();
            
            // Obtenir la scène actuelle
            Scene currentScene = backButton.getScene();
            // Récupérer le stage (fenêtre)
            Stage stage = (Stage) currentScene.getWindow();
            // Changer la scène
            stage.setScene(new Scene(root));
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de retourner à la page précédente.");
        }
    }

    private void setupTableSelection() {
        utilisateurTable.getSelectionModel().selectedItemProperty().addListener(
            (obs, oldSelection, newSelection) -> {
                boolean hasSelection = newSelection != null;
                cancelButton.setDisable(!hasSelection);
            }
        );
    }

    private void cancelUtilisateur() {
        Utilisateur selected = utilisateurTable.getSelectionModel().getSelectedItem();
        if (selected != null) {
            try (Connection connection = DatabaseConnection.getConnection()) {
                String deleteUtilisateurQuery = "DELETE FROM utilisateurs WHERE id = ?";
                try (PreparedStatement stmt = connection.prepareStatement(deleteUtilisateurQuery)) {
                    stmt.setInt(1, selected.getId());
                    int rowsDeleted = stmt.executeUpdate();

                    if (rowsDeleted > 0) {
                        allUtilisateurs.remove(selected);
                        utilisateurTable.refresh();
                        redirectToAccueil();
                    }
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    private void redirectToAccueil() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/accueil.fxml"));
            Parent root = loader.load();

            Stage stage = (Stage) utilisateurTable.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
