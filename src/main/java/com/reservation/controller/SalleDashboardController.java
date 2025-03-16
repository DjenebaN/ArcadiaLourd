package com.reservation.controller;

import com.reservation.model.Salle;

import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
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
import com.reservation.database.SalleD;
import javafx.application.Platform;

public class SalleDashboardController implements Initializable {

    @FXML private TableView<Salle> salleTable;
    @FXML private TableColumn<Salle, String> nomColumn;
    @FXML private TableColumn<Salle, String> descColumn;
    @FXML private TableColumn<Salle, String> dureeColumn;
    @FXML private TableColumn<Salle, String> joueurColumn;
    @FXML private TableColumn<Salle, String> prixColumn;
    @FXML private TextField nomField, dureeField, nbJoueursMinField, nbJoueursMaxField, prixField;
    @FXML private TextArea descriptionField;
    @FXML private Button cancelButton;
    @FXML private Button backButton;
    @FXML private TabPane mainTabPane;

    private SalleD salleD; 
    private ObservableList<Salle> allSalles;  
    private Salle salle;

    private void showError(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        if (url != null && url.toString().endsWith("addSalle.fxml")) {
            return;
        }

        salleD = new SalleD();
        
        nomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNom()));
        descColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getDesc()));
        dureeColumn.setCellValueFactory(cellData -> new SimpleStringProperty(String.valueOf(cellData.getValue().getDuree())));
        joueurColumn.setCellValueFactory(cellData -> new SimpleStringProperty(String.valueOf(cellData.getValue().getJoueursMax())));
        prixColumn.setCellValueFactory(cellData -> new SimpleStringProperty(String.valueOf(cellData.getValue().getPrix())));

        loadTestData();
        setupButtons();
        setupTableSelection();
    }


    private void loadTestData() {
        ObservableList<Salle> salles = salleD.getAllSalles();
        salleTable.setItems(salles);
        allSalles = salles;
    }

    private void setupButtons() {
        cancelButton.setDisable(true);
        cancelButton.setOnAction(e -> cancelSalle());

        backButton.setOnAction(e -> handleBack());
    }

    @FXML
    private void handleBack() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/accueil.fxml"));
            Parent root = loader.load();
            
            Scene currentScene = backButton.getScene();
            Stage stage = (Stage) currentScene.getWindow();
            stage.setScene(new Scene(root));
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de retourner à la page précédente.");
        }
    }

    @FXML
    private void addSalle(ActionEvent event) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/addSalle.fxml"));
            if (loader.getLocation() == null) {
                System.out.println("Le fichier FXML n'a pas été trouvé à l'emplacement spécifié.");
            }
            Parent sallesPage = loader.load();
    
            // Obtenir la scène actuelle
            Scene currentScene = ((Node) event.getSource()).getScene();
            // Récupérer le stage (fenêtre)
            Stage stage = (Stage) currentScene.getWindow();
            // Changer la scène
            stage.setScene(new Scene(sallesPage));
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de charger la page salles.");
        }
    }

    @FXML
private void submitSalle(ActionEvent event) {
    String nom = nomField.getText().trim();
    String description = descriptionField.getText().trim();
    int duree, nbJoueursMin, nbJoueursMax, prix;

    if (nom.isEmpty() || dureeField.getText().isEmpty() || nbJoueursMinField.getText().isEmpty() ||
        nbJoueursMaxField.getText().isEmpty() || prixField.getText().isEmpty()) {
        showError("Erreur", "Tous les champs sont obligatoires !");
        return;
    }

    try {
        duree = Integer.parseInt(dureeField.getText());
        nbJoueursMin = Integer.parseInt(nbJoueursMinField.getText());
        nbJoueursMax = Integer.parseInt(nbJoueursMaxField.getText());
        prix = Integer.parseInt(prixField.getText());

        if (duree <= 0 || nbJoueursMin <= 0 || nbJoueursMax <= 0 || prix <= 0) {
            showError("Erreur", "Les valeurs numériques doivent être positives !");
            return;
        }

        if (nbJoueursMin > nbJoueursMax) {
            showError("Erreur", "Le nombre minimum de joueurs doit être inférieur ou égal au maximum !");
            return;
        }

    } catch (NumberFormatException e) {
        showError("Erreur", "Veuillez entrer des nombres valides !");
        return;
    }

    Salle nouvelleSalle = new Salle(nom, description, duree, nbJoueursMin, nbJoueursMax, prix);

    if (SalleD.ajouterSalle(nouvelleSalle)) {
        showInfo("Succès", "Salle ajoutée avec succès !");
        
        allSalles.add(nouvelleSalle);
        salleTable.refresh();
        
        clearForm();

        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/acceuil.fxml"));
            Parent accueilPage = loader.load();
            
            Scene currentScene = ((Node) event.getSource()).getScene();
            Stage stage = (Stage) currentScene.getWindow();
            
            stage.setScene(new Scene(accueilPage));
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de charger la page d'accueil.");
        }

    } else {
        showErrorSalle("Erreur", "Échec de l'ajout de la salle !");
    }
}


    @FXML
    private void cancelSalleForm() {
        clearForm();
    }

    private void clearForm() {
        nomField.clear();
        descriptionField.clear();
        dureeField.clear();
        nbJoueursMinField.clear();
        nbJoueursMaxField.clear();
        prixField.clear();
    }

    private void showErrorSalle(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    private void showInfo(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    private void setupTableSelection() {
        salleTable.getSelectionModel().selectedItemProperty().addListener(
            (obs, oldSelection, newSelection) -> {
                boolean hasSelection = newSelection != null;
                cancelButton.setDisable(!hasSelection);
            }
        );
    }

    private void cancelSalle() {
        Salle selected = salleTable.getSelectionModel().getSelectedItem();
        if (selected != null) {
            try (Connection connection = DatabaseConnection.getConnection()) {
                String deleteSalleQuery = "DELETE FROM salles WHERE id = ?";
                try (PreparedStatement stmt = connection.prepareStatement(deleteSalleQuery)) {
                    stmt.setInt(1, selected.getId());
                    int rowsDeleted = stmt.executeUpdate();

                    if (rowsDeleted > 0) {
                        allSalles.remove(selected);
                        salleTable.refresh();
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

            Stage stage = (Stage) salleTable.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
