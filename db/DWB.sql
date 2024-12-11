CREATE DATABASE DWB;

USE DWB;

-- Table to store users information
CREATE TABLE DWB_Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    UserPassword VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    UserType ENUM('WheelchairUser', 'Restaurant') NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store restaurant details
CREATE TABLE DWB_Restaurants (
    RestaurantID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    ResName VARCHAR(100) NOT NULL,
    ResAddress VARCHAR(255) NOT NULL,
    PhoneNumber VARCHAR(15),
    AccessibilityFeatures TEXT,
    RestaurantImage VARCHAR(500) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES DWB_Users(UserID)
);

-- Table to store ratings by wheelchair users
CREATE TABLE DWB_Ratings (
    RatingID INT AUTO_INCREMENT PRIMARY KEY,
    RestaurantID INT NOT NULL,
    UserID INT NOT NULL,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (RestaurantID) REFERENCES DWB_Restaurants(RestaurantID),
    FOREIGN KEY (UserID) REFERENCES DWB_Users(UserID),
    CONSTRAINT UniqueRatingPerUser UNIQUE (RestaurantID, UserID)
);

