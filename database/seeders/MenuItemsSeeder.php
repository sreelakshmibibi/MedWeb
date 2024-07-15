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
            'manage_appointments',
            'staff_list',
            'staff_details',
            'patient_list',
            'patient_details',
            'patients',
            'clinics',
            'departments',
            'medicines',
            'treatment_cost',
            'combo_offers',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superadmin->syncPermissions($permissions);

        $admin->syncPermissions($permissions);

        $doctor->syncPermissions([
            'view_dashboard',
            'manage_appointments',
            'patient_list',
            'patient_details',
            'patients',
            'medicines',
            'staff_list',
        ]);

        $nurse->syncPermissions([
            'view_dashboard',
            'patient_list',
            'patient_details',
        ]);

        $reception->syncPermissions([
            'view_dashboard',
            'manage_appointments',
            'patient_list',
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
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-hospital-user',
            'order_no' => 4,
        ]);

        $reports = MenuItem::create([
            'name' => 'Reports',
            'url' => '#',
            'route_name' => '#',
            'icon' => 'fa-solid fa-file-lines',
            'order_no' => 5,
        ]);

        $billing = MenuItem::create([
            'name' => 'Billing',
            'url' => '#',
            'route_name' => '#',
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

        $settingSubmenus = $settings->children()->createMany([
            ['name' => 'Clinics', 'url' => '/clinic', 'route_name' => 'settings.clinic', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Departments', 'url' => '/department', 'route_name' => 'settings.department', 'icon' => 'icon-Commit', 'order_no' => 2],
            ['name' => 'Medicines', 'url' => '/medicine', 'route_name' => 'settings.medicine', 'icon' => 'icon-Commit', 'order_no' => 3],
            ['name' => 'Treatment Cost', 'url' => '/treatment_cost', 'route_name' => 'settings.treatment_cost', 'icon' => 'icon-Commit', 'order_no' => 4],
            ['name' => 'Disease', 'url' => '/diseases', 'route_name' => 'settings.disease', 'icon' => 'icon-Commit', 'order_no' => 5],
            ['name' => 'Combo Offers', 'url' => '/combo_offer', 'route_name' => 'settings.combo_offer', 'icon' => 'icon-Commit', 'order_no' => 6],
        ]);

        $staffSubmenus = $staffs->children()->createMany([
            ['name' => 'Staff List', 'url' => '/staff_list', 'route_name' => 'staff.staff_list', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Staff Details', 'url' => '#', 'route_name' => '#', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);

        $patientSubmenus = $patients->children()->createMany([
            ['name' => 'Patient List', 'url' => '/patient_list', 'route_name' => 'patient.patient_list', 'icon' => 'icon-Commit', 'order_no' => 1],
            ['name' => 'Patient Details', 'url' => '#', 'route_name' => '#', 'icon' => 'icon-Commit', 'order_no' => 2],
        ]);

        // $appointmentSubmenus = $patients->children()->createMany([
        //     ['name' => 'Appointment List', 'url' => '/appointment', 'route_name' => 'appointment', 'icon' => 'icon-Commit', 'order_no' => 1],

        // ]);

        // Attach roles to menu items
        $dashboard->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $appointments->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $staffs->roles()->attach([$superadmin->id, $admin->id, $doctor->id]);
        $patients->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        $settings->roles()->attach([$superadmin->id, $admin->id]);
        $reports->roles()->attach([$superadmin->id, $admin->id]);
        $billing->roles()->attach([$superadmin->id, $admin->id]);
        // Attach roles to submenu items
        foreach ($settingSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id]);
        }
        // Fetching the created submenus
        $staffList = $staffSubmenus->where('name', 'Staff List')->first();
        $staffDetails = $staffSubmenus->where('name', 'Staff Details')->first();
        foreach ($patientSubmenus as $submenu) {
            $submenu->roles()->attach([$superadmin->id, $admin->id, $doctor->id, $nurse->id, $reception->id]);
        }

        // Fetching roles
        $admin = Role::findByName('Admin');
        $doctor = Role::findByName('Doctor');

        // Assigning roles to submenus
        $staffList->roles()->attach([$admin->id, $doctor->id]);
        $staffDetails->roles()->attach($admin->id);
    }
}
