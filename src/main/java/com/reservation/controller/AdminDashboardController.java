package com.reservation.controller;

import com.reservation.model.Reservation;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
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