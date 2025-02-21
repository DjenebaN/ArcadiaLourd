package com.reservation.controller;

import com.reservation.model.Reservation;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.*;
import javafx.animation.ScaleTransition;
import javafx.util.Duration;
import javafx.scene.Node;
import javafx.beans.property.SimpleStringProperty;

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

public class AdminDashboardController implements Initializable {

    @FXML private TableView<Reservation> reservationTable;
    @FXML private TableColumn<Reservation, String> clientColumn;
    @FXML private TableColumn<Reservation, String> roomColumn;
    @FXML private TableColumn<Reservation, String> dateTimeColumn;
    @FXML private TableColumn<Reservation, String> statusColumn;
    @FXML private TableColumn<Reservation, String> action;
    @FXML private Label todayReservationsCount;
    @FXML private Label pendingReservationsCount;
    @FXML private Label confirmedReservationsCount;
    @FXML private TextField searchField;
    @FXML private Button confirmButton;
    @FXML private Button cancelButton;

    private ReservationD reservationD;
    private ObservableList<Reservation> allReservations;
    private FilteredList<Reservation> filteredReservations;

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        reservationD = new ReservationD();
        // Configuration des colonnes
        clientColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomUtilisateur()));
        roomColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomSalle()));
        statusColumn.setCellValueFactory(cellData -> new SimpleStringProperty(cellData.getValue().getNomStatus()));
        dateTimeColumn.setCellValueFactory(cellData -> {
            LocalDateTime dateTime = cellData.getValue().getDateReservation();
            return new SimpleStringProperty(dateTime.format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm")));
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

                return reservation.getNomUtilisateur().toLowerCase().contains(lowerCaseFilter) ||
                       reservation.getNomSalle().toLowerCase().contains(lowerCaseFilter) ||
                       reservation.getDateReservation().format(DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm"))
                           .toLowerCase().contains(lowerCaseFilter);
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
            .filter(r -> r.getDateReservation().toLocalDate().equals(LocalDateTime.now().toLocalDate()))
            .count();

        long pendingCount = filteredReservations.stream()
            .filter(r -> r.getStatusName().equalsIgnoreCase("En Attente")) // Ajuste selon ton statut exact
            .count();

        long confirmedCount = filteredReservations.stream()
            .filter(r -> r.getStatusName().equalsIgnoreCase("Validée")) // Ajuste selon ton statut exact
            .count();

        todayReservationsCount.setText(String.valueOf(todayCount));
        pendingReservationsCount.setText(String.valueOf(pendingCount));
        confirmedReservationsCount.setText(String.valueOf(confirmedCount));
    }

    @FXML
    public void showTodayReservations() {
        applyFilter(r -> r.getDateReservation().toLocalDate().equals(LocalDateTime.now().toLocalDate()));
        animateClick(todayReservationsCount.getParent());
    }

    @FXML
    public void showPendingReservations() {
        applyFilter(r -> true);
        animateClick(pendingReservationsCount.getParent());
    }

    @FXML
    public void showConfirmedReservations() {
        applyFilter(r -> true);
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
            showAlert(Alert.AlertType.INFORMATION, "Confirmation", 
                     "La réservation de " + selected.getNomUtilisateur() + " a été confirmée.");
        }
    }

    private void cancelReservation() {
        Reservation selected = reservationTable.getSelectionModel().getSelectedItem();
        if (selected != null) {
            // Utilisation de try-with-resources pour gérer la connexion
            try (Connection connection = DatabaseConnection.getConnection()) {
                
                // Première requête pour mettre à jour l'état de l'horaire (disponible)
                String updateAvailabilityQuery = "UPDATE horaires SET disponible = 1 WHERE id = ?";
                try (PreparedStatement req = connection.prepareStatement(updateAvailabilityQuery)) {
                    req.setInt(1, selected.getHoraireId()); // Utilise l'id horaire de la réservation
                    int rowsAffected = req.executeUpdate();
                    if (rowsAffected > 0) {
                        // Si la mise à jour de l'horaire a réussi, on continue avec la suppression de la réservation
                        String deleteReservationQuery = "DELETE FROM reservations WHERE id = ?";
                        try (PreparedStatement stmt = connection.prepareStatement(deleteReservationQuery)) {
                            stmt.setInt(1, selected.getId()); // ID de la réservation à supprimer
                            int rowsDeleted = stmt.executeUpdate();
    
                            if (rowsDeleted > 0) {
                                // Si la suppression de la réservation a réussi, retirer la réservation de la liste en mémoire
                                allReservations.remove(selected);
                                // Rafraîchir le tableau et mettre à jour les compteurs
                                reservationTable.refresh();
                                updateCounters();
                                showAlert(Alert.AlertType.INFORMATION, "Annulation", 
                                         "La réservation de " + selected.getNomUtilisateur() + " a été annulée.");
                            } else {
                                showAlert(Alert.AlertType.ERROR, "Erreur", 
                                         "Impossible d'annuler la réservation.");
                            }
                        }
                    } else {
                        showAlert(Alert.AlertType.ERROR, "Erreur", 
                                 "Impossible de mettre à jour l'horaire. La réservation n'a pas été annulée.");
                    }
                }
            } catch (SQLException e) {
                // Si une erreur SQL survient, afficher une alerte
                showAlert(Alert.AlertType.ERROR, "Erreur de base de données", 
                         "Une erreur est survenue lors de l'annulation de la réservation : " + e.getMessage());
                e.printStackTrace();
            }
        }
    }
    
    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
}
