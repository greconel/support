<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // permissions
        $permissions = [
            'view client zone', 'view ampp zone', 'view admin zone',
            'view logs', 'view system log', 'view login logs',
            'artisan console',
            'api documentation',
            'view all help messages', 'view my help messages',
            'view gdpr audits', 'create gdpr audits', 'edit gdpr audits', 'delete gdpr audits',
            'view gdpr checklists', 'edit gdpr checklists',
            'view gdpr messages', 'create gdpr messages', 'edit gdpr messages', 'delete gdpr messages',
            'view gdpr registers', 'create gdpr registers', 'edit gdpr registers', 'delete gdpr registers',
            'view qr codes', 'create qr codes', 'edit qr codes', 'delete qr codes',
            'view connections', 'edit connections', 'create connections',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
            'view passport clients', 'create passport clients', 'edit passport clients', 'delete passport clients',
            'view users', 'create users', 'edit users', 'delete users', 'view user files', 'impersonate users',
            'view clients', 'create clients', 'edit clients', 'delete clients', 'manage files for clients',
            'view client contacts', 'create client contacts', 'edit client contacts', 'delete client contacts', 'view client contact files',
            'view time registrations', 'create time registrations', 'edit time registrations', 'delete time registrations', 'view time registration files', 'view other users time registrations',
            'view projects', 'create projects', 'edit projects', 'delete projects', 'manage files for projects', 'view project reports', 'manage emails for projects',
            'view quotations', 'create quotations', 'edit quotations', 'delete quotations', 'manage files for quotations',
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices', 'manage files for invoices',
            'view billing reports',
            'view products', 'create products', 'edit products', 'delete products',
            'view suppliers', 'create suppliers', 'edit suppliers', 'delete suppliers',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses', 'change status for expenses', 'manage files for expenses',
            'import releases', 'update releases',
            'view deals', 'create deals', 'edit deals', 'delete deals', 'manage files for deals',
            'view project activities', 'create project activities', 'edit project activities', 'delete project activities',
            'view implementations', 'manage implementations',
            'view helpdesk', 'view tickets', 'create tickets', 'edit tickets', 'delete tickets',
            'assign tickets', 'close tickets', 'manage ticket labels',
            'manage ai skill', 'view ai corrections', 'export ai corrections',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // roles
        $roles = [
            'super admin',
            'ampp user',
            'client',
            'helpdesk agent',
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }
    }
}
