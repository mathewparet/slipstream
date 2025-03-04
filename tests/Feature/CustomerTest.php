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
}
