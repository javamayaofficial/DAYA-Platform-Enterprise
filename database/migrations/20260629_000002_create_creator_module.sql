-- DAYA Platform
-- Creator Module Migration
-- Jalankan setelah migration Authentication.

CREATE TABLE IF NOT EXISTS creator_profiles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    handle VARCHAR(30) NOT NULL,
    display_name VARCHAR(150) NOT NULL,
    tagline VARCHAR(255) NOT NULL DEFAULT '',
    bio TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL DEFAULT '',
    website_url VARCHAR(255) NOT NULL DEFAULT '',
    public_email VARCHAR(190) NOT NULL DEFAULT '',
    status ENUM('draft', 'pending_review', 'active', 'rejected', 'suspended', 'revoked') NOT NULL DEFAULT 'draft',
    kyc_status ENUM('unsubmitted', 'pending_review', 'verified', 'rejected') NOT NULL DEFAULT 'unsubmitted',
    public_page_enabled TINYINT(1) NOT NULL DEFAULT 0,
    allow_public_contact TINYINT(1) NOT NULL DEFAULT 0,
    show_portfolio_publicly TINYINT(1) NOT NULL DEFAULT 0,
    profile_view_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    approved_at DATETIME NULL,
    approved_by_user_id BIGINT UNSIGNED NULL,
    rejected_at DATETIME NULL,
    rejected_by_user_id BIGINT UNSIGNED NULL,
    review_notes TEXT NULL,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_creator_profiles_user_id (user_id),
    UNIQUE KEY uq_creator_profiles_handle (handle),
    KEY idx_creator_profiles_status (status),
    KEY idx_creator_profiles_category (category),
    KEY idx_creator_profiles_deleted_at (deleted_at),
    CONSTRAINT fk_creator_profiles_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_creator_profiles_approved_by FOREIGN KEY (approved_by_user_id) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT fk_creator_profiles_rejected_by FOREIGN KEY (rejected_by_user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_applications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    status ENUM('draft', 'pending_review', 'active', 'rejected', 'suspended', 'revoked') NOT NULL DEFAULT 'pending_review',
    application_note TEXT NOT NULL,
    kyc_full_name VARCHAR(190) NOT NULL,
    kyc_document_type VARCHAR(100) NOT NULL,
    kyc_document_number VARCHAR(150) NOT NULL,
    kyc_document_url VARCHAR(255) NOT NULL DEFAULT '',
    submitted_at DATETIME NOT NULL,
    reviewed_at DATETIME NULL,
    reviewed_by_user_id BIGINT UNSIGNED NULL,
    review_notes TEXT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_creator_applications_creator_id (creator_id),
    KEY idx_creator_applications_status (status),
    CONSTRAINT fk_creator_applications_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE,
    CONSTRAINT fk_creator_applications_reviewed_by FOREIGN KEY (reviewed_by_user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_social_links (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_creator_social_links_creator_id (creator_id),
    KEY idx_creator_social_links_deleted_at (deleted_at),
    CONSTRAINT fk_creator_social_links_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS creator_portfolios (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    summary TEXT NOT NULL,
    url VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255) NOT NULL DEFAULT '',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_creator_portfolios_creator_id (creator_id),
    KEY idx_creator_portfolios_deleted_at (deleted_at),
    CONSTRAINT fk_creator_portfolios_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
