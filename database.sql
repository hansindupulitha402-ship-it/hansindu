CREATE TABLE IF NOT EXISTS ligozza_registrations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(254) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_ligozza_registration_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2006001;

-- If the table already exists and has no registrations, run this separately:
-- ALTER TABLE ligozza_registrations AUTO_INCREMENT = 2006001;
