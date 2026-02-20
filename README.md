# Plateforme Immobilière - Gestion de Biens

![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13-blue)
![Laravel](https://img.shields.io/badge/Laravel-8-red)
![License](https://img.shields.io/badge/License-MIT-green)
![Stage](https://img.shields.io/badge/Stage-2025-orange)

## Contexte
Projet développé lors de mon stage chez **Data Insight Solution** (Lomé, Togo) en 2024.  
L'objectif était de créer une plateforme de gestion immobilière permettant le suivi des biens, des propriétaires et des locataires.

---

## Objectifs du projet
- Modéliser et structurer une base de données PostgreSQL robuste
- Développer les fonctionnalités CRUD (Create, Read, Update, Delete)
- Optimiser les performances des requêtes
- Créer un tableau de bord d'administration pour le pilotage des données

---

## 🛠️ Technologies utilisées

| Domaine | Technologies |
|---------|-------------|
| **Base de données** | PostgreSQL |
| **Backend** | Laravel / PHP |
| **Frontend** | HTML/CSS, JavaScript |
| **Visualisation** | (optionnel : dashboard interne) |
| **Outils** | Git, GitHub, Trello |

---

## Base de données - Modélisation

### Schéma relationnel
![Schéma BDD](docs/schema-bdd.png)

**Tables principales :**
- `biens` : informations sur les propriétés (adresse, surface, prix, statut)
- `proprietaires` : coordonnées des propriétaires
- `locataires` : informations des locataires
- `contrats` : liens entre biens et locataires
- `paiements` : historique des loyers
- etc.

### Exemple de requête analytique
```sql
-- Nombre de biens par statut et par ville
SELECT 
    ville,
    statut,
    COUNT(*) as nombre_biens
FROM biens
GROUP BY ville, statut
ORDER BY ville, nombre_biens DESC;


