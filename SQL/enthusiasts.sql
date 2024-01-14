CREATE TABLE enthusiasts(
     enthusiast_id INT AUTO_INCREMENT,
     enthusiast_first_name VARCHAR(64),
     enthusiast_last_name VARCHAR(64),
     enthusiast_street_address VARCHAR(64),
     enthusiast_city VARCHAR(64),
     enthusiast_region VARCHAR(64),
     enthusiast_zipcode VARCHAR(16),
     enthusiast_country VARCHAR(64), -- might need to add a subset table
     enthusiast_email_address VARCHAR(64) NOT NULL UNIQUE,
     enthusiast_phone_number VARCHAR(16),
     PRIMARY KEY (enthusiast_id)
);

