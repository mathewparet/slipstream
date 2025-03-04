<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

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
}
