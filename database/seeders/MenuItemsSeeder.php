<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superadmin = Role::create(['name' => 'Superadmin']);
        $admin = Role::create(['name' => 'Admin']);
        $doctor = Role::create(['name' => 'Doctor']);
        $nurse = Role::create(['name' => 'Nurse']);
        $reception = Role::create(['name' => 'Reception']);

        // Create permissions
        $permissions = [
            'view_dashboard',
            'appointments',
            'appointment create',
            'appointment reschedule',
            'appointment cancel',
            'treatments',
            'staff_list',
            'staff view',
            'staff create',
            'staff update',
            'staff delete',
            'staff change status',
            'leave',
            'leave apply',
            'leave approve',
            'technician',
            'technician_add',
            'technician_remove',
            'technician cost',
            'technician_edit',
            'patient_list',
            'patient_details',
            'patients',
            'patient create',
            'patient update',
            'patient delete',
            'patient view',
            'settings clinics',
            'settings departments',
            'settings diseases',
            'settings medicines',
            'settings treatment_cost',
            'settings combo_offers',
            'settings insurance',
            'settings treatment_plan',
            'view role',
            'create role',
            'update role',
            'delete role',
            'view permission',
            'create permission',
            'update permission',
            'delete permission',
            'view menu item',
            'create menu item',
            'update menu item',
            'delete menu item',
            'bill generate',
            'bill payment',
            'bill cancel',
            'bill view',
            'reports',
            'users',
            'settings db_backup',
            'order place',
            'order place store',
            'order track',
            'order cancel',
            'order deliver',
            'order repeat',
            'lab payment',
            'lab payment cancel',
            'lab payment store',
            'lab payment history',
            'supplier',
            'supplier add',
            'supplier edit',
            'supplier remove',

        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superadmin->syncPermissions($permissions);
        $adminPermissions = [
            'view_dashboard',
            'appointments',
            'appointment create',
            'appointment reschedule',
            'appointment cancel',
            'treatments',
            'staff_list',
            'staff view',
            'staff create',
            'staff update',
            'staff change status',
            'leave',
            'technician',
            'technician_add',
            'technician_remove',
            'technician_edit',
            'technician cost',
            'patient_list',
            'patient_details',
            'patients',
            'patient create',
            'patient update',
            'patient delete',
            'patient view',
            'settings clinics',
            'settings departments',
            'settings diseases',
            'settings medicines',
            'settings treatment_cost',
            'settings combo_offers',
            'settings insurance',
            'settings treatment_plan',
            'view role',
            'create role',
            'view permission',
            'create permission',
            'bill generate',
            'bill payment',
            'bill cancel',
            'bill view',
            'reports',
            'leave apply',
            'leave approve',
            'settings db_backup',
            'order place',
            'order place store',
            'order track',
            'order cancel',
            'order deliver',
            'order repeat',
            'lab payment',
            'lab payment cancel',
            'lab payment store',
            'lab payment history',
            'supplier',
            'supplier add',
            'supplier edit',
            'supplier delete',

        ];


        $admin->syncPermissions($adminPermissions);

        $doctor->syncPermissions([
            'view_dashboard',
            'appointments',
            'appointment create',
            'appointment reschedule',
            'appointment cancel',
            'treatments',
            'patient_list',
            'patient_details',
            'patients',
            'patient create',
            'patient update',
            'patient view',
            'settings medicines',
            'staff_list',
            'leave',
            'technician',
            'technician cost',
            'settings insurance',
            'settings treatment_plan',
            'leave apply',
            'order place',
            'order place store',
            'order track',
            'order cancel',
            'order deliver',
            'order repeat',

        ]);

        $nurse->syncPermissions([
            'view_dashboard',
            'appointments',
            'appointment create',
            'patients',
            'patient_list',
            'patient_details',
            'leave',
            'leave apply',
            'staff_list',
            'technician',
            
        ]);

        $reception->syncPermissions([
            'view_dashboard',
            'appointments',
            'appointment create',
            'appointment reschedule',
            'appointment cancel',
            'patient_list',
            'patients',
            'patient create',
            'bill payment',
            'bill view',
            'leave',
            'leave apply',
            'staff_list',
            'technician',
            
        ]);

        // Create menu items
        $dashboard = MenuItem::create([
            'name' => 'Dashboard',
            'url' => '/home',
            'route_name' => 'home',
            'icon' => 'icon-Layout-4-blocks',
            'order_no' => 1,
        ]);

        $appointments = MenuItem::create([
            'name' => 'Appointments',
            'url' => '/appointment',
            'route_name' => 'appointment',
            'icon' => 'fa-regular fa-calendar-check',
            'order_no' => 2,
        ]);

        $staffs = MenuItem::create([
            'name' => 'Staffs',
            'url' => '/staff_list',
            'route_name' => 'staff.staff_list',
            'icon' => 'fa-solid fa-user-nurse',
            'order_no' => 3,
        ]);

        $patients = MenuItem::create([
            'name' => 'Patients',
            'url' => '/patient_list',
            'route_name' => 'patient.patient_list',
            'icon' => 'fa-solid fa-hospital-user',
            'order_no' => 4,
        ]);

        $reports = MenuItem::create([
            'name' => 'Reports',
            'url' => '/report',
            'route_name' => 'report',
            'icon' => 'fa-solid fa-file-lines',
            'order_no' => 5,
        ]);

        $billing = MenuItem::create([
            'name' => 'Billing',
            'url' => '/billing',
            'route_name' => 'billing',
            'icon' => 'icon-Settings-1',
            'order_no' => 6,
        ]);

        $settings = MenuItem::create([
            'name' => 'Settings',
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-gear',
            'order_no' => 7,
        ]);

        $orders = MenuItem::create([
            'name' => 'Orders',
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-shopping-cart',
            'order_no' => 8,
        ]);
        $expenses = MenuItem::create([
            'name' => 'Expenses',
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-shopping-cart',
            'order_no' => 9,
        ]);

        $purchases = MenuItem::create([
            'name' => 'Purchases',
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-shopping-cart',
            'order_no' => 10,
        ]);

        $billingSubmenus = $billing->children()->createMany([
            ['name' => 'Treatment Billing', 'url' => '/billing', 'route_name' => 'billing', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Outstanding Billing', 'url' => '/duepayment', 'route_name' => 'duePayment', 'icon' => 'icon-Commit', 'order_no' => 2],
            ['name' => 'Lab Payments', 'url' => '/labPayment', 'route_name' => 'labPayment', 'icon' => 'icon-Commit', 'order_no' => 3],

        ]);

        $settingSubmenus = $settings->children()->createMany([
            ['name' => 'Clinics', 'url' => '/clinic', 'route_name' => 'settings.clinic', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Departments', 'url' => '/department', 'route_name' => 'settings.department', 'icon' => 'icon-Commit', 'order_no' => 2],
            ['name' => 'Medicines', 'url' => '/medicine', 'route_name' => 'settings.medicine', 'icon' => 'icon-Commit', 'order_no' => 3],
            ['name' => 'Treatment Cost', 'url' => '/treatment_cost', 'route_name' => 'settings.treatment_cost', 'icon' => 'icon-Commit', 'order_no' => 4],
            ['name' => 'Disease', 'url' => '/diseases', 'route_name' => 'settings.disease', 'icon' => 'icon-Commit', 'order_no' => 5],
            ['name' => 'Combo Offers', 'url' => '/combo_offer', 'route_name' => 'settings.combo_offer', 'icon' => 'icon-Commit', 'order_no' => 6],
            ['name' => 'Insurance', 'url' => '/insurance', 'route_name' => 'settings.insurance', 'icon' => 'icon-Commit', 'order_no' => 7],
            ['name' => 'Treatment Plan', 'url' => '/treatment_plan', 'route_name' => 'settings.treatment_plan', 'icon' => 'icon-Commit', 'order_no' => 8],
            ['name' => 'Roles and Permissions', 'url' => 'roles', 'route_name' => 'roles.index', 'icon' => 'icon-Commit', 'order_no' => 9],
            ['name' => 'DB Backup', 'url' => '/db_backup', 'route_name' => 'settings.db_backup', 'icon' => 'icon-Commit', 'order_no' => 10],
        ]);

        $settingSuperAdminSubmenus = $settings->children()->createMany([
            ['name' => 'Menu Items', 'url' => 'menu_items', 'route_name' => 'menu_items.index', 'icon' => 'icon-Commit', 'order_no' => 10],

        ]);

        $staffSubmenus = $staffs->children()->createMany([
            ['name' => 'Staff List', 'url' => '/staff_list', 'route_name' => 'staff.staff_list', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Leave', 'url' => '/leave', 'route_name' => 'leave', 'icon' => 'icon-Commit', 'order_no' => 2],
            ['name' => 'Technicians', 'url' => '/technicians', 'route_name' => 'technicians', 'icon' => 'icon-Commit', 'order_no' => 3],

        ]);

        $patientSubmenus = $patients->children()->createMany([
            ['name' => 'Patient List', 'url' => '/patient_list', 'route_name' => 'patient.patient_list', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Patient Details', 'url' => '#', 'route_name' => '#', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);

        $orderSubmenus = $orders->children()->createMany([
            ['name' => 'Place Order', 'url' => '/place_order', 'route_name' => 'order.place_order', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Track Order', 'url' => '/track_order', 'route_name' => 'order.track_order', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);

        $expenseSubmenus = $expenses->children()->createMany([
            ['name' => 'Category', 'url' => '/expenseCategory', 'route_name' => 'expenseCategory', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Add expense', 'url' => '/clinicExpense', 'route_name' => 'clinicExpense', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);
        $purchaseSubmenus = $purchases->children()->createMany([
            ['name' => 'Suppliers', 'url' => '/suppliers', 'route_name' => 'suppliers', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Purchases', 'url' => '/purchases', 'route_name' => 'purchases', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);

        // $appointmentSubmenus = $patients->children()->createMany([
        //     ['name' => 'Appointment List', 'url' => '/appointment', 'route_name' => 'appointment', 'icon' => 'icon-Commit', 'order_no' => 1],

        // ]);

        // Attach roles to menu items
        $dashboard->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $appointments->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $staffs->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);
        $patients->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $settings->roles()->attach([$superadmin->id, $admin->id]);
        $reports->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);
        $billing->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);
        $orders->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);
        $expenses->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);
        $purchases->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $reception->id, $nurse->id]);

        // Attach roles to submenu items
        foreach ($billingSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id]);
        }
        foreach ($settingSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id]);
        }
        foreach ($settingSuperAdminSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id]);
        }
        // Fetching the created submenus
        $staffList = $staffSubmenus->where('name', 'Staff List')->first();
        $leave = $staffSubmenus->where('name', 'Leave')->first();
        $technicians = $staffSubmenus->where('name', 'Technicians')->first();
        foreach ($patientSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        }
        foreach ($orderSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        }

        foreach ($expenseSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        }
        foreach ($purchaseSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        }

        // Fetching roles
        $admin = Role::findByName('Admin');
        $doctor = Role::findByName('Doctor');

        // Assigning roles to submenus
        $staffList->roles()->attach([$admin->id, $doctor->id]);
        $leave->roles()->attach([$admin->id, $doctor->id, $nurse->id, $reception->id]);
        $technicians->roles()->attach([$admin->id, $doctor->id, $nurse->id, $reception->id]);
    }
}
