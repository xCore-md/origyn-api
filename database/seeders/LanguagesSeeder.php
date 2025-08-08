<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            // Major World Languages
            ['language' => 'English', 'code' => 'en', 'is_active' => true],
            ['language' => 'Spanish', 'code' => 'es', 'is_active' => true],
            ['language' => 'French', 'code' => 'fr', 'is_active' => true],
            ['language' => 'German', 'code' => 'de', 'is_active' => true],
            ['language' => 'Italian', 'code' => 'it', 'is_active' => true],
            ['language' => 'Portuguese', 'code' => 'pt', 'is_active' => true],
            ['language' => 'Russian', 'code' => 'ru', 'is_active' => true],
            ['language' => 'Chinese (Simplified)', 'code' => 'zh-cn', 'is_active' => true],
            ['language' => 'Chinese (Traditional)', 'code' => 'zh-tw', 'is_active' => true],
            ['language' => 'Japanese', 'code' => 'ja', 'is_active' => true],
            ['language' => 'Korean', 'code' => 'ko', 'is_active' => true],
            ['language' => 'Arabic', 'code' => 'ar', 'is_active' => true],
            ['language' => 'Hindi', 'code' => 'hi', 'is_active' => true],
            ['language' => 'Turkish', 'code' => 'tr', 'is_active' => true],
            ['language' => 'Polish', 'code' => 'pl', 'is_active' => true],
            ['language' => 'Dutch', 'code' => 'nl', 'is_active' => true],
            ['language' => 'Swedish', 'code' => 'sv', 'is_active' => true],
            ['language' => 'Norwegian', 'code' => 'no', 'is_active' => true],
            ['language' => 'Danish', 'code' => 'da', 'is_active' => true],
            ['language' => 'Finnish', 'code' => 'fi', 'is_active' => true],

            // Regional European Languages
            ['language' => 'Romanian', 'code' => 'ro', 'is_active' => true],
            ['language' => 'Hungarian', 'code' => 'hu', 'is_active' => true],
            ['language' => 'Czech', 'code' => 'cs', 'is_active' => true],
            ['language' => 'Slovak', 'code' => 'sk', 'is_active' => true],
            ['language' => 'Bulgarian', 'code' => 'bg', 'is_active' => true],
            ['language' => 'Croatian', 'code' => 'hr', 'is_active' => true],
            ['language' => 'Serbian', 'code' => 'sr', 'is_active' => true],
            ['language' => 'Slovenian', 'code' => 'sl', 'is_active' => true],
            ['language' => 'Lithuanian', 'code' => 'lt', 'is_active' => true],
            ['language' => 'Latvian', 'code' => 'lv', 'is_active' => true],
            ['language' => 'Estonian', 'code' => 'et', 'is_active' => true],
            ['language' => 'Greek', 'code' => 'el', 'is_active' => true],
            ['language' => 'Ukrainian', 'code' => 'uk', 'is_active' => true],
            ['language' => 'Belarusian', 'code' => 'be', 'is_active' => true],

            // Asian Languages
            ['language' => 'Thai', 'code' => 'th', 'is_active' => true],
            ['language' => 'Vietnamese', 'code' => 'vi', 'is_active' => true],
            ['language' => 'Indonesian', 'code' => 'id', 'is_active' => true],
            ['language' => 'Malay', 'code' => 'ms', 'is_active' => true],
            ['language' => 'Tagalog', 'code' => 'tl', 'is_active' => true],
            ['language' => 'Bengali', 'code' => 'bn', 'is_active' => true],
            ['language' => 'Tamil', 'code' => 'ta', 'is_active' => true],
            ['language' => 'Telugu', 'code' => 'te', 'is_active' => true],
            ['language' => 'Marathi', 'code' => 'mr', 'is_active' => true],
            ['language' => 'Gujarati', 'code' => 'gu', 'is_active' => true],
            ['language' => 'Punjabi', 'code' => 'pa', 'is_active' => true],
            ['language' => 'Urdu', 'code' => 'ur', 'is_active' => true],
            ['language' => 'Persian', 'code' => 'fa', 'is_active' => true],
            ['language' => 'Hebrew', 'code' => 'he', 'is_active' => true],

            // African Languages
            ['language' => 'Swahili', 'code' => 'sw', 'is_active' => true],
            ['language' => 'Afrikaans', 'code' => 'af', 'is_active' => true],
            ['language' => 'Amharic', 'code' => 'am', 'is_active' => true],
            ['language' => 'Hausa', 'code' => 'ha', 'is_active' => true],
            ['language' => 'Yoruba', 'code' => 'yo', 'is_active' => true],
            ['language' => 'Zulu', 'code' => 'zu', 'is_active' => true],

            // American Languages
            ['language' => 'Portuguese (Brazil)', 'code' => 'pt-br', 'is_active' => true],
            ['language' => 'Spanish (Mexico)', 'code' => 'es-mx', 'is_active' => true],
            ['language' => 'Spanish (Argentina)', 'code' => 'es-ar', 'is_active' => true],
            ['language' => 'French (Canada)', 'code' => 'fr-ca', 'is_active' => true],

            // Additional Languages
            ['language' => 'Catalan', 'code' => 'ca', 'is_active' => true],
            ['language' => 'Basque', 'code' => 'eu', 'is_active' => true],
            ['language' => 'Galician', 'code' => 'gl', 'is_active' => true],
            ['language' => 'Welsh', 'code' => 'cy', 'is_active' => true],
            ['language' => 'Irish', 'code' => 'ga', 'is_active' => true],
            ['language' => 'Icelandic', 'code' => 'is', 'is_active' => true],
            ['language' => 'Maltese', 'code' => 'mt', 'is_active' => true],
            ['language' => 'Macedonian', 'code' => 'mk', 'is_active' => true],
            ['language' => 'Albanian', 'code' => 'sq', 'is_active' => true],
            ['language' => 'Bosnian', 'code' => 'bs', 'is_active' => true],
            ['language' => 'Montenegrin', 'code' => 'cnr', 'is_active' => true],

            // Central Asian Languages
            ['language' => 'Kazakh', 'code' => 'kk', 'is_active' => true],
            ['language' => 'Uzbek', 'code' => 'uz', 'is_active' => true],
            ['language' => 'Kyrgyz', 'code' => 'ky', 'is_active' => true],
            ['language' => 'Tajik', 'code' => 'tg', 'is_active' => true],
            ['language' => 'Turkmen', 'code' => 'tk', 'is_active' => true],
            ['language' => 'Mongolian', 'code' => 'mn', 'is_active' => true],

            // Caucasus Languages
            ['language' => 'Georgian', 'code' => 'ka', 'is_active' => true],
            ['language' => 'Armenian', 'code' => 'hy', 'is_active' => true],
            ['language' => 'Azerbaijani', 'code' => 'az', 'is_active' => true],

            // Additional Asian Languages
            ['language' => 'Burmese', 'code' => 'my', 'is_active' => true],
            ['language' => 'Khmer', 'code' => 'km', 'is_active' => true],
            ['language' => 'Lao', 'code' => 'lo', 'is_active' => true],
            ['language' => 'Sinhala', 'code' => 'si', 'is_active' => true],
            ['language' => 'Nepali', 'code' => 'ne', 'is_active' => true],

            // Pacific Languages
            ['language' => 'Maori', 'code' => 'mi', 'is_active' => true],
            ['language' => 'Samoan', 'code' => 'sm', 'is_active' => true],
            ['language' => 'Tongan', 'code' => 'to', 'is_active' => true],
            ['language' => 'Fijian', 'code' => 'fj', 'is_active' => true],

            // Less common but notable languages
            ['language' => 'Esperanto', 'code' => 'eo', 'is_active' => false],
            ['language' => 'Latin', 'code' => 'la', 'is_active' => false],
            ['language' => 'Sanskrit', 'code' => 'sa', 'is_active' => false],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
