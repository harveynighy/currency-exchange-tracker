<?php

// Currency code to country code mapping
$currencyMap = [
    'aed' => 'ae',  // UAE Dirham
    'afn' => 'af',  // Afghan Afghani
    'all' => 'al',  // Albanian Lek
    'amd' => 'am',  // Armenian Dram
    'ang' => 'cw',  // Netherlands Antillean Guilder
    'aoa' => 'ao',  // Angolan Kwanza
    'ars' => 'ar',  // Argentine Peso
    'aud' => 'au',  // Australian Dollar
    'awg' => 'aw',  // Aruban Florin
    'azn' => 'az',  // Azerbaijani Manat
    'bam' => 'ba',  // Bosnia-Herzegovina Convertible Mark
    'bbd' => 'bb',  // Barbadian Dollar
    'bdt' => 'bd',  // Bangladeshi Taka
    'bgn' => 'bg',  // Bulgarian Lev
    'bhd' => 'bh',  // Bahraini Dinar
    'bif' => 'bi',  // Burundian Franc
    'bmd' => 'bm',  // Bermudian Dollar
    'bnd' => 'bn',  // Brunei Dollar
    'bob' => 'bo',  // Bolivian Boliviano
    'brl' => 'br',  // Brazilian Real
    'bsd' => 'bs',  // Bahamian Dollar
    'btn' => 'bt',  // Bhutanese Ngultrum
    'bwp' => 'bw',  // Botswanan Pula
    'byn' => 'by',  // Belarusian Ruble
    'bzd' => 'bz',  // Belize Dollar
    'cad' => 'ca',  // Canadian Dollar
    'cdf' => 'cd',  // Congolese Franc
    'chf' => 'ch',  // Swiss Franc
    'clp' => 'cl',  // Chilean Peso
    'cny' => 'cn',  // Chinese Yuan
    'cop' => 'co',  // Colombian Peso
    'crc' => 'cr',  // Costa Rican Colón
    'cup' => 'cu',  // Cuban Peso
    'cve' => 'cv',  // Cape Verdean Escudo
    'czk' => 'cz',  // Czech Koruna
    'djf' => 'dj',  // Djiboutian Franc
    'dkk' => 'dk',  // Danish Krone
    'dop' => 'do',  // Dominican Peso
    'dzd' => 'dz',  // Algerian Dinar
    'egp' => 'eg',  // Egyptian Pound
    'ern' => 'er',  // Eritrean Nakfa
    'etb' => 'et',  // Ethiopian Birr
    'fjd' => 'fj',  // Fijian Dollar
    'fkp' => 'fk',  // Falkland Islands Pound
    'fok' => 'fo',  // Faroese Króna
    'gbp' => 'gb',  // British Pound Sterling
    'gel' => 'ge',  // Georgian Lari
    'ggp' => 'gg',  // Guernsey Pound
    'ghs' => 'gh',  // Ghanaian Cedi
    'gip' => 'gi',  // Gibraltar Pound
    'gmd' => 'gm',  // Gambian Dalasi
    'gnf' => 'gn',  // Guinean Franc
    'gtq' => 'gt',  // Guatemalan Quetzal
    'gyd' => 'gy',  // Guyanaese Dollar
    'hkd' => 'hk',  // Hong Kong Dollar
    'hnl' => 'hn',  // Honduran Lempira
    'hrk' => 'hr',  // Croatian Kuna
    'htg' => 'ht',  // Haitian Gourde
    'huf' => 'hu',  // Hungarian Forint
    'idr' => 'id',  // Indonesian Rupiah
    'ils' => 'il',  // Israeli New Sheqel
    'imp' => 'im',  // Manx pound
    'inr' => 'in',  // Indian Rupee
    'iqd' => 'iq',  // Iraqi Dinar
    'irr' => 'ir',  // Iranian Rial
    'isk' => 'is',  // Icelandic Króna
    'jep' => 'je',  // Jersey Pound
    'jmd' => 'jm',  // Jamaican Dollar
    'jod' => 'jo',  // Jordanian Dinar
    'jpy' => 'jp',  // Japanese Yen
    'kes' => 'ke',  // Kenyan Shilling
    'kgs' => 'kg',  // Kyrgystani Som
    'khr' => 'kh',  // Cambodian Riel
    'kid' => 'ki',  // Kiribati Dollar
    'kmf' => 'km',  // Comorian Franc
    'krw' => 'kr',  // South Korean Won
    'kwd' => 'kw',  // Kuwaiti Dinar
    'kyd' => 'ky',  // Cayman Islands Dollar
    'kzt' => 'kz',  // Kazakhstani Tenge
    'lak' => 'la',  // Laotian Kip
    'lbp' => 'lb',  // Lebanese Pound
    'lkr' => 'lk',  // Sri Lankan Rupee
    'lrd' => 'lr',  // Liberian Dollar
    'lsl' => 'ls',  // Lesotho Loti
    'lyd' => 'ly',  // Libyan Dinar
    'mad' => 'ma',  // Moroccan Dirham
    'mdl' => 'md',  // Moldovan Leu
    'mga' => 'mg',  // Malagasy Ariary
    'mkd' => 'mk',  // Macedonian Denar
    'mmk' => 'mm',  // Myanma Kyat
    'mnt' => 'mn',  // Mongolian Tugrik
    'mop' => 'mo',  // Macanese Pataca
    'mru' => 'mr',  // Mauritanian Ouguiya
    'mur' => 'mu',  // Mauritian Rupee
    'mvr' => 'mv',  // Maldivian Rufiyaa
    'mwk' => 'mw',  // Malawian Kwacha
    'mxn' => 'mx',  // Mexican Peso
    'myr' => 'my',  // Malaysian Ringgit
    'mzn' => 'mz',  // Mozambican Metical
    'nad' => 'na',  // Namibian Dollar
    'ngn' => 'ng',  // Nigerian Naira
    'nio' => 'ni',  // Nicaraguan Córdoba
    'nok' => 'no',  // Norwegian Krone
    'npr' => 'np',  // Nepalese Rupee
    'nzd' => 'nz',  // New Zealand Dollar
    'omr' => 'om',  // Omani Rial
    'pab' => 'pa',  // Panamanian Balboa
    'pen' => 'pe',  // Peruvian Nuevo Sol
    'pgk' => 'pg',  // Papua New Guinean Kina
    'php' => 'ph',  // Philippine Peso
    'pkr' => 'pk',  // Pakistani Rupee
    'pln' => 'pl',  // Polish Zloty
    'pyg' => 'py',  // Paraguayan Guarani
    'qar' => 'qa',  // Qatari Rial
    'ron' => 'ro',  // Romanian Leu
    'rsd' => 'rs',  // Serbian Dinar
    'rub' => 'ru',  // Russian Ruble
    'rwf' => 'rw',  // Rwandan Franc
    'sar' => 'sa',  // Saudi Riyal
    'sbd' => 'sb',  // Solomon Islands Dollar
    'scr' => 'sc',  // Seychellois Rupee
    'sdg' => 'sd',  // Sudanese Pound
    'sek' => 'se',  // Swedish Krona
    'sgd' => 'sg',  // Singapore Dollar
    'shp' => 'sh',  // Saint Helena Pound
    'sle' => 'sl',  // Sierra Leonean Leone
    'sos' => 'so',  // Somali Shilling
    'srd' => 'sr',  // Surinamese Dollar
    'ssp' => 'ss',  // South Sudanese Pound
    'stn' => 'st',  // São Tomé and Príncipe Dobra
    'syp' => 'sy',  // Syrian Pound
    'szl' => 'sz',  // Swazi Lilangeni
    'thb' => 'th',  // Thai Baht
    'tjs' => 'tj',  // Tajikistani Somoni
    'tmt' => 'tm',  // Turkmenistani Manat
    'tnd' => 'tn',  // Tunisian Dinar
    'top' => 'to',  // Tongan Paʻanga
    'try' => 'tr',  // Turkish Lira
    'ttd' => 'tt',  // Trinidad and Tobago Dollar
    'tvd' => 'tv',  // Tuvaluan Dollar
    'twd' => 'tw',  // New Taiwan Dollar
    'tzs' => 'tz',  // Tanzanian Shilling
    'uah' => 'ua',  // Ukrainian Hryvnia
    'ugx' => 'ug',  // Ugandan Shilling
    'usd' => 'us',  // United States Dollar
    'uyu' => 'uy',  // Uruguayan Peso
    'uzs' => 'uz',  // Uzbekistan Som
    'ves' => 've',  // Venezuelan Bolívar
    'vnd' => 'vn',  // Vietnamese Dong
    'vuv' => 'vu',  // Vanuatu Vatu
    'wst' => 'ws',  // Samoan Tala
    'xaf' => 'cm',  // CFA Franc BEAC (using Cameroon)
    'xcd' => 'ag',  // East Caribbean Dollar (using Antigua)
    'xof' => 'sn',  // CFA Franc BCEAO (using Senegal)
    'xpf' => 'pf',  // CFP Franc (French Polynesia)
    'yer' => 'ye',  // Yemeni Rial
    'zar' => 'za',  // South African Rand
    'zmw' => 'zm',  // Zambian Kwacha
    'zwl' => 'zw',  // Zimbabwean Dollar
];

$dir = __DIR__;
$created = 0;
$warnings = [];

foreach ($currencyMap as $currency => $country) {
    $countryFile = "$dir/{$country}.svg";
    $currencyFile = "$dir/{$currency}.svg";
    
    if (file_exists($countryFile)) {
        copy($countryFile, $currencyFile);
        echo "✓ Created {$currency}.svg from {$country}.svg\n";
        $created++;
    } else {
        $warnings[] = "⚠ Warning: {$country}.svg not found for currency {$currency}";
    }
}

// Special handling for EUR - try to find an EU flag
if (file_exists("$dir/eu.svg")) {
    copy("$dir/eu.svg", "$dir/eur.svg");
    echo "✓ Created eur.svg from eu.svg\n";
    $created++;
} elseif (file_exists("$dir/de.svg")) {
    copy("$dir/de.svg", "$dir/eur.svg");
    echo "✓ Created eur.svg from de.svg (EU flag not found)\n";
    $created++;
} else {
    $warnings[] = "⚠ Warning: Could not create eur.svg";
}

echo "\n✓ Done! Created $created currency code flags.\n";

if (!empty($warnings)) {
    echo "\nWarnings:\n";
    foreach ($warnings as $warning) {
        echo "$warning\n";
    }
}
