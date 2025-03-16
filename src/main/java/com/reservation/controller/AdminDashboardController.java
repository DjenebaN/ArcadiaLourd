package com.reservation.controller;

import com.reservation.model.Reservation;
import com.reservation.model.Utilisateur;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.stage.Stage;
import javafx.animation.ScaleTransition;
import javafx.util.Duration;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.beans.property.SimpleStringProperty;

import java.io.IOException;
import java.net.URL;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ResourceBundle;
import java.util.function.Predicate;

import com.reservation.database.DatabaseConnection;
import com.reservation.database.ReservationD;
import javafx.application.Platform;

public class AdminDashboardController implements Initializable {

    @FXML private TableView<Reservation> reservationTable;
    @FXML private TableColumn<Reservation, String> clientColumn;
    @FXML private TableColumn<Reservation, String> roomColumn;
    @FXML private TableColumn<Reservation, String> heureDebutColumn;
    @FXML private TableColumn<Reservation, String> heureFinColumn;
    @FXML private TableColumn<Reservation, String> statusColumn;
    @FXML private Label todayReservationsCount;
    @FXML private Label pendingReservationsCount;
    @FXML private Label confirmedReservationsCount;
    @FXML private TextField searchField;
    @FXML private Button confirmButton;
    @FXML private Button cancelButton;
    @FXML private TabPane mainTabPane;

    private ReservationD reservationD;
    private ObservableList<Reservation> allReservations;
    private FilteredList<Reservation> filteredReservations;
    private Utilisateur utilisateur;

    public void setUtilisateur(Utilisateur utilisateur) {
        this.utilisateur = utilisateur;
        
        // Attendre que le TabPane soit complètement chargé
        Platform.runLater(() -> {
            try {
                // Charger la vue du profil
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/profil.fxml"));
                Node profilContent = loader.load();
                
                // Récupérer le contrôleur et lui passer l'utilisateur
                ProfilController profilController = loader.getController();
                profilController.setUtilisateur(utilisateur);
                
                // Trouver l'onglet Profil et mettre à jour son contenu
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

    @FXML
    private void handleShowUsers(ActionEvent event) {
        try {
            // Chargement de l'écran utilisateurs
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/utilisateurs.fxml"));
            if (loader.getLocation() == null) {
                System.out.println("Le fichier FXML n'a pas été trouvé à l'emplacement spécifié.");
            }
            Parent usersPage = loader.load();
    
            // Obtenir la scène actuelle
            Scene currentScene = ((Node) event.getSource()).getScene();
            // Récupérer le stage (fenêtre)
            Stage stage = (Stage) currentScene.getWindow();
            // Changer la scène
            stage.setScene(new Scene(usersPage));
        } catch (IOException e) {
            e.printStackTrace();
            showError("Erreur de navigation", "Impossible de charger la page utilisateurs.");
        }
    }   
    
    @FXML
    private void handleShowSalles(ActionEvent event) {
        try {
            // Chargement de l'écran utilisateurs
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/salles.fxml"));
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

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        reservationD = new ReservationD();
        // Configuration des colonnes
        clientColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomUtilisateur()));
        roomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomSalle()));
        statusColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomStatus()));
        
        // Configuration des colonnes d'horaires avec date et heure
        heureDebutColumn.setCellValueFactory(cellData -> {
            LocalDateTime heureDebut = cellData.getValue().getHeureDebut();
            return new SimpleStringProperty(heureDebut != null ? 
                heureDebut.format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm")) : "");
        });

        heureFinColumn.setCellValueFactory(cellData -> {
            LocalDateTime heureFin = cellData.getValue().getHeureFin();
            return new SimpleStringProperty(heureFin != null ? 
                heureFin.format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm")) : "");
        });

        loadTestData();

        setupSearch();

        setupButtons();

        setupTableSelection();

        updateCounters();
    }

    private void loadTestData() {
        allReservations = reservationD.getAllReservations();
        filteredReservations = new FilteredList<>(allReservations);
        reservationTable.setItems(filteredReservations);
    }

    private void setupSearch() {
        searchField.textProperty().addListener((observable, oldValue, newValue) -> {
            filteredReservations.setPredicate(reservation -> {
                if (newValue == null || newValue.isEmpty()) {
                    return true;
                }

                String lowerCaseFilter = newValue.toLowerCase();
                String heureDebutStr = "";
                String heureFinStr = "";

                if (reservation.getHeureDebut() != null) {
                    heureDebutStr = reservation.getHeureDebut()
                        .format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm"))
                        .toLowerCase();
                }

                if (reservation.getHeureFin() != null) {
                    heureFinStr = reservation.getHeureFin()
                        .format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm"))
                        .toLowerCase();
                }

                return reservation.getNomUtilisateur().toLowerCase().contains(lowerCaseFilter) ||
                       reservation.getNomSalle().toLowerCase().contains(lowerCaseFilter) ||
                       heureDebutStr.contains(lowerCaseFilter) ||
                       heureFinStr.contains(lowerCaseFilter);
            });
            updateCounters();
        });
    }

    private void setupButtons() {
        confirmButton.setDisable(true);
        cancelButton.setDisable(true);

        confirmButton.setOnAction(e -> confirmReservation());
        cancelButton.setOnAction(e -> cancelReservation());
    }

    private void setupTableSelection() {
        reservationTable.getSelectionModel().selectedItemProperty().addListener(
            (obs, oldSelection, newSelection) -> {
                boolean hasSelection = newSelection != null;
                confirmButton.setDisable(!hasSelection);
                cancelButton.setDisable(!hasSelection);
            }
        );
    }

    private void updateCounters() {
        long todayCount = filteredReservations.stream()
            .filter(r -> r.getHeureDebut() != null && 
                    r.getHeureDebut().toLocalDate().equals(LocalDateTime.now().toLocalDate()))
            .count();

        long pendingCount = filteredReservations.stream()
            .filter(r -> r.getNomStatus() != null && 
                    r.getNomStatus().equalsIgnoreCase("En Attente"))
            .count();

        long confirmedCount = filteredReservations.stream()
            .filter(r -> r.getNomStatus() != null && 
                    r.getNomStatus().equalsIgnoreCase("Validée"))
            .count();

        todayReservationsCount.setText(String.valueOf(todayCount));
        pendingReservationsCount.setText(String.valueOf(pendingCount));
        confirmedReservationsCount.setText(String.valueOf(confirmedCount));
    }

    @FXML
    public void showTodayReservations() {
        applyFilter(r -> r.getHeureDebut() != null && 
                r.getHeureDebut().toLocalDate().equals(LocalDateTime.now().toLocalDate()));
        animateClick(todayReservationsCount.getParent());
    }

    @FXML
    public void showPendingReservations() {
        applyFilter(r -> r.getNomStatus() != null && 
                r.getNomStatus().equalsIgnoreCase("En Attente"));
        animateClick(pendingReservationsCount.getParent());
    }

    @FXML
    public void showConfirmedReservations() {
        applyFilter(r -> r.getNomStatus() != null && 
                r.getNomStatus().equalsIgnoreCase("Validée"));
        animateClick(confirmedReservationsCount.getParent());
    }

    private void applyFilter(Predicate<Reservation> predicate) {
        filteredReservations.setPredicate(predicate);
        updateCounters();
    }

    private void animateClick(Node node) {
        ScaleTransition st = new ScaleTransition(Duration.millis(100), node);
        st.setToX(0.9);
        st.setToY(0.9);
        st.setCycleCount(2);
        st.setAutoReverse(true);
        st.play();
    }

    private void confirmReservation() {
    Reservation selected = reservationTable.getSelectionModel().getSelectedItem();
    if (selected != null) {
        reservationD.updateReservationStatus(selected.getId());
        reservationTable.refresh();
        updateCounters();

        // Redirection vers l'accueil
        redirectToAccueil();
    }
}

    private void cancelReservation() {
        Reservation selected = reservationTable.getSelectionModel().getSelectedItem();
        if (selected != null) {
            try (Connection connection = DatabaseConnection.getConnection()) {
                
                // Mise à jour de l'horaire pour le rendre disponible
                String updateAvailabilityQuery = "UPDATE horaires SET disponible = 1 WHERE id = ?";
                try (PreparedStatement req = connection.prepareStatement(updateAvailabilityQuery)) {
                    req.setInt(1, selected.getHoraireId());
                    int rowsAffected = req.executeUpdate();
                    
                    if (rowsAffected > 0) {
                        // Suppression de la réservation
                        String deleteReservationQuery = "DELETE FROM reservations WHERE id = ?";
                        try (PreparedStatement stmt = connection.prepareStatement(deleteReservationQuery)) {
                            stmt.setInt(1, selected.getId());
                            int rowsDeleted = stmt.executeUpdate();
    
                            if (rowsDeleted > 0) {
                                // Mise à jour des données après suppression
                                allReservations.remove(selected);
                                reservationTable.refresh();
                                updateCounters();

                                // Redirection vers l'accueil
                                redirectToAccueil();
                            }
                        }
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
    
            // Récupérer la fenêtre actuelle
            Stage stage = (Stage) reservationTable.getScene().getWindow();
    
            // Changer la scène
            stage.setScene(new Scene(root));
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}