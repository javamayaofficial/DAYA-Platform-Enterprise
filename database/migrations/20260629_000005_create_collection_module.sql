-- DAYA Platform
-- Collection Module Migration
-- Jalankan setelah migration Authentication, Creator, Creator Identity, dan Content.

CREATE TABLE IF NOT EXISTS collections (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    creator_id BIGINT UNSIGNED NOT NULL,
    collection_code VARCHAR(30) NOT NULL,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    cover_image_url VARCHAR(255) NOT NULL DEFAULT '',
    visibility VARCHAR(20) NOT NULL DEFAULT 'public',
    status VARCHAR(50) NOT NULL DEFAULT 'draft',
    published_at DATETIME NULL,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_collections_collection_code (collection_code),
    UNIQUE KEY uq_collections_slug (slug),
    KEY idx_collections_creator_id (creator_id),
    KEY idx_collections_status (status),
    KEY idx_collections_visibility (visibility),
    KEY idx_collections_deleted_at (deleted_at),
    CONSTRAINT fk_collections_creator FOREIGN KEY (creator_id) REFERENCES creator_profiles (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS collection_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    collection_id BIGINT UNSIGNED NOT NULL,
    content_id BIGINT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 1,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_collection_items_collection_id (collection_id),
    KEY idx_collection_items_content_id (content_id),
    KEY idx_collection_items_sort_order (sort_order),
    KEY idx_collection_items_deleted_at (deleted_at),
    CONSTRAINT fk_collection_items_collection FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE,
    CONSTRAINT fk_collection_items_content FOREIGN KEY (content_id) REFERENCES contents (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
