<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    const MENU_TYPES = [
        'utility_bar' => 'Top Utility Bar',
        'header'      => 'Header Navigation',
        'footer'      => 'Footer Columns',
        'social'      => 'Social Media',
    ];

    public function index()
    {
        $schoolId = 1;
        $menus = DB::table('menu_items')
            ->where('school_id', $schoolId)
            ->orderByRaw("FIELD(menu_type, 'utility_bar','header','footer','social')")
            ->orderBy('order')
            ->get();

        $grouped = [];
        foreach (array_keys(self::MENU_TYPES) as $type) {
            $grouped[$type] = [];
        }
        foreach ($menus as $m) {
            if (isset($grouped[$m->menu_type])) {
                $grouped[$m->menu_type][] = $m;
            }
        }
        foreach (array_keys($grouped) as $type) {
            $grouped[$type] = $this->buildTree($grouped[$type]);
        }

        return view('menu-manage.index', compact('grouped'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_type' => 'required|in:utility_bar,header,footer,social',
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'target' => 'nullable|in:_self,_blank',
            'parent_id' => 'nullable|integer',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'css_class' => 'nullable|string|max:255',
            'mega_columns' => 'nullable|integer|min:1|max:6',
            'permissions' => 'nullable|string|max:255',
        ]);

        $validated['school_id'] = 1;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['target'] = $validated['target'] ?? '_self';
        // Map footer_column to mega_columns
        if ($request->filled('footer_column')) {
            $validated['mega_columns'] = $request->footer_column;
        }
        if (!isset($validated['order'])) {
            $validated['order'] = DB::table('menu_items')
                ->where('school_id', 1)->where('menu_type', $validated['menu_type'])->count();
        }

        DB::table('menu_items')->insert($validated);

        return redirect()->route('menu-manage.index')->with('success', 'Menu item created');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'menu_type' => 'required|in:utility_bar,header,footer,social',
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'target' => 'nullable|in:_self,_blank',
            'parent_id' => 'nullable|integer',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'css_class' => 'nullable|string|max:255',
            'mega_columns' => 'nullable|integer|min:1|max:6',
            'permissions' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['target'] = $validated['target'] ?? '_self';
        if ($request->filled('footer_column')) {
            $validated['mega_columns'] = $request->footer_column;
        }

        DB::table('menu_items')->where('id', $id)->where('school_id', 1)->update($validated);

        return redirect()->route('menu-manage.index')->with('success', 'Menu item updated');
    }

    public function destroy(int $id)
    {
        DB::table('menu_items')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->route('menu-manage.index')->with('success', 'Menu item deleted');
    }

    private function buildTree($items, $parentId = null)
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildTree($items, $item->id);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }
}
