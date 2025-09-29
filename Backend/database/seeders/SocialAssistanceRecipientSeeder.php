<?php

namespace Database\Seeders;

use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Database\Factories\SocialAssistanceRecipientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialAssistances = SocialAssistance::all();
        $headOfFamilies = HeadOfFamily::all();

        foreach($socialAssistances as $sa){
            foreach($headOfFamilies as $hof){
                SocialAssistanceRecipient::factory()->create([
                    'head_of_family_id' => $hof->id,
                    'social_assistance_id' => $sa->id
                ]);
            }
        }
    }
}
