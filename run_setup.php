<?php
include 'connection.php';
pg_query($conn, "CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

pg_query($conn, "CREATE TABLE IF NOT EXISTS admin_logs (
    id SERIAL PRIMARY KEY,
    admin_id INT REFERENCES admins(id) ON DELETE SET NULL,
    admin_name VARCHAR(100),
    action VARCHAR(255) NOT NULL,
    target TEXT,
    ip_address VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

pg_query($conn, "INSERT INTO admins (username, password, name) VALUES ('admin', '$2y$10\$Y.P.p.9x/T3L9u9ZqfA.MOMm3O9E/w4u6d1.L/Rz0w6c0v/T/1sK2', 'Pradipta') ON CONFLICT (username) DO NOTHING");

echo "Done";
?>
