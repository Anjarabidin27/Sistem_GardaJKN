import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/variables.css',
                'resources/css/components.css',
                'resources/css/layout.css',
                'resources/css/admin.css',
                'resources/js/pages/admin-dashboard.js',
                'resources/css/pages/apply_pengurus.css',
                'resources/js/pages/apply_pengurus.js',
                'resources/css/pages/member.css',
                'resources/js/pages/member.js',
                'resources/js/pages/admin_approvals_pengurus.js',
                'resources/js/pages/admin_audit_logs_index.js',
                'resources/js/pages/admin_informations_index.js',
                'resources/js/pages/admin_members_index.js',
                'resources/js/pages/admin_bpjs_keliling_index.js',
                'resources/js/pages/admin_pil_index.js',
                'resources/css/pages/auth_admin_login.css',
                'resources/js/pages/auth_admin_login.js',
                'resources/css/pages/auth_login.css',
                'resources/js/pages/auth_login.js',
                'resources/css/pages/auth_register.css',
                'resources/js/pages/auth_register.js',
                'resources/css/auth.css',
                'resources/js/pages/common_settings.js',
                'resources/css/pages/member_informations_index.css',
                'resources/js/pages/member_informations_index.js',
                'resources/css/pages/pengurus_dashboard.css',
                'resources/js/pages/pengurus_dashboard.js',
                'resources/css/pages/pengurus_informations.css',
                'resources/js/pages/pengurus_informations.js',
                'resources/css/pages/pengurus_members.css',
                'resources/js/pages/pengurus_members.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
