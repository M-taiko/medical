<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
        }

        if ($filter === 'low-stock') {
            $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
        } elseif ($filter === 'expired') {
            $query->where('expiry_date', '<', now());
        } elseif ($filter === 'expiring-soon') {
            $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();

        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count();
        $totalItems = Product::count();
        $totalValue = Product::selectRaw('SUM(stock_quantity * cost_price) as total')->value('total') ?? 0;

        return view('inventory.index', compact('products', 'lowStockCount', 'totalItems', 'totalValue', 'search', 'filter'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'sku'                   => 'required|string|max:100|unique:products,sku',
            'unit'                  => 'required|string|max:50',
            'stock_quantity'        => 'required|numeric|min:0',
            'low_stock_threshold'   => 'nullable|numeric|min:0',
            'cost_price'            => 'required|numeric|min:0',
            'expiry_date'           => 'nullable|date',
        ]);

        $validated['clinic_id'] = auth()->user()->clinic_id;
        $product = Product::create($validated);

        if ($validated['stock_quantity'] > 0) {
            StockMovement::create([
                'clinic_id'  => auth()->user()->clinic_id,
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'purchase-in',
                'quantity'   => $validated['stock_quantity'],
                'reference'  => 'Initial stock',
                'notes'      => 'Product created with initial stock',
            ]);
        }

        return redirect()->route('inventory.show', $product)->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $movements = $product->movements()->latest()->paginate(15);
        return view('inventory.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'sku'                   => "required|string|max:100|unique:products,sku,{$product->id}",
            'unit'                  => 'required|string|max:50',
            'low_stock_threshold'   => 'nullable|numeric|min:0',
            'cost_price'            => 'required|numeric|min:0',
            'expiry_date'           => 'nullable|date',
        ]);

        $product->update($validated);

        return redirect()->route('inventory.show', $product)->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();
        return redirect()->route('inventory.index')->with('success', "$name has been deleted.");
    }

    public function recordMovement(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type'     => 'required|in:purchase-in,manual-out',
            'quantity' => 'required|numeric|min:1',
            'notes'    => 'nullable|string|max:500',
        ]);

        $newQuantity = $validated['type'] === 'purchase-in'
            ? $product->stock_quantity + $validated['quantity']
            : max(0, $product->stock_quantity - $validated['quantity']);

        $product->update(['stock_quantity' => $newQuantity]);

        StockMovement::create([
            'clinic_id'  => auth()->user()->clinic_id,
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'type'       => $validated['type'],
            'quantity'   => $validated['quantity'],
            'notes'      => $validated['notes'] ?? null,
        ]);

        return redirect()->route('inventory.show', $product)->with('success', 'Stock movement recorded!');
    }
}
