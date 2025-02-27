package com.reservation.model;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import com.reservation.database.DatabaseConnection;

public class Utilisateur {
    private int id;
    private String nom;
    private String prenom;
    private String email;
    private String motDePasse;
    private int typeUtilisateur; // 1: Admin, 2: Client, 3: GameMaster

    // Constructeur pour la connexion
    public Utilisateur(String email, String motDePasse) {
        this.email = email;
        this.motDePasse = motDePasse;
    }

    // Constructeur complet
    public Utilisateur(int id, String nom, String prenom, String email, String motDePasse, int typeUtilisateur) {
        this.id = id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.motDePasse = motDePasse;
        this.typeUtilisateur = typeUtilisateur;
    }

    // Getters et Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getPrenom() { return prenom; }
    public void setPrenom(String prenom) { this.prenom = prenom; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getMotDePasse() { return motDePasse; }
    public void setMotDePasse(String motDePasse) { this.motDePasse = motDePasse; }

    public int getTypeUtilisateur() { return typeUtilisateur; }
    public void setTypeUtilisateur(int typeUtilisateur) { this.typeUtilisateur = typeUtilisateur; }

    // Méthode de connexion
    public static Utilisateur connexion(String email, String motDePasse) throws SQLException {
        String sql = "SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?";
        
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            
            pstmt.setString(1, email);
            pstmt.setString(2, motDePasse); // Note: Dans un cas réel, il faudrait hasher le mot de passe
            
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    return new Utilisateur(
                        rs.getInt("id"),
                        rs.getString("nom"),
                        rs.getString("prenom"),
                        rs.getString("email"),
                        rs.getString("mot_de_passe"),
                        rs.getInt("type_utilisateur")
                    );
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur lors de la connexion : " + e.getMessage());
            throw e;
        }
        return null; // Retourne null si l'authentification échoue
    }

    // Méthodes pour vérifier le type d'utilisateur
    public boolean estAdmin() {
        return this.typeUtilisateur == 3;
    }

    public boolean estClient() {
        return this.typeUtilisateur == 1;
    }

    public boolean estGameMaster() {
        return this.typeUtilisateur == 2;
    }
}
