<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssociationRule;

class AssociationRuleInteractionSeeder extends Seeder
{
    public function run(): void
    {
        AssociationRule::query()->update([
            'view_count' => rand(10, 100),
            'order_count' => rand(1, 10),
            'like_count' => rand(5, 20),
        ]);
    }
}
