# Système de Réservation JavaFX

Ce projet est une application de gestion de réservations développée avec JavaFX et MySQL.

## Prérequis

- Java JDK 17 ou supérieur
- Maven
- MAMP (Mac) ou XAMPP (Windows)
- MySQL

## Structure du Projet

```
JavaLourd/
├── src/
│   └── main/
│       ├── java/
│       │   └── com/
│       │       └── reservation/
│       │           ├── controller/
│       │           ├── database/
│       │           └── Main.java
│       └── resources/
│           ├── styles/
│           └── views/
├── txt/
│   ├── installation_mac.txt
│   └── installation_windows.txt
└── pom.xml
```

## Installation

Veuillez suivre les instructions d'installation correspondant à votre système d'exploitation :
- Pour Mac OS : voir le fichier txt/installation_mac.txt
- Pour Windows : voir le fichier txt/installation_windows.txt

## Configuration de la Base de Données

La configuration de la base de données se trouve dans :
src/main/java/com/reservation/database/DatabaseConnection.java

## Lancement de l'Application

1. Assurez-vous que MAMP/XAMPP est en cours d'exécution
2. Ouvrez un terminal dans le dossier du projet
3. Exécutez : mvn clean javafx:run

## Fonctionnalités

- Gestion des réservations
- Interface administrateur
- Base de données MySQL
- Interface utilisateur JavaFX

## Support

Pour plus d'informations, consultez les fichiers d'installation dans le dossier txt/
