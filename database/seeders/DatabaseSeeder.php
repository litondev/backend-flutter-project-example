<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Product
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<10;$i++){
            User::create([
                "name" => "user".$i,
                "email" => "user".$i."@gmail.com",
                "password" => \Hash::make("12345678")            
            ]);
        }

        for($i=0;$i<100;$i++){
            Product::create([
                "user_id" => 1,
                "title" => "product".$i,
                "stock" => rand(0,100),
                "price" => rand(1000,99999),                
            ]);
        }
    }
}
