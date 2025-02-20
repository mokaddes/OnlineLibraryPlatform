<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Franchises Available Now'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool)env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Brian2694\Toastr\ToastrServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Js' => Illuminate\Support\Js::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Toastr' => Brian2694\Toastr\Facades\Toastr::class,

    ],

    'Offerings' => [
        '1' => 'Access one books monthly',
        '2' => 'Unlimited book access',
        '3' => 'Unlimited book access',
    ],

    'Library_Content' => [
        '1' => 'Restricted access to premium content',
        '2' => 'Access to all library content',
        '3' => 'Access to all library content',

    ],

    'Book_Access' => [
        '1' => 'Renew book access in the next month',
        '2' => 'Renew book access after two days',
        '3' => 'Renew book access after two days',
    ],

    'Blog_Access' => [
        '1' => 'Read only access',
        '2' => 'Read and Comment access only',
        '3' => 'Full blog access',
    ],
    'Forum_Access' => [
        '1' => 'Read only access',
        '2' => 'Read and Comment access only',
        '3' => 'Full forum access',
    ],

    'Book_Club_Access' => [
        '1' => 'No access',
        '2' => 'Join existing clubs',
        '3' => 'Can create and join clubs',
    ],

    'countries' => [
        'af' => "Afghanistan",
        'ax' => "Aland Islands",
        'al' => "Albania",
        'dz' => "Algeria",
        'as' => "American Samoa",
        'ad' => "Andorra",
        'ao' => "Angola",
        'ai' => "Anguilla",
        'aq' => "Antarctica",
        'ag' => "Antigua And Barbuda",
        'ar' => "Argentina",
        'am' => "Armenia",
        'aw' => "Aruba",
        'au' => "Australia",
        'at' => "Austria",
        'az' => "Azerbaijan",
        'bs' => "Bahamas",
        'bh' => "Bahrain",
        'bd' => "Bangladesh",
        'bb' => "Barbados",
        'by' => "Belarus",
        'be' => "Belgium",
        'bz' => "Belize",
        'bj' => "Benin",
        'bm' => "Bermuda",
        'bt' => "Bhutan",
        'bo' => "Bolivia",
        'ba' => "Bosnia And Herzegovina",
        'bw' => "Botswana",
        'bv' => "Bouvet Island",
        'br' => "Brazil",
        'io' => "British Indian Ocean Territory",
        'bn' => "Brunei Darussalam",
        'bg' => "Bulgaria",
        'bf' => "Burkina Faso",
        'bi' => "Burundi",
        'kh' => "Cambodia",
        'cm' => "Cameroon",
        'ca' => "Canada",
        'cv' => "Cape Verde",
        'ky' => "Cayman Islands",
        'cf' => "Central African Republic",
        'td' => "Chad",
        'cl' => "Chile",
        'cn' => "China",
        'cx' => "Christmas Island",
        'cc' => "Cocos (Keeling) Islands",
        'co' => "Colombia",
        'km' => "Comoros",
        'cg' => "Congo",
        'cd' => "Congo, Democratic Republic",
        'ck' => "Cook Islands",
        'cr' => "Costa Rica",
        'ci' => "Cote D'Ivoire",
        'hr' => "Croatia",
        'cu' => "Cuba",
        'cy' => "Cyprus",
        'cz' => "Czech Republic",
        'dk' => "Denmark",
        'dj' => "Djibouti",
        'dm' => "Dominica",
        'do' => "Dominican Republic",
        'ec' => "Ecuador",
        'eg' => "Egypt",
        'sv' => "El Salvador",
        'gq' => "Equatorial Guinea",
        'er' => "Eritrea",
        'ee' => "Estonia",
        'et' => "Ethiopia",
        'fk' => "Falkland Islands (Malvinas)",
        'fo' => "Faroe Islands",
        'fj' => "Fiji",
        'fi' => "Finland",
        'fr' => "France",
        'gf' => "French Guiana",
        'pf' => "French Polynesia",
        'tf' => "French Southern Territories",
        'ga' => "Gabon",
        'gm' => "Gambia",
        'ge' => "Georgia",
        'de' => "Germany",
        'gh' => "Ghana",
        'gi' => "Gibraltar",
        'gr' => "Greece",
        'gl' => "Greenland",
        'gd' => "Grenada",
        'gp' => "Guadeloupe",
        'gu' => "Guam",
        'gt' => "Guatemala",
        'gg' => "Guernsey",
        'gn' => "Guinea",
        'gw' => "Guinea-Bissau",
        'gy' => "Guyana",
        'ht' => "Haiti",
        'hm' => "Heard Island & Mcdonald Islands",
        'va' => "Holy See (Vatican City State)",
        'hn' => "Honduras",
        'hk' => "Hong Kong",
        'hu' => "Hungary",
        'is' => "Iceland",
        'in' => "India",
        'id' => "Indonesia",
        'ir' => "Iran, Islamic Republic Of",
        'iq' => "Iraq",
        'ie' => "Ireland",
        'im' => "Isle Of Man",
        'il' => "Israel",
        'it' => "Italy",
        'jm' => "Jamaica",
        'jp' => "Japan",
        'je' => "Jersey",
        'jo' => "Jordan",
        'kz' => "Kazakhstan",
        'ke' => "Kenya",
        'ki' => "Kiribati",
        'kr' => "Korea",
        'kp' => "North Korea",
        'kw' => "Kuwait",
        'kg' => "Kyrgyzstan",
        'la' => "Lao People's Democratic Republic",
        'lv' => "Latvia",
        'lb' => "Lebanon",
        'ls' => "Lesotho",
        'lr' => "Liberia",
        'ly' => "Libyan Arab Jamahiriya",
        'li' => "Liechtenstein",
        'lt' => "Lithuania",
        'lu' => "Luxembourg",
        'mo' => "Macao",
        'mk' => "Macedonia",
        'mg' => "Madagascar",
        'mw' => "Malawi",
        'my' => "Malaysia",
        'mv' => "Maldives",
        'ml' => "Mali",
        'mt' => "Malta",
        'mh' => "Marshall Islands",
        'mq' => "Martinique",
        'mr' => "Mauritania",
        'mu' => "Mauritius",
        'yt' => "Mayotte",
        'mx' => "Mexico",
        'fm' => "Micronesia, Federated States Of",
        'md' => "Moldova",
        'mc' => "Monaco",
        'mn' => "Mongolia",
        'me' => "Montenegro",
        'ms' => "Montserrat",
        'ma' => "Morocco",
        'mz' => "Mozambique",
        'mm' => "Myanmar",
        'na' => "Namibia",
        'nr' => "Nauru",
        'np' => "Nepal",
        'nl' => "Netherlands",
        'an' => "Netherlands Antilles",
        'nc' => "New Caledonia",
        'nz' => "New Zealand",
        'ni' => "Nicaragua",
        'ne' => "Niger",
        'ng' => "Nigeria",
        'nu' => "Niue",
        'nf' => "Norfolk Island",
        'mp' => "Northern Mariana Islands",
        'no' => "Norway",
        'om' => "Oman",
        'pk' => "Pakistan",
        'pw' => "Palau",
        'ps' => "Palestinian Territory, Occupied",
        'pa' => "Panama",
        'pg' => "Papua New Guinea",
        'py' => "Paraguay",
        'pe' => "Peru",
        'ph' => "Philippines",
        'pn' => "Pitcairn",
        'pl' => "Poland",
        'pt' => "Portugal",
        'pr' => "Puerto Rico",
        'qa' => "Qatar",
        're' => "Reunion",
        'ro' => "Romania",
        'ru' => "Russian Federation",
        'rw' => "Rwanda",
        'bl' => "Saint Barthelemy",
        'sh' => "Saint Helena",
        'kn' => "Saint Kitts And Nevis",
        'lc' => "Saint Lucia",
        'mf' => "Saint Martin",
        'pm' => "Saint Pierre And Miquelon",
        'vc' => "Saint Vincent And Grenadines",
        'ws' => "Samoa",
        'sm' => "San Marino",
        'st' => "Sao Tome And Principe",
        'sa' => "Saudi Arabia",
        'sn' => "Senegal",
        'rs' => "Serbia",
        'sc' => "Seychelles",
        'sl' => "Sierra Leone",
        'sg' => "Singapore",
        'sk' => "Slovakia",
        'si' => "Slovenia",
        'sb' => "Solomon Islands",
        'so' => "Somalia",
        'za' => "South Africa",
        'gs' => "South Georgia And Sandwich Isl.",
        'es' => "Spain",
        'lk' => "Sri Lanka",
        'sd' => "Sudan",
        'sr' => "Suriname",
        'sj' => "Svalbard And Jan Mayen",
        'sz' => "Swaziland",
        'se' => "Sweden",
        'ch' => "Switzerland",
        'sy' => "Syrian Arab Republic",
        'tw' => "Taiwan",
        'tj' => "Tajikistan",
        'tz' => "Tanzania",
        'th' => "Thailand",
        'tl' => "Timor-Leste",
        'tg' => "Togo",
        'tk' => "Tokelau",
        'to' => "Tonga",
        'tt' => "Trinidad And Tobago",
        'tn' => "Tunisia",
        'tr' => "Turkey",
        'tm' => "Turkmenistan",
        'tc' => "Turks And Caicos Islands",
        'tv' => "Tuvalu",
        'ug' => "Uganda",
        'ua' => "Ukraine",
        'ae' => "United Arab Emirates",
        'gb' => "United Kingdom",
        'us' => "United States",
        'um' => "United States Outlying Islands",
        'uy' => "Uruguay",
        'uz' => "Uzbekistan",
        'vu' => "Vanuatu",
        've' => "Venezuela",
        'vn' => "Vietnam",
        'vg' => "Virgin Islands, British",
        'vi' => "Virgin Islands, U.S.",
        'wf' => "Wallis And Futuna",
        'eh' => "Western Sahara",
        'ye' => "Yemen",
        'zm' => "Zambia",
        'zw' => "Zimbabwe",
    ],
];
