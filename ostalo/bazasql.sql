CREATE DATABASE IF NOT EXISTS roo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE roo;

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ime VARCHAR(100) NOT NULL,
    prezime VARCHAR(100) NOT NULL,
    korisnicko_ime VARCHAR(100) NULL UNIQUE,
    mail VARCHAR(180) NOT NULL UNIQUE,
    lozinka VARCHAR(255) NOT NULL,
    profilna_slika VARCHAR(255) NULL,
    bio TEXT NULL,
    title VARCHAR(100) NOT NULL DEFAULT 'Dnevni sanjar',
    remember_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- USER INTERESTS
CREATE TABLE user_interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    interest VARCHAR(120) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ADVENTURES
CREATE TABLE adventures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    naziv VARCHAR(180) NOT NULL,
    trip_type VARCHAR(255) NULL,
    travel_with VARCHAR(100) NULL,
    budget_type VARCHAR(100) NULL,
    budget_per_day INT NULL,
    destination TEXT NULL,
    daily_plan TEXT NULL,
    transport_mode VARCHAR(100) NULL,
    accommodation_type VARCHAR(100) NULL,
    host_languages VARCHAR(255) NULL,
    adventure_image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ADVENTURE TAGS
CREATE TABLE adventure_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    adventure_id INT NOT NULL,
    tag_type VARCHAR(100) NOT NULL,
    tag_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (adventure_id) REFERENCES adventures(id) ON DELETE CASCADE
);

-- ACCOMMODATIONS
CREATE TABLE accommodations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    name VARCHAR(180) NOT NULL,
    accommodation_type ENUM('hotel_motel', 'hostel_apartment') NOT NULL,
    max_price_per_night INT NOT NULL,
    image VARCHAR(255) NULL DEFAULT 'media/slike/smjestaj-placeholder.jpg',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CITY ACTIVITIES
CREATE TABLE city_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    activity_type VARCHAR(120) NOT NULL,
    budget_level ENUM('low', 'mid', 'high') NOT NULL,
    name VARCHAR(180) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ADVENTURE JOIN REQUESTS
CREATE TABLE adventure_join_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    adventure_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (adventure_id) REFERENCES adventures(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);