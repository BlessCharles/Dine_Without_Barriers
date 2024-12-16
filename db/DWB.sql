USE webtech_fall2024_bless_oppong;

-- Table to store users information
CREATE TABLE DWB_Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    UserPassword VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    UserType ENUM('WheelchairUser', 'Restaurant','Admin') NOT NULL,
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
    FOREIGN KEY (UserID) REFERENCES DWB_Users(UserID) ON DELETE CASCADE
);

-- Table to store ratings by wheelchair users
CREATE TABLE DWB_Ratings (
    RatingID INT AUTO_INCREMENT PRIMARY KEY,
    RestaurantID INT NOT NULL,
    UserID INT NOT NULL,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (RestaurantID) REFERENCES DWB_Restaurants(RestaurantID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES DWB_Users(UserID) ON DELETE CASCADE,
    CONSTRAINT UniqueRatingPerUser UNIQUE (RestaurantID, UserID)
);

CREATE TABLE DWB_Restaurant_Pending (
    PendingID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ResName VARCHAR(255),
    ResAddress VARCHAR(255),
    PhoneNumber VARCHAR(20),
    AccessibilityFeatures TEXT,
    RestaurantImage VARCHAR(255),
    SubmissionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

