package com.reservation.model;

public class Salle {
    private int id;
    private String nom;
    private String description;
    private int duree;
    private int nb_joueurs_min;
    private int nb_joueurs_max;
    private int prix;
    
    // Constructeur complet
    public Salle(int id, String nom, String description, int duree, int nb_joueurs_max, int prix) {
        this.id = id;
        this.nom = nom;
        this.description = description;
        this.duree = duree;
        this.nb_joueurs_max = nb_joueurs_max;
        this.prix = prix;
    }

    public Salle(String nom, String description, int duree, int nb_joueurs_min, int nb_joueurs_max, int prix) {
        this.nom = nom;
        this.description = description;
        this.duree = duree;
        this.nb_joueurs_min = nb_joueurs_min;
        this.nb_joueurs_max = nb_joueurs_max;
        this.prix = prix;
    }

    // Getters et Setters
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getDesc() { return description; }
    public void setDesc(String description) { this.description = description; }

    public int getDuree() { return duree; }
    public void setDuree(int duree) { this.duree = duree; }

    public int getJoueursMin() { return nb_joueurs_min; }
    public void setJoueursMin(int nb_joueurs_min) { this.nb_joueurs_min = nb_joueurs_min; }

    public int getJoueursMax() { return nb_joueurs_max; }
    public void setJoueursMax(int nb_joueurs_max) { this.nb_joueurs_max = nb_joueurs_max; }

    public int getPrix() { return prix; }
    public void setPrix(int prix) { this.prix = prix; }

}
