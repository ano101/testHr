<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Ноутбук Lenovo',
            'description' => 'Мощный ноутбук для работы и игр',
            'price' => 45990.50,
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/products', $productData);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'Ноутбук Lenovo',
            'description' => 'Мощный ноутбук для работы и игр',
            'price' => 45990.50,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Ноутбук Lenovo',
            'price' => 45990.50,
            'category_id' => $category->id,
        ]);
    }
}
