<?php

namespace Database\Seeders;

use App\Models\Development;
use App\Models\DevelopmentApplicant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevelopmentApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developments = Development::all();
        $users = User::all();

        foreach($developments as $d){
            foreach($users as $u){
                DevelopmentApplicant::factory()->create([
                    'user_id' => $u->id,
                    'development_id' => $d->id
                ]);
            }
        }
    }
}
