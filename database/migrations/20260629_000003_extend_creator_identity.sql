-- DAYA Platform
-- Creator Identity Extension
-- Jalankan setelah migration Creator awal.

ALTER TABLE creator_profiles
    ADD COLUMN creator_code VARCHAR(30) NULL AFTER user_id,
    ADD COLUMN slug VARCHAR(80) NULL AFTER creator_code,
    ADD COLUMN creator_type VARCHAR(50) NOT NULL DEFAULT 'individual' AFTER display_name,
    ADD COLUMN creator_level VARCHAR(50) NOT NULL DEFAULT 'emerging' AFTER creator_type,
    ADD COLUMN creator_rank INT UNSIGNED NOT NULL DEFAULT 0 AFTER creator_level,
    ADD COLUMN verification_status VARCHAR(50) NOT NULL DEFAULT 'unverified' AFTER creator_rank,
    ADD COLUMN avatar_url VARCHAR(255) NOT NULL DEFAULT '' AFTER location,
    ADD COLUMN cover_image_url VARCHAR(255) NOT NULL DEFAULT '' AFTER avatar_url,
    ADD COLUMN seo_title VARCHAR(150) NOT NULL DEFAULT '' AFTER public_email,
    ADD COLUMN seo_description VARCHAR(255) NOT NULL DEFAULT '' AFTER seo_title;

UPDATE creator_profiles
SET creator_code = CONCAT('CR-', LPAD(id, 6, '0'))
WHERE creator_code IS NULL OR creator_code = '';

UPDATE creator_profiles
SET slug = handle
WHERE slug IS NULL OR slug = '';

UPDATE creator_profiles
SET verification_status = CASE
    WHEN status = 'active' THEN 'verified'
    WHEN status = 'rejected' THEN 'rejected'
    ELSE 'pending_review'
END
WHERE verification_status = 'unverified';

ALTER TABLE creator_profiles
    MODIFY COLUMN creator_code VARCHAR(30) NOT NULL,
    MODIFY COLUMN slug VARCHAR(80) NOT NULL,
    ADD UNIQUE KEY uq_creator_profiles_creator_code (creator_code),
    ADD UNIQUE KEY uq_creator_profiles_slug (slug),
    ADD KEY idx_creator_profiles_verification_status (verification_status),
    ADD KEY idx_creator_profiles_creator_type (creator_type),
    ADD KEY idx_creator_profiles_creator_level (creator_level);

ALTER TABLE creator_portfolios
    ADD COLUMN portfolio_type VARCHAR(50) NOT NULL DEFAULT 'story' AFTER creator_id,
    ADD COLUMN organization VARCHAR(150) NOT NULL DEFAULT '' AFTER summary,
    ADD COLUMN issued_at DATE NULL AFTER organization,
    ADD COLUMN ended_at DATE NULL AFTER issued_at,
    ADD KEY idx_creator_portfolios_type (portfolio_type);

CREATE TABLE IF NOT EXISTS creator_categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_creator_categories_creator_name (creator_id, name),
    KEY idx_creator_categories_creator_id (creator_id),
    CONSTRAINT fk_creator_categories_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_skills (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(80) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_creator_skills_creator_name (creator_id, name),
    KEY idx_creator_skills_creator_id (creator_id),
    CONSTRAINT fk_creator_skills_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_achievements (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    issuer VARCHAR(150) NOT NULL DEFAULT '',
    description TEXT NOT NULL,
    achieved_at DATE NOT NULL,
    url VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_creator_achievements_creator_id (creator_id),
    KEY idx_creator_achievements_deleted_at (deleted_at),
    CONSTRAINT fk_creator_achievements_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_badges (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    badge_key VARCHAR(100) NOT NULL,
    badge_label VARCHAR(150) NOT NULL,
    assigned_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_creator_badges_creator_badge (creator_id, badge_key),
    KEY idx_creator_badges_creator_id (creator_id),
    CONSTRAINT fk_creator_badges_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_statistics (
    creator_id BIGINT UNSIGNED NOT NULL,
    followers_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    reads_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    listens_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    downloads_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    sponsor_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    donation_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    affiliate_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    revenue_minor BIGINT UNSIGNED NOT NULL DEFAULT 0,
    wallet_available_minor BIGINT UNSIGNED NOT NULL DEFAULT 0,
    wallet_pending_minor BIGINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (creator_id),
    CONSTRAINT fk_creator_statistics_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO creator_statistics (
    creator_id, followers_count, reads_count, listens_count, downloads_count, sponsor_count,
    donation_count, affiliate_count, revenue_minor, wallet_available_minor, wallet_pending_minor,
    created_at, updated_at
)
SELECT cp.id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NOW(), NOW()
FROM creator_profiles cp
WHERE NOT EXISTS (
    SELECT 1
    FROM creator_statistics cs
    WHERE cs.creator_id = cp.id
);

INSERT IGNORE INTO creator_categories (creator_id, name, sort_order, created_at)
SELECT id, category, 1, NOW()
FROM creator_profiles
WHERE category <> '';
