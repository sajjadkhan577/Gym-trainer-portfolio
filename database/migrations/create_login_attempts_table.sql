CREATE TABLE IF NOT EXISTS login_attempts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_login_attempts_email_ip_time (email, ip_address, attempted_at),
    INDEX idx_login_attempts_time (attempted_at)
);