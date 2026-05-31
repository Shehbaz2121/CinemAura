-- database.sql
CREATE DATABASE IF NOT EXISTS cinemaaura;
USE cinemaaura;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CinemAura Database
CREATE DATABASE IF NOT EXISTS cinemaaura;
USE cinemaaura;

--USERS
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--MOVIES
CREATE TABLE movies (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    genre       VARCHAR(50)  NOT NULL,
    description TEXT,
    duration    INT,           -- in minutes e.g. 120
    rating      DECIMAL(2,1), -- e.g. 8.2
    year        YEAR,
    poster      VARCHAR(255), -- image filename e.g. "avengers.jpg"
    trailer_url VARCHAR(255), -- youtube link
    status      ENUM('showing', 'upcoming') DEFAULT 'showing',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--THEATERS
CREATE TABLE theaters (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL,
    capacity INT
);

--SHOWS
CREATE TABLE shows (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    movie_id       INT NOT NULL,
    theater_id     INT NOT NULL,
    show_date      DATE NOT NULL,
    show_time      TIME NOT NULL,
    gold_price     DECIMAL(8,2) NOT NULL,
    platinum_price DECIMAL(8,2) NOT NULL,
    box_price      DECIMAL(8,2) NOT NULL,

    FOREIGN KEY (movie_id)   REFERENCES movies(id)   ON DELETE CASCADE,
    FOREIGN KEY (theater_id) REFERENCES theaters(id) ON DELETE CASCADE
);

-- BOOKINGS
CREATE TABLE bookings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    show_id    INT NOT NULL,
    seat_class ENUM('gold', 'platinum', 'box') NOT NULL,
    tickets    INT NOT NULL,
    kids       INT DEFAULT 0,  -- number of kids (discounted)
    total      DECIMAL(10,2) NOT NULL,
    status     ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    booked_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (show_id) REFERENCES shows(id) ON DELETE CASCADE
);