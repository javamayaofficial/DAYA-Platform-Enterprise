-- DAYA Platform
-- Collection Module Seeder
-- Jalankan setelah migration Authentication dan Collection.

INSERT IGNORE INTO permissions (slug, name, description, created_at, updated_at) VALUES
('collection.admin.view', 'View Collection Directory', 'Melihat daftar dan detail collection dari area admin', NOW(), NOW());

INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.id, p.id, NOW()
FROM roles r
INNER JOIN permissions p ON p.slug IN (
    'collection.admin.view'
)
WHERE r.slug IN ('super_admin', 'admin_yayasan')
AND NOT EXISTS (
    SELECT 1
    FROM role_permissions rp
    WHERE rp.role_id = r.id
      AND rp.permission_id = p.id
);
