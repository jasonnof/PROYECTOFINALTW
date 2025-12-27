-- Recomendado
SET sql_mode = 'STRICT_ALL_TABLES';
SET time_zone = '+00:00';

-- 1) USERS
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(120) NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','gestor','user') NOT NULL DEFAULT 'user',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) ACTIVITIES
CREATE TABLE IF NOT EXISTS activities (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(120) NULL,
  start_at DATETIME NULL,
  end_at DATETIME NULL,
  image_path VARCHAR(255) NULL,
  created_by INT UNSIGNED NOT NULL,
  published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_activities_created_by (created_by),
  KEY idx_activities_published (published),
  CONSTRAINT fk_activities_users
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) GALLERY_IMAGES
CREATE TABLE IF NOT EXISTS gallery_images (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  uploaded_by INT UNSIGNED NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_gallery_uploaded_by (uploaded_by),
  KEY idx_gallery_active (active),
  CONSTRAINT fk_gallery_users
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) GAME_SCORES
CREATE TABLE IF NOT EXISTS game_scores (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NULL,
  nickname VARCHAR(50) NULL,
  score INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_scores_user_id (user_id),
  KEY idx_scores_score (score),
  CONSTRAINT fk_scores_users
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
