# PharmaFEFO - Pharmacy Stock Management System

Application de gestion de stock pharmaceutique avec logique FEFO (First Expired, First Out).

## Pre-requis

- **PHP 8.1+** (requis pour les enums)
- **MySQL 5.7+** ou **MariaDB 10.3+**
- **Apache** avec `mod_rewrite` active (XAMPP, WAMP, MAMP, etc.)

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/fadaldrame97-sys/PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption.git
```

Placer le dossier dans le repertoire web de votre serveur :
- **XAMPP** : `C:\xampp\htdocs\PharmaFEFO\`
- **WAMP** : `C:\wamp64\www\PharmaFEFO\`
- **MAMP** : `/Applications/MAMP/htdocs/PharmaFEFO/`

### 2. Creer la base de donnees

**Option A - phpMyAdmin :**
1. Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`)
2. Onglet **Import**
3. Choisir le fichier `sql/setup.sql`
4. Cliquer **Executer**

**Option B - Terminal :**
```bash
mysql -u root < sql/setup.sql
```

### 3. Verifier la configuration de la base

Le fichier `config/database.php` utilise ces parametres par defaut :
- Host : `localhost`
- Base : `PharmaFEFO`
- User : `root`
- Password : `` (vide)

Si votre mot de passe MySQL est different, modifiez la ligne correspondante dans `config/database.php`.

### 4. Activer mod_rewrite (XAMPP)

Verifier que `mod_rewrite` est active dans Apache :
1. Ouvrir `C:\xampp\apache\conf\httpd.conf`
2. Trouver la ligne `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Retirer le `#` pour l'activer
4. Redemarrer Apache

### 5. Acceder a l'application

```
http://localhost/PharmaFEFO/public/index.php
```

Ou si mod_rewrite est active :
```
http://localhost/PharmaFEFO/public/
```

## Comptes de test

| Email | Mot de passe | Role |
|-------|-------------|------|
| admin@pharma.com | admin123 | ADMIN |
| pharmacien@pharma.com | pharma123 | PHARMACIEN |
| preparateur@pharma.com | prep123 | PREPARATEUR |

## Structure du projet

```
PharmaFEFO/
├── config/
│   └── database.php          # Connexion PDO
├── public/
│   ├── index.php             # Routeur principal (point d'entree)
│   ├── css.css
│   └── .htaccess
├── sql/
│   └── setup.sql             # Creation de la base + donnees de test
├── src/
│   ├── Controller/           # Controleurs MVC
│   ├── Entity/               # Entites (User, Product, StockBatch, etc.)
│   ├── Enum/                 # Enums PHP 8.1 (Role, BatchStatus)
│   ├── Repository/           # Acces aux donnees (PDO)
│   └── Services/             # Services metier (criticite FEFO)
├── templates/
│   ├── Autentification/      # Login / Logout
│   ├── Stock/                # Gestion des lots
│   ├── dashboard/            # Tableau de bord + admin
│   ├── inventory/            # Validation inventaire
│   ├── layout/               # Template de base (navigation)
│   ├── reception_de_commandes/
│   └── returns/              # Gestion des retours
├── test_db.php               # Test de connexion a la base
├── .htaccess                 # Redirection vers public/
└── README.md
```

## Depannage

### Erreur "Could not find driver"
Installer l'extension PDO MySQL :
- **XAMPP** : Activer `extension=pdo_mysql` dans `php.ini`
- **Linux** : `sudo apt-get install php-mysql`

### Page blanche
1. Activer l'affichage des erreurs dans `php.ini` : `display_errors = On`
2. Verifier que PHP 8.1+ est installe : `php -v`
3. Verifier que le module `mod_rewrite` d'Apache est active

### Erreur "Table doesn't exist"
Executer le script SQL : `mysql -u root < sql/setup.sql`

### Erreur "Access denied for user 'root'"
Modifier le mot de passe dans `config/database.php` pour correspondre a votre configuration MySQL.
