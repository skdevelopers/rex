<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all cash flows with associated customer and supplier details
        $cashFlows = CashFlow::with('customer', 'supplier')->get();

        // Return the index view with cash flows data
        return view('cash-flows.index', compact('cashFlows'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexJson(): JsonResponse
    {
        // Retrieve all customers
        $cashFlow = CashFlow::all();
        // Return customers data as JSON response
        return response()->json($cashFlow);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the create view for cash flow creation
        return view('cash-flows.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'dated' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'cash_receipts' => 'required|numeric',
            'cash_disbursements' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        // Create a new cash flow record
        $cashFlow = CashFlow::create($validatedData);

        // Redirect to the index route or show view with success message
        return redirect()->route('cash-flows.index')->with('success', 'Cash Flow created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashFlow $cashFlow)
    {
        // Load related customer and supplier data
        $cashFlow->load('customer', 'supplier');

        // Return the show view with cash flow details
        return view('cash-flows.show', compact('cashFlow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashFlow $cashFlow)
    {
        // Return the edit view with the specified cash flow data
        return view('cash-flows.edit', compact('cashFlow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashFlow $cashFlow)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'dated' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'cash_receipts' => 'required|numeric',
            'cash_disbursements' => 'required|numeric',
            // Add other validation rules as needed
        ]);

        // Update the cash flow record with validated data
        $cashFlow->update($validatedData);

        // Redirect to the show view with success message
        return redirect()->route('cash-flows.show', $cashFlow->id)->with('success', 'Cash Flow updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashFlow $cashFlow)
    {
        // Delete the cash flow record
        $cashFlow->delete();

        // Redirect to the index route with success message
        return redirect()->route('cash-flows.index')->with('success', 'Cash Flow deleted successfully.');
    }
}
