<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Customer;
use Database\Seeders\CategorySeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    /**
     * A basic feature test example.
     */
    public function test_customer_can_be_created(): void
    {
        $response = $this->post('/customers', [
            'name' => 'Acme Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => 'Some random description',
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response->assertOk();

        $response = $this->get('/customers');

        $response->assertSee('Acme Ltd');
    }

    public function test_customer_must_have_a_valid_category(): void
    {
        $response = $this->post('/customers', [
            'name' => 'Acme Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => 'Some random description',
            'category_id' => Category::latest('id')->first()->id + 1
        ]);

        $response->assertInvalid('category_id');

        $response = $this->get('/customers');

        $response->assertDontSee('Acme Ltd');
    }
    
    public function test_customer_reference_must_be_unique(): void
    {
        /**
         * Create first customer
         */
        $response = $this->post('/customers', [
            'name' => 'Acme Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => 'Some random description',
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response = $this->get('/customers');

        $response->assertSee('Acme Ltd');
        
        /**
         * Attempt to create a second customer with same reference - must be rejected
         */
        $response = $this->post('/customers', [
            'name' => 'Another Company Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => null,
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response->assertInvalid('reference');

        $response = $this->get('/customers');

        $response->assertDontSee('Another Company Ltd');

        /**
         * Now create a seconds customer with a different reference and attempt to 
         * update the second customer's reference to an existing customer reference - 
         * must be rejected.
         * 
         * This is to confirm that the uniqueness of reference is kept while updating as well
         */
        $response = $this->post('/customers', [
            'name' => 'Another Company Ltd',
            'reference' => 'CUST-02',
            'start_date' => now()->format('Y-m-d'),
            'description' => null,
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response->assertOk();

        $response = $this->get('/customers');

        $response->assertSee('Another Company Ltd');

        $response = $this->patch('/customers/'. Customer::whereName('Another Company Ltd')->first()->id, [
            'name' => 'Another Company Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => null,
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response->assertInvalid('reference');

        /**
         * Next let's confirm that when a customer is updated it's own reference is accepted in the update
         */

        $response = $this->patch('/customers/'. Customer::whereName('Another Company Ltd')->first()->id, [
            'name' => 'Another Company Ltd Renamed',
            'reference' => 'CUST-02',
            'start_date' => now()->format('Y-m-d'),
            'description' => null,
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response->assertOk();

        $response = $this->get('/customers');

        $response->assertSee('Another Company Ltd Renamed');
    }

    public function test_customer_can_be_deleted()
    {
        $response = $this->post('/customers', [
            'name' => 'Acme Ltd',
            'reference' => 'CUST-01',
            'start_date' => now()->format('Y-m-d'),
            'description' => 'Some random description',
            'category_id' => Category::whereName('Gold')->first()->id
        ]);

        $response = $this->get('/customers');

        $response->assertSee('Acme Ltd');

        $response = $this->delete('/customers/' . Customer::first()->id);

        $response->assertOk();

        $response = $this->get('/customers');

        $response->assertDontSee('Acme Ltd');
    }
}
