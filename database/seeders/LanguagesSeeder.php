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
            ['language' => 'English', 'code' => 'en', 'emoji' => '🇺🇸', 'is_active' => true],
            ['language' => 'Spanish', 'code' => 'es', 'emoji' => '🇪🇸', 'is_active' => true],
            ['language' => 'French', 'code' => 'fr', 'emoji' => '🇫🇷', 'is_active' => true],
            ['language' => 'German', 'code' => 'de', 'emoji' => '🇩🇪', 'is_active' => true],
            ['language' => 'Italian', 'code' => 'it', 'emoji' => '🇮🇹', 'is_active' => true],
            ['language' => 'Portuguese', 'code' => 'pt', 'emoji' => '🇵🇹', 'is_active' => true],
            ['language' => 'Russian', 'code' => 'ru', 'emoji' => '🇷🇺', 'is_active' => true],
            ['language' => 'Chinese (Simplified)', 'code' => 'zh-cn', 'emoji' => '🇨🇳', 'is_active' => true],
            ['language' => 'Chinese (Traditional)', 'code' => 'zh-tw', 'emoji' => '🇹🇼', 'is_active' => true],
            ['language' => 'Japanese', 'code' => 'ja', 'emoji' => '🇯🇵', 'is_active' => true],
            ['language' => 'Korean', 'code' => 'ko', 'emoji' => '🇰🇷', 'is_active' => true],
            ['language' => 'Arabic', 'code' => 'ar', 'emoji' => '🇸🇦', 'is_active' => true],
            ['language' => 'Hindi', 'code' => 'hi', 'emoji' => '🇮🇳', 'is_active' => true],
            ['language' => 'Turkish', 'code' => 'tr', 'emoji' => '🇹🇷', 'is_active' => true],
            ['language' => 'Polish', 'code' => 'pl', 'emoji' => '🇵🇱', 'is_active' => true],
            ['language' => 'Dutch', 'code' => 'nl', 'emoji' => '🇳🇱', 'is_active' => true],
            ['language' => 'Swedish', 'code' => 'sv', 'emoji' => '🇸🇪', 'is_active' => true],
            ['language' => 'Norwegian', 'code' => 'no', 'emoji' => '🇳🇴', 'is_active' => true],
            ['language' => 'Danish', 'code' => 'da', 'emoji' => '🇩🇰', 'is_active' => true],
            ['language' => 'Finnish', 'code' => 'fi', 'emoji' => '🇫🇮', 'is_active' => true],

            // Regional European Languages
            ['language' => 'Romanian', 'code' => 'ro', 'emoji' => '🇷🇴', 'is_active' => true],
            ['language' => 'Hungarian', 'code' => 'hu', 'emoji' => '🇭🇺', 'is_active' => true],
            ['language' => 'Czech', 'code' => 'cs', 'emoji' => '🇨🇿', 'is_active' => true],
            ['language' => 'Slovak', 'code' => 'sk', 'emoji' => '🇸🇰', 'is_active' => true],
            ['language' => 'Bulgarian', 'code' => 'bg', 'emoji' => '🇧🇬', 'is_active' => true],
            ['language' => 'Croatian', 'code' => 'hr', 'emoji' => '🇭🇷', 'is_active' => true],
            ['language' => 'Serbian', 'code' => 'sr', 'emoji' => '🇷🇸', 'is_active' => true],
            ['language' => 'Slovenian', 'code' => 'sl', 'emoji' => '🇸🇮', 'is_active' => true],
            ['language' => 'Lithuanian', 'code' => 'lt', 'emoji' => '🇱🇹', 'is_active' => true],
            ['language' => 'Latvian', 'code' => 'lv', 'emoji' => '🇱🇻', 'is_active' => true],
            ['language' => 'Estonian', 'code' => 'et', 'emoji' => '🇪🇪', 'is_active' => true],
            ['language' => 'Greek', 'code' => 'el', 'emoji' => '🇬🇷', 'is_active' => true],
            ['language' => 'Ukrainian', 'code' => 'uk', 'emoji' => '🇺🇦', 'is_active' => true],
            ['language' => 'Belarusian', 'code' => 'be', 'emoji' => '🇧🇾', 'is_active' => true],

            // Asian Languages
            ['language' => 'Thai', 'code' => 'th', 'emoji' => '🇹🇭', 'is_active' => true],
            ['language' => 'Vietnamese', 'code' => 'vi', 'emoji' => '🇻🇳', 'is_active' => true],
            ['language' => 'Indonesian', 'code' => 'id', 'emoji' => '🇮🇩', 'is_active' => true],
            ['language' => 'Malay', 'code' => 'ms', 'emoji' => '🇲🇾', 'is_active' => true],
            ['language' => 'Tagalog', 'code' => 'tl', 'emoji' => '🇵🇭', 'is_active' => true],
            ['language' => 'Bengali', 'code' => 'bn', 'emoji' => '🇧🇩', 'is_active' => true],
            ['language' => 'Tamil', 'code' => 'ta', 'emoji' => '🇱🇰', 'is_active' => true],
            ['language' => 'Telugu', 'code' => 'te', 'emoji' => '🇮🇳', 'is_active' => true],
            ['language' => 'Marathi', 'code' => 'mr', 'emoji' => '🇮🇳', 'is_active' => true],
            ['language' => 'Gujarati', 'code' => 'gu', 'emoji' => '🇮🇳', 'is_active' => true],
            ['language' => 'Punjabi', 'code' => 'pa', 'emoji' => '🇮🇳', 'is_active' => true],
            ['language' => 'Urdu', 'code' => 'ur', 'emoji' => '🇵🇰', 'is_active' => true],
            ['language' => 'Persian', 'code' => 'fa', 'emoji' => '🇮🇷', 'is_active' => true],
            ['language' => 'Hebrew', 'code' => 'he', 'emoji' => '🇮🇱', 'is_active' => true],

            // African Languages
            ['language' => 'Swahili', 'code' => 'sw', 'emoji' => '🇰🇪', 'is_active' => true],
            ['language' => 'Afrikaans', 'code' => 'af', 'emoji' => '🇿🇦', 'is_active' => true],
            ['language' => 'Amharic', 'code' => 'am', 'emoji' => '🇪🇹', 'is_active' => true],
            ['language' => 'Hausa', 'code' => 'ha', 'emoji' => '🇳🇬', 'is_active' => true],
            ['language' => 'Yoruba', 'code' => 'yo', 'emoji' => '🇳🇬', 'is_active' => true],
            ['language' => 'Zulu', 'code' => 'zu', 'emoji' => '🇿🇦', 'is_active' => true],

            // American Languages
            ['language' => 'Portuguese (Brazil)', 'code' => 'pt-br', 'emoji' => '🇧🇷', 'is_active' => true],
            ['language' => 'Spanish (Mexico)', 'code' => 'es-mx', 'emoji' => '🇲🇽', 'is_active' => true],
            ['language' => 'Spanish (Argentina)', 'code' => 'es-ar', 'emoji' => '🇦🇷', 'is_active' => true],
            ['language' => 'French (Canada)', 'code' => 'fr-ca', 'emoji' => '🇨🇦', 'is_active' => true],

            // Additional Languages
            ['language' => 'Catalan', 'code' => 'ca', 'emoji' => '🇪🇸', 'is_active' => true],
            ['language' => 'Basque', 'code' => 'eu', 'emoji' => '🇪🇸', 'is_active' => true],
            ['language' => 'Galician', 'code' => 'gl', 'emoji' => '🇪🇸', 'is_active' => true],
            ['language' => 'Welsh', 'code' => 'cy', 'emoji' => '🏴󠁧󠁢󠁷󠁬󠁳󠁿', 'is_active' => true],
            ['language' => 'Irish', 'code' => 'ga', 'emoji' => '🇮🇪', 'is_active' => true],
            ['language' => 'Icelandic', 'code' => 'is', 'emoji' => '🇮🇸', 'is_active' => true],
            ['language' => 'Maltese', 'code' => 'mt', 'emoji' => '🇲🇹', 'is_active' => true],
            ['language' => 'Macedonian', 'code' => 'mk', 'emoji' => '🇲🇰', 'is_active' => true],
            ['language' => 'Albanian', 'code' => 'sq', 'emoji' => '🇦🇱', 'is_active' => true],
            ['language' => 'Bosnian', 'code' => 'bs', 'emoji' => '🇧🇦', 'is_active' => true],
            ['language' => 'Montenegrin', 'code' => 'cnr', 'emoji' => '🇲🇪', 'is_active' => true],

            // Central Asian Languages
            ['language' => 'Kazakh', 'code' => 'kk', 'emoji' => '🇰🇿', 'is_active' => true],
            ['language' => 'Uzbek', 'code' => 'uz', 'emoji' => '🇺🇿', 'is_active' => true],
            ['language' => 'Kyrgyz', 'code' => 'ky', 'emoji' => '🇰🇬', 'is_active' => true],
            ['language' => 'Tajik', 'code' => 'tg', 'emoji' => '🇹🇯', 'is_active' => true],
            ['language' => 'Turkmen', 'code' => 'tk', 'emoji' => '🇹🇲', 'is_active' => true],
            ['language' => 'Mongolian', 'code' => 'mn', 'emoji' => '🇲🇳', 'is_active' => true],

            // Caucasus Languages
            ['language' => 'Georgian', 'code' => 'ka', 'emoji' => '🇬🇪', 'is_active' => true],
            ['language' => 'Armenian', 'code' => 'hy', 'emoji' => '🇦🇲', 'is_active' => true],
            ['language' => 'Azerbaijani', 'code' => 'az', 'emoji' => '🇦🇿', 'is_active' => true],

            // Additional Asian Languages
            ['language' => 'Burmese', 'code' => 'my', 'emoji' => '🇲🇲', 'is_active' => true],
            ['language' => 'Khmer', 'code' => 'km', 'emoji' => '🇰🇭', 'is_active' => true],
            ['language' => 'Lao', 'code' => 'lo', 'emoji' => '🇱🇦', 'is_active' => true],
            ['language' => 'Sinhala', 'code' => 'si', 'emoji' => '🇱🇰', 'is_active' => true],
            ['language' => 'Nepali', 'code' => 'ne', 'emoji' => '🇳🇵', 'is_active' => true],

            // Pacific Languages
            ['language' => 'Maori', 'code' => 'mi', 'emoji' => '🇳🇿', 'is_active' => true],
            ['language' => 'Samoan', 'code' => 'sm', 'emoji' => '🇼🇸', 'is_active' => true],
            ['language' => 'Tongan', 'code' => 'to', 'emoji' => '🇹🇴', 'is_active' => true],
            ['language' => 'Fijian', 'code' => 'fj', 'emoji' => '🇫🇯', 'is_active' => true],

            // Less common but notable languages
            ['language' => 'Esperanto', 'code' => 'eo', 'emoji' => '🌍', 'is_active' => false],
            ['language' => 'Latin', 'code' => 'la', 'emoji' => '🏛️', 'is_active' => false],
            ['language' => 'Sanskrit', 'code' => 'sa', 'emoji' => '🕉️', 'is_active' => false],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
