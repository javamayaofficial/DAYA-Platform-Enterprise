-- DAYA Platform
-- Content Module Seeder
-- Jalankan setelah migration Authentication dan Content.

INSERT IGNORE INTO permissions (slug, name, description, created_at, updated_at) VALUES
('content.admin.view', 'View Content Directory', 'Melihat daftar dan detail content dari area admin', NOW(), NOW()),
('content.admin.review', 'Review Content', 'Melakukan review dan moderasi content creator', NOW(), NOW());

INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.id, p.id, NOW()
FROM roles r
INNER JOIN permissions p ON p.slug IN (
    'content.admin.view',
    'content.admin.review'
)
WHERE r.slug IN ('super_admin', 'admin_yayasan')
AND NOT EXISTS (
    SELECT 1
    FROM role_permissions rp
    WHERE rp.role_id = r.id
      AND rp.permission_id = p.id
);
