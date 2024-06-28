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


    public function splitNames($name)
    {
        $split_name = explode('<br>', $name);
        return $split_name;
    }

    // Other common methods can be added here
}
