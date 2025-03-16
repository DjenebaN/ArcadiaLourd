package com.reservation.database;

import com.reservation.model.Salle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import java.sql.*;

public class SalleD {

    public static boolean ajouterSalle(Salle salle) {
        String query = "INSERT INTO salles (nom, description, duree, nb_joueurs_min, nb_joueurs_max, prix) VALUES (?, ?, ?, ?, ?, ?)";

        try (Connection connection = DatabaseConnection.getConnection();
             PreparedStatement stmt = connection.prepareStatement(query)) {
             
            stmt.setString(1, salle.getNom());
            stmt.setString(2, salle.getDesc());
            stmt.setInt(3, salle.getDuree());
            stmt.setInt(4, salle.getJoueursMin());
            stmt.setInt(5, salle.getJoueursMax());
            stmt.setInt(6, salle.getPrix());

            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    public boolean supprimerSalle(int salleId) {
        String query = "DELETE FROM salles WHERE id = ?";
        try (Connection connection = DatabaseConnection.getConnection();
             PreparedStatement stmt = connection.prepareStatement(query)) {
             
            stmt.setInt(1, salleId); 
            int rowsAffected = stmt.executeUpdate();
            return rowsAffected > 0; 
        } catch (SQLException e) {
            e.printStackTrace();
            return false; 
        }
    }
    
    public ObservableList<Salle> getAllSalles() {
        ObservableList<Salle> salles = FXCollections.observableArrayList();
        
        try (Connection conn = DatabaseConnection.getConnection()) {
            String query = "SELECT * FROM salles";
            
            try (PreparedStatement pst = conn.prepareStatement(query);
                 ResultSet rs = pst.executeQuery()) {
                
                while (rs.next()) {
                    try {                        
                        Salle salle = new Salle(
                            rs.getInt("id"),
                            rs.getString("nom"),
                            rs.getString("description"),
                            rs.getInt("duree"),
                            rs.getInt("nb_joueurs_max"),
                            rs.getInt("prix")
                        );
                        salles.add(salle);
                    } catch (SQLException e) {
                        System.err.println("Erreur lors de la création d'une salle : " + e.getMessage());
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la récupération des salles : " + e.getMessage());
            e.printStackTrace();
        }
        return salles;
    }
}
