package com.reservation.database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.ResultSet;

public class DatabaseConnection {
    private static final String URL = "jdbc:mysql://localhost:3306/escapegame?serverTimezone=UTC";
    private static final String USER = "root";
    private static final String PASSWORD = "";
    private static Connection connection = null;
    
    static {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("Driver MySQL chargé avec succès !");
        } catch (ClassNotFoundException e) {
            System.err.println("Erreur de chargement du driver MySQL : " + e.getMessage());
            e.printStackTrace();
        }
    }
    
    public static Connection getConnection() throws SQLException {
        System.out.println("Tentative de connexion à la base de données...");
        System.out.println("URL: " + URL);
        System.out.println("Utilisateur: " + USER);
        
        Connection connection = null;
        try {
            connection = DriverManager.getConnection(URL, USER, PASSWORD);
            System.out.println("Connexion à la base de données réussie !");
            
            try (Statement stmt = connection.createStatement()) {
                ResultSet rs = stmt.executeQuery("SELECT 1");
                if (rs.next()) {
                    System.out.println("Test de connexion réussi !");
                }
            }
        } catch (SQLException e) {
            System.err.println("Erreur de connexion à la base de données : " + e.getMessage());
            if (connection != null) {
                try {
                    connection.close();
                } catch (SQLException ex) {
                    ex.printStackTrace();
                }
            }
            throw e;
        }
        
        return connection;
    }
    
    public static void closeConnection() {
        if (connection != null) {
            try {
                connection.close();
                System.out.println("Connexion à la base de données fermée.");
            } catch (SQLException e) {
                System.err.println("Erreur lors de la fermeture de la connexion : " + e.getMessage());
                e.printStackTrace();
            }
        }
    }
}
