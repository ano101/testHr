<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.ru',
            'password' => bcrypt('password'),
        ]);

        $categories = [
            ['name' => 'Электроника', 'description' => 'Смартфоны, ноутбуки и другая техника'],
            ['name' => 'Одежда', 'description' => 'Мужская и женская одежда'],
            ['name' => 'Книги', 'description' => 'Художественная и техническая литература'],
            ['name' => 'Спорттовары', 'description' => 'Всё для занятий спортом'],
            ['name' => 'Продукты', 'description' => 'Продукты питания и напитки'],
        ];

        foreach ($categories as $data) {
            $cat = Category::create($data);

            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => $cat->name.' товар №'.$i,
                    'description' => 'Подробное описание товара '.$i.' из категории '.$cat->name,
                    'price' => rand(100, 10000) / 10,
                    'category_id' => $cat->id,
                ]);
            }
        }

        $this->command->info('Готово! Создано '.Category::count().' категорий и '.Product::count().' товаров');
    }
}
