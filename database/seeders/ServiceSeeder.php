<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            // خدمات رفع السرعة
            [
                'name' => 'رفع السرعة من 1 إلى 2 ميغابت',
                'description' => 'زيادة سرعة الإنترنت من 1 إلى 2 ميغابت لتصفح أفضل.',
                'price' => 2000,
                'type' => 'speed_upgrade',
            ],
            [
                'name' => 'رفع السرعة من 2 إلى 4 ميغابت',
                'description' => 'ترقية السرعة لتحميل أسرع وتجربة أفضل.',
                'price' => 3500,
                'type' => 'speed_upgrade',
            ],
            [
                'name' => 'رفع السرعة من 4 إلى 8 ميغابت',
                'description' => 'مناسبة للبث ومكالمات الفيديو.',
                'price' => 6000,
                'type' => 'speed_upgrade',
            ],
            [
                'name' => 'رفع السرعة من 8 إلى 16 ميغابت',
                'description' => 'إنترنت سريع للعائلة والاستخدام المتعدد.',
                'price' => 10000,
                'type' => 'speed_upgrade',
            ],
            [
                'name' => 'رفع السرعة من 16 إلى 32 ميغابت',
                'description' => 'أفضل أداء للتنزيل والألعاب.',
                'price' => 18000,
                'type' => 'speed_upgrade',
            ],

            // خدمات شحن الباقة
            [
                'name' => 'شحن باقة 5 جيجابايت',
                'description' => 'إضافة 5GB إلى رصيد الباقة الحالي.',
                'price' => 2500,
                'type' => 'addon_bundle',
            ],
            [
                'name' => 'شحن باقة 10 جيجابايت',
                'description' => 'إضافة 10GB لاستخدام إضافي.',
                'price' => 4500,
                'type' => 'addon_bundle',
            ],
            [
                'name' => 'شحن باقة 20 جيجابايت',
                'description' => 'شحن 20GB للتحميل والتصفح.',
                'price' => 8000,
                'type' => 'addon_bundle',
            ],
            [
                'name' => 'شحن باقة 50 جيجابايت',
                'description' => 'سعة كبيرة لتغطية كل احتياجاتك.',
                'price' => 15000,
                'type' => 'addon_bundle',
            ],

            // خدمة إضافية - تفعيل الصفر الدولي
            [
                'name' => 'تفعيل الصفر الدولي',
                'description' => 'تمكين الاتصال الدولي عن طريق فتح ميزة الاتصال برموز الدولة.',
                'price' => 500,
                'type' => 'misc',
            ],
        ]);
    }
}
