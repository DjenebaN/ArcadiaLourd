package com.reservation.controller;

import java.io.IOException;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Scene;
import javafx.scene.Parent;
import javafx.fxml.FXMLLoader;
import javafx.stage.Stage;
import javafx.scene.control.Button;
//import javafx.scene.Node;


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
    } catch (IOException e) {
        e.printStackTrace();
        // Loggez ou affichez l'erreur pour mieux comprendre pourquoi le chargement échoue.
    }
}
/* 
@FXML
private void goToAccueil(ActionEvent event) {
    try {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/accueil.fxml"));
        Parent root = loader.load();
        
        Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
        stage.setScene(new Scene(root));
        stage.show();
    } catch (IOException e) {
        e.printStackTrace();
    }
}
*/


    
}
