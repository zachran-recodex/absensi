-- Menambahkan kolom lokasi pada tabel users
ALTER TABLE users 
ADD COLUMN location_latitude DECIMAL(9,6) DEFAULT NULL, 
ADD COLUMN location_longitude DECIMAL(9,6) DEFAULT NULL, 
ADD COLUMN location_radius INT DEFAULT 100 COMMENT 'Radius dalam meter' AFTER location_longitude;

-- Menambahkan indeks untuk pencarian yang lebih cepat
ALTER TABLE users ADD INDEX idx_location (location_latitude, location_longitude);
