<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create( ['type' => 'Sick Leave', 'description' => 'Leave for illness or medical reasons', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Casual Leave', 'description' => 'Leave for personal reasons', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Maternity Leave', 'description' => 'Leave for maternity purposes', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Paternity Leave', 'description' => 'Leave for paternity purposes', 'payment_status' => 'Partially Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Bereavement Leave', 'description' => 'Leave due to a death in the family', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Study Leave', 'description' => 'Leave for study purposes', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Annual Leave', 'description' => 'Yearly leave for rest and relaxation', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Emergency Leave', 'description' => 'Leave for emergency reasons', 'payment_status' => 'Partially Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Unpaid Leave', 'description' => 'Leave without pay', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Compassionate Leave', 'description' => 'Leave due to critical family matters', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create(['type' => 'Adoption Leave', 'description' => 'Leave for adopting a child', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Jury Duty Leave', 'description' => 'Leave for attending jury duty', 'payment_status' => 'Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Public Service Leave', 'description' => 'Leave for public service engagements', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Sabbatical Leave', 'description' => 'Extended leave for research or rest', 'payment_status' => 'Not Paid', 'status' => 'Y']);
        LeaveType::create( ['type' => 'Military Leave', 'description' => 'Leave for military service', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create( ['type' => 'Marriage Leave', 'description' => 'Leave for wedding purposes', 'payment_status' => 'Not Paid', 'status' => 'Y'] );
        LeaveType::create(['type' => 'Relocation Leave', 'description' => 'Leave for relocation purposes', 'payment_status' => 'Not Paid', 'status' => 'Y']  );
        LeaveType::create( ['type' => 'Voting Leave', 'description' => 'Leave to  participate in elections', 'payment_status' => 'Not Paid', 'status' => 'Y']);
        LeaveType::create( ['type' => 'Compensatory Leave', 'description' => 'Leave in exchange for extra hours worked', 'payment_status' => 'Paid', 'status' => 'Y']);
       
    }
}
