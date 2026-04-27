<?php
include 'connection.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS saved_passengers (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id),
        name VARCHAR(255) NOT NULL,
        identity_type VARCHAR(50) NOT NULL,
        identity_number VARCHAR(100) NOT NULL,
        gender VARCHAR(20),
        date_of_birth DATE,
        phone VARCHAR(20),
        email VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    
    $conn->exec($sql);
    echo json_encode(["status" => "success", "message" => "Tabel saved_passengers berhasil dibuat"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
