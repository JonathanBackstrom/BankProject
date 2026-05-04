CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    card_number VARCHAR(20) NOT NULL UNIQUE,
    pin_hash VARCHAR(60) NOT NULL,
    name VARCHAR(156) NOT NULL,
    role VARCHAR(56) NOT NULL DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    account_type VARCHAR(156) NOT NULL DEFAULT 'checking',
    balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    from_account_id INT,
    to_account_id INT,
    `type` VARCHAR(56) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_account_id) REFERENCES accounts(id),
    FOREIGN KEY (to_account_id) REFERENCES accounts(id)
);