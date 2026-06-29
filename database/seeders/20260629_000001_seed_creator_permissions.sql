-- DAYA Platform
-- Creator Module Seeder
-- Jalankan setelah migration Authentication dan Creator.

INSERT IGNORE INTO permissions (slug, name, description, created_at, updated_at) VALUES
('creator.admin.view', 'View Creator Directory', 'Melihat daftar dan detail creator dari area admin', NOW(), NOW()),
('creator.admin.review', 'Review Creator Application', 'Melakukan review, approval, suspend, reject, dan revoke creator', NOW(), NOW());

INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.id, p.id, NOW()
FROM roles r
INNER JOIN permissions p ON p.slug IN (
    'creator.admin.view',
    'creator.admin.review'
)
WHERE r.slug IN ('super_admin', 'admin_yayasan')
AND NOT EXISTS (
    SELECT 1
    FROM role_permissions rp
    WHERE rp.role_id = r.id
      AND rp.permission_id = p.id
);
