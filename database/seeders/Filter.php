<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Material;
use App\Models\Style;
use App\Models\Medium;
use App\Models\Subject;

class Filter extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Seed Styles
        $styles = ['Figurative', 'Fine Art', 'Islamic Art', 'Modern', 'Impressionism', 'Abstract', 'Illustration', 'Minimalism', 'Pop Art', 'Realism', 'Contemporary', 'Conceptual', 'Geometric'];
        $styles_ar = ['تجريدي', 'فن جميل', 'فن إسلامي', 'حديث', 'انطباعية', 'تجريدية', 'رسوم توضيحية', 'تقليلية', 'فن البوب', 'واقعية', 'معاصر', 'مفهومي', 'هندسي'];
        foreach($styles as $index => $style){
            Style::create([
                'name'=> $style,
                'name_ar'=> $styles_ar[$index],
            ]);
        }
        // Seed Subjects
        $subjects = ['Social', 'Political', 'Conceptual', 'Women', 'Nature', 'People', 'Abstract', 'Landscape', 'Still Life', 'Cities', 'Religion', 'Calligraphy'];
        $subjects_ar = ['اجتماعي', 'سياسي', 'مفهومي', 'نساء', 'طبيعة', 'ناس', 'تجريدي', 'مناظر طبيعية', 'طبيعة صامتة', 'مدن', 'دين', 'خط عربي'];
        foreach($subjects as $index => $subject){
            Subject::create([
                'name'=> $subject,
                'name_ar'=> $subjects_ar[$index],
            ]);
        }

        // Seed Mediums
        $mediums = ['Oil', 'Acrylic', 'Watercolor', 'Ink', 'Spray Paint', 'Marker', 'Tempera', 'Charcoal', 'Pastel', 'Painting', 'Digital'];
        $mediums_ar = ['زيت', 'أكريليك', 'مائي', 'حبر', 'رذاذ الطلاء', 'علامة', 'تمبرا', 'فحم', 'باستيل', 'لوحة', 'رقمي'];
        foreach($mediums as $index => $medium){
            Medium::create([
                'name'=> $medium,
                'name_ar'=> $mediums_ar[$index],
            ]);
        }

        // Seed Materials
        $materials = ['Canvas', 'Paper', 'Wood', 'Marble', 'Stone', 'Glass', 'Acrylic', 'Panel'];
        $materials_ar = ['قماش', 'ورق', 'خشب', 'رخام', 'حجر', 'زجاج', 'أكريليك', 'لوحة'];
        foreach($materials as $index => $material){
            Material::create([
                'name'=> $material,
                'name_ar'=> $materials_ar[$index],
            ]);
        }


    }
}
