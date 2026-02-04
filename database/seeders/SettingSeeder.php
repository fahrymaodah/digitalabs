<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Digitalabs', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Belajar Skill Digital dari Ahlinya', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Platform kursus online untuk belajar video editing, coding, dan skill digital lainnya', 'type' => 'text'],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'hello@digitalabs.id', 'type' => 'text'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '+62 812-3456-7890', 'type' => 'text'],
            ['group' => 'general', 'key' => 'contact_address', 'value' => 'Jakarta, Indonesia', 'type' => 'text'],

            // Social Media
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/digitalabs', 'type' => 'text'],
            ['group' => 'social', 'key' => 'youtube', 'value' => 'https://youtube.com/@digitalabs', 'type' => 'text'],
            ['group' => 'social', 'key' => 'tiktok', 'value' => 'https://tiktok.com/@digitalabs', 'type' => 'text'],
            ['group' => 'social', 'key' => 'twitter', 'value' => '', 'type' => 'text'],

            // Payment
            ['group' => 'payment', 'key' => 'duitku_merchant_code', 'value' => '', 'type' => 'text'],
            ['group' => 'payment', 'key' => 'duitku_api_key', 'value' => '', 'type' => 'text'],
            ['group' => 'payment', 'key' => 'duitku_sandbox', 'value' => '1', 'type' => 'boolean'],

            // Affiliate
            ['group' => 'affiliate', 'key' => 'affiliate_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'affiliate', 'key' => 'default_commission_rate', 'value' => '10', 'type' => 'integer'],
            ['group' => 'affiliate', 'key' => 'minimum_payout', 'value' => '100000', 'type' => 'integer'],
            ['group' => 'affiliate', 'key' => 'cookie_days', 'value' => '30', 'type' => 'integer'],

            // Homepage
            ['group' => 'homepage', 'key' => 'hero_title', 'value' => 'Kuasai Skill Digital yang Dibutuhkan Industri', 'type' => 'text'],
            ['group' => 'homepage', 'key' => 'hero_subtitle', 'value' => 'Belajar dari praktisi berpengalaman dengan kurikulum terstruktur dan project nyata', 'type' => 'text'],
            ['group' => 'homepage', 'key' => 'hero_cta_text', 'value' => 'Lihat Kursus', 'type' => 'text'],
            ['group' => 'homepage', 'key' => 'hero_cta_url', 'value' => '/courses', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
