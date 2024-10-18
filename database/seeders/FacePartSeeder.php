<?php

namespace Database\Seeders;

use App\Models\FacePart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('face_parts')->delete();
        FacePart::create(['face_part' => 'Forehead']);
        FacePart::create(['face_part' => 'Upper lip']);
        FacePart::create(['face_part' => 'Lower lip']);
        FacePart::create(['face_part' => 'Cheeks']);
        FacePart::create(['face_part' => 'Chin']);
        FacePart::create(['face_part' => 'Nose']);
        FacePart::create(['face_part' => 'Jawline']);
        FacePart::create(['face_part' => 'Temples']);
        FacePart::create(['face_part' => 'Ears']);
        FacePart::create(['face_part' => 'Neck']);
        FacePart::create(['face_part' => 'Hairline']);
        FacePart::create(['face_part' => 'Eyes']);
        FacePart::create(['face_part' => 'Eyebrows']);
        FacePart::create(['face_part' => 'Eyelids']);
        FacePart::create(['face_part' => 'Forehead lines']);
        FacePart::create(['face_part' => 'Marionette lines']);
        FacePart::create(['face_part' => 'Nasolabial folds']);

    }
}
