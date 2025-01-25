<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\GeneralLedgerTrait;

class SalesController extends Controller
{
    use GeneralLedgerTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::all();
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        // Fetch the supplier
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        // Loop through products and create sales
        foreach ($validated['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $quantity = $productData['quantity'];
            $unitPrice = $productData['unit_price'];

            // Record the sale and generate GL entries
            $this->recordSaleTransaction($product, $quantity, $unitPrice, auth()->user());
        }

        return redirect()->route('sales.index')->with('success', 'Sales recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        // Update logic here...
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    /**
     * Get all suppliers for the supplier dropdown.
     *
     * @return JsonResponse
     */
    public function getSuppliers(): JsonResponse
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    /**
     * Search for products based on a query.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->get();

        return response()->json($products);
    }
}
