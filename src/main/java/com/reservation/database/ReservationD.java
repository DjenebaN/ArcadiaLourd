package com.reservation.database;

import com.reservation.model.Reservation;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import java.sql.*;
import java.time.LocalDateTime;

public class ReservationD {

    public boolean supprimerReservation(int reservationId) {
        String query = "DELETE FROM reservations WHERE id = ?";
        try (Connection connection = DatabaseConnection.getConnection();
             PreparedStatement stmt = connection.prepareStatement(query)) {
             
            stmt.setInt(1, reservationId); 
            int rowsAffected = stmt.executeUpdate();
            return rowsAffected > 0; 
        } catch (SQLException e) {
            e.printStackTrace();
            return false; 
        }
    }
    
    public ObservableList<Reservation> getAllReservations() {
        ObservableList<Reservation> reservations = FXCollections.observableArrayList();
        
        try (Connection conn = DatabaseConnection.getConnection()) {
            String query = "SELECT r.*, u.nom as nom_utilisateur, s.nom as nom_salle, " +
                          "sr.etat as status_nom, h.heure_debut, h.heure_fin " +
                          "FROM reservations r " +
                          "JOIN utilisateurs u ON r.utilisateur_id = u.id " +
                          "JOIN salles s ON r.salle_id = s.id " +
                          "JOIN status_res sr ON r.status_id = sr.id " +
                          "JOIN horaires h ON r.horaire_id = h.id " +
                          "ORDER BY r.date_reservation DESC";
                      
            try (PreparedStatement pst = conn.prepareStatement(query);
                 ResultSet rs = pst.executeQuery()) {
                
                while (rs.next()) {
                    try {
                        Timestamp heureDebut = rs.getTimestamp("heure_debut");
                        Timestamp heureFin = rs.getTimestamp("heure_fin");
                        
                        Reservation reservation = new Reservation(
                            rs.getInt("id"),
                            rs.getInt("utilisateur_id"),
                            rs.getInt("salle_id"),
                            rs.getInt("horaire_id"),
                            rs.getInt("nb_participants"),
                            rs.getTimestamp("date_reservation").toLocalDateTime(),
                            rs.getInt("prix_total"),
                            rs.getString("nom_utilisateur"),
                            rs.getString("nom_salle"),
                            rs.getString("status_nom"),
                            heureDebut != null ? heureDebut.toLocalDateTime() : null,
                            heureFin != null ? heureFin.toLocalDateTime() : null
                        );
                        reservations.add(reservation);
                    } catch (SQLException e) {
                        System.err.println("Erreur lors de la création d'une réservation : " + e.getMessage());
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la récupération des réservations : " + e.getMessage());
            e.printStackTrace();
        }
        return reservations;
    }

    public boolean updateReservationStatus(int reservationId) {
            String query = "UPDATE reservations SET status_id = 2 WHERE id = ?" ;

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement pst = conn.prepareStatement(query)) {
            
            pst.setInt(1, reservationId);
            
            int rowsAffected = pst.executeUpdate();
            return rowsAffected > 0;
            
        } catch (SQLException e) {
            System.err.println("Erreur lors de la mise à jour du statut : " + e.getMessage());
            e.printStackTrace();
            return false;
        }
    }

    public ObservableList<Reservation> searchReservations(String searchText) {
        ObservableList<Reservation> reservations = FXCollections.observableArrayList();
        String query = "SELECT r.*, u.nom as nom_utilisateur, s.nom as nom_salle, " +
                      "sr.etat as status_nom, h.heure_debut, h.heure_fin " +
                      "FROM reservations r " +
                      "JOIN utilisateurs u ON r.utilisateur_id = u.id " +
                      "JOIN salles s ON r.salle_id = s.id " +
                      "JOIN status_res sr ON r.status_id = sr.id " +
                      "JOIN horaires h ON r.horaire_id = h.id " +
                      "WHERE u.nom LIKE ? OR s.nom LIKE ? OR sr.etat LIKE ? " +
                      "ORDER BY r.date_reservation DESC";
        
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement pst = conn.prepareStatement(query)) {
            
            String searchPattern = "%" + searchText + "%";
            for (int i = 1; i <= 3; i++) {
                pst.setString(i, searchPattern);
            }
            
            ResultSet rs = pst.executeQuery();
            while (rs.next()) {
                try {
                    Timestamp heureDebut = rs.getTimestamp("heure_debut");
                    Timestamp heureFin = rs.getTimestamp("heure_fin");
                    
                    Reservation reservation = new Reservation(
                        rs.getInt("id"),
                        rs.getInt("utilisateur_id"),
                        rs.getInt("salle_id"),
                        rs.getInt("horaire_id"),
                        rs.getInt("nb_participants"),
                        rs.getTimestamp("date_reservation").toLocalDateTime(),
                        rs.getInt("prix_total"),
                        rs.getString("nom_utilisateur"),
                        rs.getString("nom_salle"),
                        rs.getString("status_nom"),
                        heureDebut != null ? heureDebut.toLocalDateTime() : null,
                        heureFin != null ? heureFin.toLocalDateTime() : null
                    );
                    reservations.add(reservation);
                } catch (SQLException e) {
                    System.err.println("Erreur lors de la création d'une réservation : " + e.getMessage());
                }
            }
            
        } catch (SQLException e) {
            System.err.println("Erreur lors de la recherche des réservations : " + e.getMessage());
            e.printStackTrace();
        }
        
        return reservations;
    }
}
