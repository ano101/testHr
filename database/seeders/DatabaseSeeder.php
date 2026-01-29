<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаём тестового пользователя
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Создаём категории
        $categories = [
            ['name' => 'Электроника', 'description' => 'Электронные устройства и гаджеты'],
            ['name' => 'Одежда', 'description' => 'Одежда и аксессуары'],
            ['name' => 'Книги', 'description' => 'Печатные и электронные книги'],
            ['name' => 'Спорт', 'description' => 'Спортивные товары и оборудование'],
            ['name' => 'Продукты', 'description' => 'Продукты питания'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Создаём 5 товаров для каждой категории
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => "{$category->name} - Товар {$i}",
                    'description' => "Описание товара {$i} в категории {$category->name}",
                    'price' => rand(100, 10000) / 10, // случайная цена от 10 до 1000
                    'category_id' => $category->id,
                ]);
            }
        }

        $this->command->info('Создано ' . Category::count() . ' категорий и ' . Product::count() . ' товаров.');
    }
}
