package com.reservation.database;

import com.reservation.model.Utilisateur;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import java.sql.*;

public class UtilisateurD {

    public boolean supprimerUtilisateur(int utilisateurId) {
        String query = "DELETE FROM utilisateurs WHERE id = ?";
        try (Connection connection = DatabaseConnection.getConnection();
             PreparedStatement stmt = connection.prepareStatement(query)) {
             
            stmt.setInt(1, utilisateurId); 
            int rowsAffected = stmt.executeUpdate();
            return rowsAffected > 0; 
        } catch (SQLException e) {
            e.printStackTrace();
            return false; 
        }
    }
    
    public ObservableList<Utilisateur> getAllUtilisateurs() {
        ObservableList<Utilisateur> utilisateurs = FXCollections.observableArrayList();
        
        try (Connection conn = DatabaseConnection.getConnection()) {
            String query = "SELECT * FROM utilisateurs";
            
            try (PreparedStatement pst = conn.prepareStatement(query);
                 ResultSet rs = pst.executeQuery()) {
                
                while (rs.next()) {
                    try {                        
                        Utilisateur utilisateur = new Utilisateur(
                            rs.getInt("id"),
                            rs.getString("nom"),
                            rs.getString("prenom"),
                            rs.getString("email"),
                            rs.getString("mot_de_passe"),
                            rs.getInt("type_utilisateur")
                        );
                        utilisateurs.add(utilisateur);
                    } catch (SQLException e) {
                        System.err.println("Erreur lors de la création d'un utilisateur : " + e.getMessage());
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la récupération des utilisateurs : " + e.getMessage());
            e.printStackTrace();
        }
        return utilisateurs;
    }
}
