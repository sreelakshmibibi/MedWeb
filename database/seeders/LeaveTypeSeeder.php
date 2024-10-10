<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create([
            'type' => 'Sick Leave',
            'description' => 'Leave for illness or medical reasons',
            'duration' => null,  // Specify duration if needed
            'duration_type' => null,  // Specify duration type if needed
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
        LeaveType::create([
            'type' => 'Casual Leave',
            'description' => 'Leave for personal reasons',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
        LeaveType::create([
            'type' => 'Maternity Leave',
            'description' => 'Leave for maternity purposes',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
        LeaveType::create([
            'type' => 'Paternity Leave',
            'description' => 'Leave for paternity purposes',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Partially Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Bereavement Leave',
            'description' => 'Leave due to a death in the family',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Study Leave',
            'description' => 'Leave for study purposes',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Annual Leave',
            'description' => 'Yearly leave for rest and relaxation',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Emergency Leave',
            'description' => 'Leave for emergency reasons',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Partially Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
        LeaveType::create([
            'type' => 'Unpaid Leave',
            'description' => 'Leave without pay',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
        LeaveType::create([
            'type' => 'Compassionate Leave',
            'description' => 'Leave due to critical family matters',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Adoption Leave',
            'description' => 'Leave for adopting a child',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Jury Duty Leave',
            'description' => 'Leave for attending jury duty',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Public Service Leave',
            'description' => 'Leave for public service engagements',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Sabbatical Leave',
            'description' => 'Extended leave for research or rest',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Military Leave',
            'description' => 'Leave for military service',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Marriage Leave',
            'description' => 'Leave for wedding purposes',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Relocation Leave',
            'description' => 'Leave for relocation purposes',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Voting Leave',
            'description' => 'Leave to participate in elections',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Not Paid',
            'employee_type_id' => '2',
            'status' => 'N'
        ]);
        LeaveType::create([
            'type' => 'Compensatory Leave',
            'description' => 'Leave in exchange for extra hours worked',
            'duration' => null,
            'duration_type' => null,
            'payment_status' => 'Paid',
            'employee_type_id' => '2',
            'status' => 'Y'
        ]);
    }
}
