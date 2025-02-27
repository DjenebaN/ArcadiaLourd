package com.reservation.model;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.time.LocalDateTime;

import com.reservation.database.DatabaseConnection;

public class Reservation {
    private int id;
    private int utilisateur_id;
    private int salle_id;
    private int horaire_id;
    private int nb_participants;
    private LocalDateTime date_reservation;
    private int prix_total;
    private String nom_utilisateur; // Pour l'affichage
    private String nom_salle;      // Pour l'affichage
    private String status_nom;
    private LocalDateTime heure_debut; 
    private LocalDateTime heure_fin;   

    // Constructeur par défaut
    public Reservation() {
    }

    // Constructeur sans les horaires (pour la compatibilité)
    public Reservation(int id, int utilisateur_id, int salle_id, int horaire_id,
                     int nb_participants, LocalDateTime date_reservation, 
                     int prix_total, String nom_utilisateur, String nom_salle, 
                     String status_nom) {
        this(id, utilisateur_id, salle_id, horaire_id, nb_participants, 
             date_reservation, prix_total, nom_utilisateur, nom_salle, 
             status_nom, null, null);
    }

    // Constructeur complet avec les horaires
    public Reservation(int id, int utilisateur_id, int salle_id, int horaire_id,
                     int nb_participants, LocalDateTime date_reservation, 
                     int prix_total, String nom_utilisateur, String nom_salle, 
                     String status_nom, LocalDateTime heure_debut, LocalDateTime heure_fin) {
        this.id = id;
        this.utilisateur_id = utilisateur_id;
        this.salle_id = salle_id;
        this.horaire_id = horaire_id;
        this.nb_participants = nb_participants;
        this.date_reservation = date_reservation;
        this.prix_total = prix_total;
        this.nom_utilisateur = nom_utilisateur;
        this.nom_salle = nom_salle;
        this.status_nom = status_nom;
        this.heure_debut = heure_debut;
        this.heure_fin = heure_fin;
    }

    public class ReservationDAO {
    
    public boolean supprimerReservation(int reservationId) {
        String query = "DELETE FROM reservations WHERE id = ?";
        
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(query)) {
            
            stmt.setInt(1, reservationId);
            int rowsAffected = stmt.executeUpdate();
            
            return rowsAffected > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
}

    // Getters and Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    
    public int getUtilisateurId() { return utilisateur_id; }
    public void setUtilisateurId(int utilisateur_id) { this.utilisateur_id = utilisateur_id; }
    
    public int getSalleId() { return salle_id; }
    public void setSalleId(int salle_id) { this.salle_id = salle_id; }
    
    public int getHoraireId() { return horaire_id; }
    public void setHoraireId(int horaire_id) { this.horaire_id = horaire_id; }
    
    public int getNbParticipants() { return nb_participants; }
    public void setNbParticipants(int nb_participants) { this.nb_participants = nb_participants; }
    
    public LocalDateTime getDateReservation() { return date_reservation; }
    public void setDateReservation(LocalDateTime date_reservation) { this.date_reservation = date_reservation; }
    
    public int getPrixTotal() { return prix_total; }
    public void setPrixTotal(int prix_total) { this.prix_total = prix_total; }
    
    public String getNomUtilisateur() { return nom_utilisateur; }
    public void setNomUtilisateur(String nom_utilisateur) { this.nom_utilisateur = nom_utilisateur; }
    
    public String getNomSalle() { return nom_salle; }
    public void setNomSalle(String nom_salle) { this.nom_salle = nom_salle; }

    public String getNomStatus() { return status_nom; }
    public void setNomStatus(String status_nom) { this.status_nom = status_nom; }
    
    public LocalDateTime getHeureDebut() {
        return heure_debut;
    }

    public void setHeureDebut(LocalDateTime heure_debut) {
        this.heure_debut = heure_debut;
    }

    public LocalDateTime getHeureFin() {
        return heure_fin;
    }

    public void setHeureFin(LocalDateTime heure_fin) {
        this.heure_fin = heure_fin;
    }
    
    // Méthodes de compatibilité pour l'interface
    public String getClientName() { return nom_utilisateur; }
    public String getRoomName() { return nom_salle; }
    public String getStatusName() { return status_nom; }
    public LocalDateTime getDateTime() { return date_reservation; }
}
