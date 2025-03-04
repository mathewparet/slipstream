<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $query = Customer::query();

        if($request->has('query') && !blank($request->get('query')))
            $query = $query->search($request->get('query'));
        
        if($request->has("category_id") && !blank($request->category_id))
            $query = $query->forCategory($request->category_id);

        $customers = $query->get()->all();

        $categories = Category::all();

        return Inertia::render('Customers/Index', compact('customers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->safe()->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->safe()->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
