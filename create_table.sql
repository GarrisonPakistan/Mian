CREATE TABLE `client_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each client
    `name` VARCHAR(255) NOT NULL,        -- Full name of the client
    `email` VARCHAR(255) NOT NULL UNIQUE, -- Email address (must be unique)
    `phone_number` VARCHAR(15) NOT NULL UNIQUE, -- Phone number (must be unique)
    `address` TEXT,                      -- Address of the client
    `status` ENUM('active', 'inactive') DEFAULT 'active', -- Status of the client
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Record creation timestamp
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Record update timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
