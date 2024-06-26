<?php

namespace App\Services;

use App\Models\MenuItem;

class CommonService
{
    public function getMenuItems()
    {
        return MenuItem::whereNull('parent_id')
            ->where('status', 'Y')
            ->with(['children' => function ($query) {
                $query->where('status', 'Y')
                    ->orderBy('order_no');
            }])
            ->orderBy('order_no')
            ->get();
    }

    // Other common methods can be added here
}
