<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MenuItemRequest;
use App\Models\MenuItem;
use App\Models\Role;

class MenuItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view menu item', ['only' => ['index']]);
        $this->middleware('permission:create menu item', ['only' => ['create', 'store']]);
        $this->middleware('permission:update menu item', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete menu item', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Fetch all menu items ordered by 'order_no'
        $menuItems = MenuItem::orderBy('order_no')->get();

        // Organize menu items into parent-child relationships
        $menuItemsTree = $this->buildMenuTree($menuItems);

        return view('settings.menu_items.index', compact('menuItemsTree'));
    }

    // Helper function to build a hierarchical menu tree
    protected function buildMenuTree($menuItems, $parentId = null)
    {
        $branch = $menuItems->where('parent_id', $parentId);
        foreach ($branch as $key => $item) {
            $children = $this->buildMenuTree($menuItems, $item->id);
            if ($children->count()) {
                $item->children = $children;
            }
        }

        return $branch;
    }

    public function create()
    {
        $roles = Role::all();
        $menuItems = MenuItem::whereNull('parent_id')->get();

        return view('settings.menu_items.create', compact('roles', 'menuItems'));
    }

    public function store(MenuItemRequest $request)
    {

        $menuItem = MenuItem::create($request->except('roles'));

        $menuItem->roles()->sync($request->roles);

        return redirect('menu_items')->with('status', 'Menu item created successfully');
    }

    public function edit(MenuItem $menuItem)
    {
        $roles = Role::all();
        $selectedRoles = $menuItem->roles->pluck('id')->toArray();
        $menuItems = MenuItem::whereNull('parent_id')->get();

        return view('settings.menu_items.edit', compact('menuItem', 'roles', 'selectedRoles', 'menuItems'));
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {

        $menuItem->update($request->except('roles'));

        $menuItem->roles()->sync($request->roles);

        return redirect('menu_items')->with('status', 'Menu item updated successfully');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect('menu_items')->with('status', 'Menu item deleted successfully');
    }
}
