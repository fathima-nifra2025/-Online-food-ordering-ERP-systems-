<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    // Public / Customer Menu View
    public function publicMenu(Request $request)
    {
        $categories = Category::where('is_active', true)->withCount(['menuItems' => function($q) {
            $q->where('is_available', true);
        }])->get();

        $query = MenuItem::with('category')->where('is_available', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $menuItems = $query->orderBy('is_featured', 'desc')->latest()->paginate(12);

        return view('customer.menu', compact('categories', 'menuItems'));
    }

    // Admin Index
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = MenuItem::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability === '1');
        }

        $menuItems = $query->latest()->paginate(10);

        return view('admin.menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'prep_time_minutes' => 'required|integer|min:1|max:180',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
        }

        MenuItem::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'is_available' => $request->has('is_available') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
            'prep_time_minutes' => $validated['prep_time_minutes'],
            'stock_quantity' => $validated['stock_quantity'],
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item added successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'prep_time_minutes' => 'required|integer|min:1|max:180',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $imagePath = $menuItem->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
        }

        $menuItem->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'is_available' => $request->has('is_available') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
            'prep_time_minutes' => $validated['prep_time_minutes'],
            'stock_quantity' => $validated['stock_quantity'],
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update(['is_available' => !$menuItem->is_available]);
        $status = $menuItem->is_available ? 'available' : 'unavailable';
        return back()->with('success', "Item status set to {$status}.");
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted successfully.');
    }
}
