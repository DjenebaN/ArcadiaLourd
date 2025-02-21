package com.reservation.controller;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Scene;
import javafx.scene.Parent;
import javafx.fxml.FXMLLoader;
import javafx.stage.Stage;
import javafx.scene.control.Button;

public class AccueilController {

    @FXML
    private void voir(ActionEvent event) {
        try {
            // Charger le fichier FXML pour le tableau de bord des réservations
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/admin_dashboard.fxml"));
            Parent root = loader.load();

            // Obtenir la fenêtre actuelle et changer la scène pour afficher le tableau de bord
            Stage stage = (Stage) ((Button) event.getSource()).getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Tableau de bord des réservations");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
