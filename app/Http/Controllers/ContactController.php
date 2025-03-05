<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer)
    {
        $contacts = $customer->contacts;

        return $contacts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request, Customer $customer)
    {
        $customer->contacts()->create($request->safe()->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Customer $customer, Contact $contact)
    {
        $contact->update($request->safe()->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, Contact $contact)
    {
        $contact->delete();
    }
}
