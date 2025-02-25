<div class="wrap mcw-settings mcwgrid">
    <h2><?php _e('Settings', 'massive-cryptocurrency-widgets'); ?></h2>
    <?php if (isset($_GET['success'])) { ?>
        <div class="updated notice">
            <p><b><?php _e('Settings saved.', 'massive-cryptocurrency-widgets'); ?></b></p>
        </div>
    <?php } ?>
    <div class="crypto-edit">
        <div class="vue-component">
            <mcw-settings :options='<?php echo htmlspecialchars(json_encode($config), ENT_QUOTES, 'UTF-8'); ?>'></mcw-settings>
        </div>
    </div>
</div>

<template id="mcw-settings-template">

    <form action="<?php echo admin_url('admin-post.php'); ?>" id="mcw-settings-form" method="POST">

        <input type="hidden" name="action" value="mcw_save_settings">

        <div class="wrapper">

            <div class="mcw-sections">

                <div class="section-left">

                    <div class="form-control">
                        <ul class="page-menu">
                            <?php if ($config['license'] == 'regular' || $config['license'] == 'extended') { ?>
                            <li data-page="general" v-bind:class="{ 'active' : (menu === 'general') }" v-on:click="toggleMenu('general')"><?php _e('General', 'massive-cryptocurrency-widgets'); ?></li>
                            <li data-page="api" v-bind:class="{ 'active' : (menu === 'api') }" v-on:click="toggleMenu('api')"><?php _e('API', 'massive-cryptocurrency-widgets'); ?></li>
                            <li data-page="shortcodes" v-bind:class="{ 'active' : (menu === 'shortcodes') }" v-on:click="toggleMenu('shortcodes')"><?php _e('Shortcodes', 'massive-cryptocurrency-widgets'); ?></li>
                            <li data-page="currency" v-bind:class="{ 'active' : (menu === 'currency') }" v-on:click="toggleMenu('currency')"><?php _e('Currency Format', 'massive-cryptocurrency-widgets'); ?></li>
                            <?php } ?>
                            <li data-page="license" v-bind:class="{ 'active' : (menu === 'license') }" v-on:click="toggleMenu('license')"><?php _e('License', 'massive-cryptocurrency-widgets'); ?></li>
                        </ul>
                        
                        <a href="https://docs.blocksera.com/massive-cryptocurrency-widgets" target="_blank" class="btn-link">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Documentation
                        </a>
                    </div>

                    <div class="form-control">
                        <button class="mcw-button mcw-button-block mcw-button-lg mcw-button-primary"><?php _e('Save Details', 'massive-cryptocurrency-widgets'); ?></button>
                    </div>

                </div>

                <div class="section-right">

                    <div id="page-currency" class="page-content" v-show="menu==='currency'">
                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <h3>Default Format</h3>
                            </div>
                            <div class="crypto-cols">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th>Symbol</th>
                                            <th>Position</th>
                                            <th>Thousands Sep.</th>
                                            <th>Decimal Sep.</th>
                                            <th>Decimals</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="default_currency_format[iso]" id="" class="selectize-select" v-model="opts.default_currency_format.iso">
                                                    <option value="AED">United Arab Emirates dirham (&#x62f;.&#x625;)</option>
                                                    <option value="AFN">Afghan afghani (&#x60b;)</option>
                                                    <option value="ALL">Albanian lek (L)</option>
                                                    <option value="AMD">Armenian dram (AMD)</option>
                                                    <option value="ANG">Netherlands Antillean guilder (&fnof;)</option>
                                                    <option value="AOA">Angolan kwanza (Kz)</option>
                                                    <option value="ARS">Argentine peso (&#036;)</option>
                                                    <option value="AUD">Australian dollar (&#036;)</option>
                                                    <option value="AWG">Aruban florin (Afl.)</option>
                                                    <option value="AZN">Azerbaijani manat (AZN)</option>
                                                    <option value="BAM">Bosnia and Herzegovina convertible mark (KM)</option>
                                                    <option value="BBD">Barbadian dollar (&#036;)</option>
                                                    <option value="BDT">Bangladeshi taka (&#2547;&nbsp;)</option>
                                                    <option value="BGN">Bulgarian lev (&#1083;&#1074;.)</option>
                                                    <option value="BHD">Bahraini dinar (.&#x62f;.&#x628;)</option>
                                                    <option value="BIF">Burundian franc (Fr)</option>
                                                    <option value="BMD">Bermudian dollar (&#036;)</option>
                                                    <option value="BND">Brunei dollar (&#036;)</option>
                                                    <option value="BOB">Bolivian boliviano (Bs.)</option>
                                                    <option value="BRL">Brazilian real (&#082;&#036;)</option>
                                                    <option value="BSD">Bahamian dollar (&#036;)</option>
                                                    <option value="BTC">Bitcoin (&#3647;)</option>
                                                    <option value="BTN">Bhutanese ngultrum (Nu.)</option>
                                                    <option value="BWP">Botswana pula (P)</option>
                                                    <option value="BYR">Belarusian ruble (old) (Br)</option>
                                                    <option value="BYN">Belarusian ruble (Br)</option>
                                                    <option value="BZD">Belize dollar (&#036;)</option>
                                                    <option value="CAD">Canadian dollar (&#036;)</option>
                                                    <option value="CDF">Congolese franc (Fr)</option>
                                                    <option value="CHF">Swiss franc (&#067;&#072;&#070;)</option>
                                                    <option value="CLP">Chilean peso (&#036;)</option>
                                                    <option value="CNY">Chinese yuan (&yen;)</option>
                                                    <option value="COP">Colombian peso (&#036;)</option>
                                                    <option value="CRC">Costa Rican col&oacute;n (&#x20a1;)</option>
                                                    <option value="CUC">Cuban convertible peso (&#036;)</option>
                                                    <option value="CUP">Cuban peso (&#036;)</option>
                                                    <option value="CVE">Cape Verdean escudo (&#036;)</option>
                                                    <option value="CZK">Czech koruna (&#075;&#269;)</option>
                                                    <option value="DJF">Djiboutian franc (Fr)</option>
                                                    <option value="DKK">Danish krone (DKK)</option>
                                                    <option value="DOP">Dominican peso (RD&#036;)</option>
                                                    <option value="DZD">Algerian dinar (&#x62f;.&#x62c;)</option>
                                                    <option value="EGP">Egyptian pound (EGP)</option>
                                                    <option value="ERN">Eritrean nakfa (Nfk)</option>
                                                    <option value="ETB">Ethiopian birr (Br)</option>
                                                    <option value="EUR">Euro (&euro;)</option>
                                                    <option value="FJD">Fijian dollar (&#036;)</option>
                                                    <option value="FKP">Falkland Islands pound (&pound;)</option>
                                                    <option value="GBP">Pound sterling (&pound;)</option>
                                                    <option value="GEL">Georgian lari (&#x20be;)</option>
                                                    <option value="GGP">Guernsey pound (&pound;)</option>
                                                    <option value="GHS">Ghana cedi (&#x20b5;)</option>
                                                    <option value="GIP">Gibraltar pound (&pound;)</option>
                                                    <option value="GMD">Gambian dalasi (D)</option>
                                                    <option value="GNF">Guinean franc (Fr)</option>
                                                    <option value="GTQ">Guatemalan quetzal (Q)</option>
                                                    <option value="GYD">Guyanese dollar (&#036;)</option>
                                                    <option value="HKD">Hong Kong dollar (&#036;)</option>
                                                    <option value="HNL">Honduran lempira (L)</option>
                                                    <option value="HRK">Croatian kuna (kn)</option>
                                                    <option value="HTG">Haitian gourde (G)</option>
                                                    <option value="HUF">Hungarian forint (&#070;&#116;)</option>
                                                    <option value="IDR">Indonesian rupiah (Rp)</option>
                                                    <option value="ILS">Israeli new shekel (&#8362;)</option>
                                                    <option value="IMP">Manx pound (&pound;)</option>
                                                    <option value="INR">Indian rupee (&#8377;)</option>
                                                    <option value="IQD">Iraqi dinar (&#x639;.&#x62f;)</option>
                                                    <option value="IRR">Iranian rial (&#xfdfc;)</option>
                                                    <option value="IRT">Iranian toman (&#x62A;&#x648;&#x645;&#x627;&#x646;)</option>
                                                    <option value="ISK">Icelandic kr&oacute;na (kr.)</option>
                                                    <option value="JEP">Jersey pound (&pound;)</option>
                                                    <option value="JMD">Jamaican dollar (&#036;)</option>
                                                    <option value="JOD">Jordanian dinar (&#x62f;.&#x627;)</option>
                                                    <option value="JPY">Japanese yen (&yen;)</option>
                                                    <option value="KES">Kenyan shilling (KSh)</option>
                                                    <option value="KGS">Kyrgyzstani som (&#x441;&#x43e;&#x43c;)</option>
                                                    <option value="KHR">Cambodian riel (&#x17db;)</option>
                                                    <option value="KMF">Comorian franc (Fr)</option>
                                                    <option value="KPW">North Korean won (&#x20a9;)</option>
                                                    <option value="KRW">South Korean won (&#8361;)</option>
                                                    <option value="KWD">Kuwaiti dinar (&#x62f;.&#x643;)</option>
                                                    <option value="KYD">Cayman Islands dollar (&#036;)</option>
                                                    <option value="KZT">Kazakhstani tenge (KZT)</option>
                                                    <option value="LAK">Lao kip (&#8365;)</option>
                                                    <option value="LBP">Lebanese pound (&#x644;.&#x644;)</option>
                                                    <option value="LKR">Sri Lankan rupee (&#xdbb;&#xdd4;)</option>
                                                    <option value="LRD">Liberian dollar (&#036;)</option>
                                                    <option value="LSL">Lesotho loti (L)</option>
                                                    <option value="LYD">Libyan dinar (&#x644;.&#x62f;)</option>
                                                    <option value="MAD">Moroccan dirham (&#x62f;.&#x645;.)</option>
                                                    <option value="MDL">Moldovan leu (MDL)</option>
                                                    <option value="MGA">Malagasy ariary (Ar)</option>
                                                    <option value="MKD">Macedonian denar (&#x434;&#x435;&#x43d;)</option>
                                                    <option value="MMK">Burmese kyat (Ks)</option>
                                                    <option value="MNT">Mongolian t&ouml;gr&ouml;g (&#x20ae;)</option>
                                                    <option value="MOP">Macanese pataca (P)</option>
                                                    <option value="MRU">Mauritanian ouguiya (UM)</option>
                                                    <option value="MUR">Mauritian rupee (&#x20a8;)</option>
                                                    <option value="MVR">Maldivian rufiyaa (.&#x783;)</option>
                                                    <option value="MWK">Malawian kwacha (MK)</option>
                                                    <option value="MXN">Mexican peso (&#036;)</option>
                                                    <option value="MYR">Malaysian ringgit (&#082;&#077;)</option>
                                                    <option value="MZN">Mozambican metical (MT)</option>
                                                    <option value="NAD">Namibian dollar (&#036;)</option>
                                                    <option value="NGN">Nigerian naira (&#8358;)</option>
                                                    <option value="NIO">Nicaraguan c&oacute;rdoba (C&#036;)</option>
                                                    <option value="NOK">Norwegian krone (&#107;&#114;)</option>
                                                    <option value="NPR">Nepalese rupee (&#8360;)</option>
                                                    <option value="NZD">New Zealand dollar (&#036;)</option>
                                                    <option value="OMR">Omani rial (&#x631;.&#x639;.)</option>
                                                    <option value="PAB">Panamanian balboa (B/.)</option>
                                                    <option value="PEN">Sol (S/)</option>
                                                    <option value="PGK">Papua New Guinean kina (K)</option>
                                                    <option value="PHP">Philippine peso (&#8369;)</option>
                                                    <option value="PKR">Pakistani rupee (&#8360;)</option>
                                                    <option value="PLN">Polish z&#x142;oty (&#122;&#322;)</option>
                                                    <option value="PRB">Transnistrian ruble (&#x440;.)</option>
                                                    <option value="PYG">Paraguayan guaran&iacute; (&#8370;)</option>
                                                    <option value="QAR">Qatari riyal (&#x631;.&#x642;)</option>
                                                    <option value="RON">Romanian leu (lei)</option>
                                                    <option value="RSD">Serbian dinar (&#x434;&#x438;&#x43d;.)</option>
                                                    <option value="RUB">Russian ruble (&#8381;)</option>
                                                    <option value="RWF">Rwandan franc (Fr)</option>
                                                    <option value="SAR">Saudi riyal (&#x631;.&#x633;)</option>
                                                    <option value="SBD">Solomon Islands dollar (&#036;)</option>
                                                    <option value="SCR">Seychellois rupee (&#x20a8;)</option>
                                                    <option value="SDG">Sudanese pound (&#x62c;.&#x633;.)</option>
                                                    <option value="SEK">Swedish krona (&#107;&#114;)</option>
                                                    <option value="SGD">Singapore dollar (&#036;)</option>
                                                    <option value="SHP">Saint Helena pound (&pound;)</option>
                                                    <option value="SLL">Sierra Leonean leone (Le)</option>
                                                    <option value="SOS">Somali shilling (Sh)</option>
                                                    <option value="SRD">Surinamese dollar (&#036;)</option>
                                                    <option value="SSP">South Sudanese pound (&pound;)</option>
                                                    <option value="STN">S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra (Db)</option>
                                                    <option value="SYP">Syrian pound (&#x644;.&#x633;)</option>
                                                    <option value="SZL">Swazi lilangeni (L)</option>
                                                    <option value="THB">Thai baht (&#3647;)</option>
                                                    <option value="TJS">Tajikistani somoni (&#x405;&#x41c;)</option>
                                                    <option value="TMT">Turkmenistan manat (m)</option>
                                                    <option value="TND">Tunisian dinar (&#x62f;.&#x62a;)</option>
                                                    <option value="TOP">Tongan pa&#x2bb;anga (T&#036;)</option>
                                                    <option value="TRY">Turkish lira (&#8378;)</option>
                                                    <option value="TTD">Trinidad and Tobago dollar (&#036;)</option>
                                                    <option value="TWD">New Taiwan dollar (&#078;&#084;&#036;)</option>
                                                    <option value="TZS">Tanzanian shilling (Sh)</option>
                                                    <option value="UAH">Ukrainian hryvnia (&#8372;)</option>
                                                    <option value="UGX">Ugandan shilling (UGX)</option>
                                                    <option value="USD">United States (US) dollar (&#036;)</option>
                                                    <option value="UYU">Uruguayan peso (&#036;)</option>
                                                    <option value="UZS">Uzbekistani som (UZS)</option>
                                                    <option value="VEF">Venezuelan bol&iacute;var (Bs F)</option>
                                                    <option value="VES">Bol&iacute;var soberano (Bs.S)</option>
                                                    <option value="VND">Vietnamese &#x111;&#x1ed3;ng (&#8363;)</option>
                                                    <option value="VUV">Vanuatu vatu (Vt)</option>
                                                    <option value="WST">Samoan t&#x101;l&#x101; (T)</option>
                                                    <option value="XAF">Central African CFA franc (CFA)</option>
                                                    <option value="XCD">East Caribbean dollar (&#036;)</option>
                                                    <option value="XOF">West African CFA franc (CFA)</option>
                                                    <option value="XPF">CFP franc (Fr)</option>
                                                    <option value="YER">Yemeni rial (&#xfdfc;)</option>
                                                    <option value="ZAR">South African rand (&#082;)</option>
                                                    <option value="ZMW">Zambian kwacha (ZK)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input name="default_currency_format[symbol]" type="text" class="selectize-input" v-model="opts.default_currency_format.symbol">
                                            </td>
                                            <td>
                                                <select name="default_currency_format[position]" id="" class="selectize-select" v-model="opts.default_currency_format.position" style="min-width: 180px;">
                                                    <option value="{price}">None</option>
                                                    <option value="{symbol}{price}">Left</option>
                                                    <option value="{symbol}{space}{price}">Left with space</option>
                                                    <option value="{price}{symbol}">Right</option>
                                                    <option value="{price}{space}{symbol}">Right with space</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input name="default_currency_format[thousands_sep]" type="text" class="selectize-input" v-model="opts.default_currency_format.thousands_sep">
                                            </td>
                                            <td>
                                                <input name="default_currency_format[decimals_sep]" type="text" class="selectize-input" v-model="opts.default_currency_format.decimals_sep">
                                            </td>
                                            <td>
                                                <input name="default_currency_format[decimals]" type="number" class="selectize-input" v-model="opts.default_currency_format.decimals">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <h3>Currency Formats</h3>
                            </div>
                            <div class="crypto-cols">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th>Symbol</th>
                                            <th>Position</th>
                                            <th>Thousands Sep.</th>
                                            <th>Decimal Sep.</th>
                                            <th>Decimals</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(format, index) in opts.currency_format">
                                            <td>
                                                <select v-bind:name="'currency_format[' + index + '][iso]'" id="" class="selectize-select" v-model="format.iso">
                                                    <option value="AED">United Arab Emirates dirham (&#x62f;.&#x625;)</option>
                                                    <option value="AFN">Afghan afghani (&#x60b;)</option>
                                                    <option value="ALL">Albanian lek (L)</option>
                                                    <option value="AMD">Armenian dram (AMD)</option>
                                                    <option value="ANG">Netherlands Antillean guilder (&fnof;)</option>
                                                    <option value="AOA">Angolan kwanza (Kz)</option>
                                                    <option value="ARS">Argentine peso (&#036;)</option>
                                                    <option value="AUD">Australian dollar (&#036;)</option>
                                                    <option value="AWG">Aruban florin (Afl.)</option>
                                                    <option value="AZN">Azerbaijani manat (AZN)</option>
                                                    <option value="BAM">Bosnia and Herzegovina convertible mark (KM)</option>
                                                    <option value="BBD">Barbadian dollar (&#036;)</option>
                                                    <option value="BDT">Bangladeshi taka (&#2547;&nbsp;)</option>
                                                    <option value="BGN">Bulgarian lev (&#1083;&#1074;.)</option>
                                                    <option value="BHD">Bahraini dinar (.&#x62f;.&#x628;)</option>
                                                    <option value="BIF">Burundian franc (Fr)</option>
                                                    <option value="BMD">Bermudian dollar (&#036;)</option>
                                                    <option value="BND">Brunei dollar (&#036;)</option>
                                                    <option value="BOB">Bolivian boliviano (Bs.)</option>
                                                    <option value="BRL">Brazilian real (&#082;&#036;)</option>
                                                    <option value="BSD">Bahamian dollar (&#036;)</option>
                                                    <option value="BTC">Bitcoin (&#3647;)</option>
                                                    <option value="BTN">Bhutanese ngultrum (Nu.)</option>
                                                    <option value="BWP">Botswana pula (P)</option>
                                                    <option value="BYR">Belarusian ruble (old) (Br)</option>
                                                    <option value="BYN">Belarusian ruble (Br)</option>
                                                    <option value="BZD">Belize dollar (&#036;)</option>
                                                    <option value="CAD">Canadian dollar (&#036;)</option>
                                                    <option value="CDF">Congolese franc (Fr)</option>
                                                    <option value="CHF">Swiss franc (&#067;&#072;&#070;)</option>
                                                    <option value="CLP">Chilean peso (&#036;)</option>
                                                    <option value="CNY">Chinese yuan (&yen;)</option>
                                                    <option value="COP">Colombian peso (&#036;)</option>
                                                    <option value="CRC">Costa Rican col&oacute;n (&#x20a1;)</option>
                                                    <option value="CUC">Cuban convertible peso (&#036;)</option>
                                                    <option value="CUP">Cuban peso (&#036;)</option>
                                                    <option value="CVE">Cape Verdean escudo (&#036;)</option>
                                                    <option value="CZK">Czech koruna (&#075;&#269;)</option>
                                                    <option value="DJF">Djiboutian franc (Fr)</option>
                                                    <option value="DKK">Danish krone (DKK)</option>
                                                    <option value="DOP">Dominican peso (RD&#036;)</option>
                                                    <option value="DZD">Algerian dinar (&#x62f;.&#x62c;)</option>
                                                    <option value="EGP">Egyptian pound (EGP)</option>
                                                    <option value="ERN">Eritrean nakfa (Nfk)</option>
                                                    <option value="ETB">Ethiopian birr (Br)</option>
                                                    <option value="EUR">Euro (&euro;)</option>
                                                    <option value="FJD">Fijian dollar (&#036;)</option>
                                                    <option value="FKP">Falkland Islands pound (&pound;)</option>
                                                    <option value="GBP">Pound sterling (&pound;)</option>
                                                    <option value="GEL">Georgian lari (&#x20be;)</option>
                                                    <option value="GGP">Guernsey pound (&pound;)</option>
                                                    <option value="GHS">Ghana cedi (&#x20b5;)</option>
                                                    <option value="GIP">Gibraltar pound (&pound;)</option>
                                                    <option value="GMD">Gambian dalasi (D)</option>
                                                    <option value="GNF">Guinean franc (Fr)</option>
                                                    <option value="GTQ">Guatemalan quetzal (Q)</option>
                                                    <option value="GYD">Guyanese dollar (&#036;)</option>
                                                    <option value="HKD">Hong Kong dollar (&#036;)</option>
                                                    <option value="HNL">Honduran lempira (L)</option>
                                                    <option value="HRK">Croatian kuna (kn)</option>
                                                    <option value="HTG">Haitian gourde (G)</option>
                                                    <option value="HUF">Hungarian forint (&#070;&#116;)</option>
                                                    <option value="IDR">Indonesian rupiah (Rp)</option>
                                                    <option value="ILS">Israeli new shekel (&#8362;)</option>
                                                    <option value="IMP">Manx pound (&pound;)</option>
                                                    <option value="INR">Indian rupee (&#8377;)</option>
                                                    <option value="IQD">Iraqi dinar (&#x639;.&#x62f;)</option>
                                                    <option value="IRR">Iranian rial (&#xfdfc;)</option>
                                                    <option value="IRT">Iranian toman (&#x62A;&#x648;&#x645;&#x627;&#x646;)</option>
                                                    <option value="ISK">Icelandic kr&oacute;na (kr.)</option>
                                                    <option value="JEP">Jersey pound (&pound;)</option>
                                                    <option value="JMD">Jamaican dollar (&#036;)</option>
                                                    <option value="JOD">Jordanian dinar (&#x62f;.&#x627;)</option>
                                                    <option value="JPY">Japanese yen (&yen;)</option>
                                                    <option value="KES">Kenyan shilling (KSh)</option>
                                                    <option value="KGS">Kyrgyzstani som (&#x441;&#x43e;&#x43c;)</option>
                                                    <option value="KHR">Cambodian riel (&#x17db;)</option>
                                                    <option value="KMF">Comorian franc (Fr)</option>
                                                    <option value="KPW">North Korean won (&#x20a9;)</option>
                                                    <option value="KRW">South Korean won (&#8361;)</option>
                                                    <option value="KWD">Kuwaiti dinar (&#x62f;.&#x643;)</option>
                                                    <option value="KYD">Cayman Islands dollar (&#036;)</option>
                                                    <option value="KZT">Kazakhstani tenge (KZT)</option>
                                                    <option value="LAK">Lao kip (&#8365;)</option>
                                                    <option value="LBP">Lebanese pound (&#x644;.&#x644;)</option>
                                                    <option value="LKR">Sri Lankan rupee (&#xdbb;&#xdd4;)</option>
                                                    <option value="LRD">Liberian dollar (&#036;)</option>
                                                    <option value="LSL">Lesotho loti (L)</option>
                                                    <option value="LYD">Libyan dinar (&#x644;.&#x62f;)</option>
                                                    <option value="MAD">Moroccan dirham (&#x62f;.&#x645;.)</option>
                                                    <option value="MDL">Moldovan leu (MDL)</option>
                                                    <option value="MGA">Malagasy ariary (Ar)</option>
                                                    <option value="MKD">Macedonian denar (&#x434;&#x435;&#x43d;)</option>
                                                    <option value="MMK">Burmese kyat (Ks)</option>
                                                    <option value="MNT">Mongolian t&ouml;gr&ouml;g (&#x20ae;)</option>
                                                    <option value="MOP">Macanese pataca (P)</option>
                                                    <option value="MRU">Mauritanian ouguiya (UM)</option>
                                                    <option value="MUR">Mauritian rupee (&#x20a8;)</option>
                                                    <option value="MVR">Maldivian rufiyaa (.&#x783;)</option>
                                                    <option value="MWK">Malawian kwacha (MK)</option>
                                                    <option value="MXN">Mexican peso (&#036;)</option>
                                                    <option value="MYR">Malaysian ringgit (&#082;&#077;)</option>
                                                    <option value="MZN">Mozambican metical (MT)</option>
                                                    <option value="NAD">Namibian dollar (&#036;)</option>
                                                    <option value="NGN">Nigerian naira (&#8358;)</option>
                                                    <option value="NIO">Nicaraguan c&oacute;rdoba (C&#036;)</option>
                                                    <option value="NOK">Norwegian krone (&#107;&#114;)</option>
                                                    <option value="NPR">Nepalese rupee (&#8360;)</option>
                                                    <option value="NZD">New Zealand dollar (&#036;)</option>
                                                    <option value="OMR">Omani rial (&#x631;.&#x639;.)</option>
                                                    <option value="PAB">Panamanian balboa (B/.)</option>
                                                    <option value="PEN">Sol (S/)</option>
                                                    <option value="PGK">Papua New Guinean kina (K)</option>
                                                    <option value="PHP">Philippine peso (&#8369;)</option>
                                                    <option value="PKR">Pakistani rupee (&#8360;)</option>
                                                    <option value="PLN">Polish z&#x142;oty (&#122;&#322;)</option>
                                                    <option value="PRB">Transnistrian ruble (&#x440;.)</option>
                                                    <option value="PYG">Paraguayan guaran&iacute; (&#8370;)</option>
                                                    <option value="QAR">Qatari riyal (&#x631;.&#x642;)</option>
                                                    <option value="RON">Romanian leu (lei)</option>
                                                    <option value="RSD">Serbian dinar (&#x434;&#x438;&#x43d;.)</option>
                                                    <option value="RUB">Russian ruble (&#8381;)</option>
                                                    <option value="RWF">Rwandan franc (Fr)</option>
                                                    <option value="SAR">Saudi riyal (&#x631;.&#x633;)</option>
                                                    <option value="SBD">Solomon Islands dollar (&#036;)</option>
                                                    <option value="SCR">Seychellois rupee (&#x20a8;)</option>
                                                    <option value="SDG">Sudanese pound (&#x62c;.&#x633;.)</option>
                                                    <option value="SEK">Swedish krona (&#107;&#114;)</option>
                                                    <option value="SGD">Singapore dollar (&#036;)</option>
                                                    <option value="SHP">Saint Helena pound (&pound;)</option>
                                                    <option value="SLL">Sierra Leonean leone (Le)</option>
                                                    <option value="SOS">Somali shilling (Sh)</option>
                                                    <option value="SRD">Surinamese dollar (&#036;)</option>
                                                    <option value="SSP">South Sudanese pound (&pound;)</option>
                                                    <option value="STN">S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra (Db)</option>
                                                    <option value="SYP">Syrian pound (&#x644;.&#x633;)</option>
                                                    <option value="SZL">Swazi lilangeni (L)</option>
                                                    <option value="THB">Thai baht (&#3647;)</option>
                                                    <option value="TJS">Tajikistani somoni (&#x405;&#x41c;)</option>
                                                    <option value="TMT">Turkmenistan manat (m)</option>
                                                    <option value="TND">Tunisian dinar (&#x62f;.&#x62a;)</option>
                                                    <option value="TOP">Tongan pa&#x2bb;anga (T&#036;)</option>
                                                    <option value="TRY">Turkish lira (&#8378;)</option>
                                                    <option value="TTD">Trinidad and Tobago dollar (&#036;)</option>
                                                    <option value="TWD">New Taiwan dollar (&#078;&#084;&#036;)</option>
                                                    <option value="TZS">Tanzanian shilling (Sh)</option>
                                                    <option value="UAH">Ukrainian hryvnia (&#8372;)</option>
                                                    <option value="UGX">Ugandan shilling (UGX)</option>
                                                    <option value="USD">United States (US) dollar (&#036;)</option>
                                                    <option value="UYU">Uruguayan peso (&#036;)</option>
                                                    <option value="UZS">Uzbekistani som (UZS)</option>
                                                    <option value="VEF">Venezuelan bol&iacute;var (Bs F)</option>
                                                    <option value="VES">Bol&iacute;var soberano (Bs.S)</option>
                                                    <option value="VND">Vietnamese &#x111;&#x1ed3;ng (&#8363;)</option>
                                                    <option value="VUV">Vanuatu vatu (Vt)</option>
                                                    <option value="WST">Samoan t&#x101;l&#x101; (T)</option>
                                                    <option value="XAF">Central African CFA franc (CFA)</option>
                                                    <option value="XCD">East Caribbean dollar (&#036;)</option>
                                                    <option value="XOF">West African CFA franc (CFA)</option>
                                                    <option value="XPF">CFP franc (Fr)</option>
                                                    <option value="YER">Yemeni rial (&#xfdfc;)</option>
                                                    <option value="ZAR">South African rand (&#082;)</option>
                                                    <option value="ZMW">Zambian kwacha (ZK)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input v-bind:name="'currency_format[' + index + '][symbol]'" type="text" class="selectize-input" v-model="format.symbol">
                                            </td>
                                            <td>
                                                <select v-bind:name="'currency_format[' + index + '][position]'" id="" class="selectize-select" v-model="format.position" style="min-width: 180px;">
                                                    <option value="{price}">None</option>
                                                    <option value="{symbol}{price}">Left</option>
                                                    <option value="{symbol}{space}{price}">Left with space</option>
                                                    <option value="{price}{symbol}">Right</option>
                                                    <option value="{price}{space}{symbol}">Right with space</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input v-bind:name="'currency_format[' + index + '][thousands_sep]'" type="text" class="selectize-input" v-model="format.thousands_sep">
                                            </td>
                                            <td>
                                                <input v-bind:name="'currency_format[' + index + '][decimals_sep]'" type="text" class="selectize-input" v-model="format.decimals_sep">
                                            </td>
                                            <td>
                                                <input v-bind:name="'currency_format[' + index + '][decimals]'" type="number" class="selectize-input" v-model="format.decimals">
                                            </td>
                                            <td>
                                                <div class="mcw-button mcw-button-danger" v-on:click="removeFormat(index)">Remove</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                            <div class="crypto-cols">
                                <div href="javascript:void(0);" class="mcw-button mcw-button-success" v-on:click="addFormat()">+ Add Format</div>
                            </div>
                        </div>
                    </div>

                    <div id="page-license" class="page-content" v-show="menu==='license'">
                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <p><?php _e('Enter your purchase code below to activate your copy.', 'massive-cryptocurrency-widgets'); ?></p>
                                <p><?php _e('Activating the plugin unlocks additional settings, automatic future updates, and support from developers.', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_ticker"><?php _e('Status', 'massive-cryptocurrency-widgets'); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <?php if ($config['license'] != 'regular' && $config['license'] != 'extended') { ?>
                                    <div class="mcw-badge mcw-badge-dark"><?php _e('Inactive', 'massive-cryptocurrency-widgets'); ?></div><span> - <?php _e('You are not receiving automatic updates', 'massive-cryptocurrency-widgets'); ?></span>
                                <?php } else { ?>
                                <div class="mcw-badge mcw-badge-success"><?php _e('Active', 'massive-cryptocurrency-widgets'); ?></div><span> - <?php _e('You are receiving automatic updates', 'massive-cryptocurrency-widgets'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_ticker"><?php _e('Purchase Code', 'massive-cryptocurrency-widgets'); ?> (<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><?php _e('where is my purchase code?', 'massive-cryptocurrency-widgets'); ?></a>)</label>
                            </div>
                            <div class="crypto-cols">
                                <input type="text" class="selectize-input" name="license_key" v-model="opts.license_key">
                                <br><br>
                                <input type="hidden" name="license" v-model="opts.license">
                                <input type="hidden" class="mcw-license-action" name="license_action" v-model="opts.license_action">
                                <?php if ($config['license'] != 'regular' && $config['license'] != 'extended') { ?>
                                <button type="button" class="mcw-button mcw-button-success mcw-button-license" v-on:click="license('activate')"><?php _e('Activate', 'massive-cryptocurrency-widgets'); ?></button>
                                <?php } else { ?>
                                <button type="button" class="mcw-button mcw-button-danger mcw-button-license" v-on:click="license('deactivate')"><?php _e('Deactivate License', 'massive-cryptocurrency-widgets'); ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div id="page-general" class="page-content" v-show="menu==='general'">
                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_ticker"><?php _e("Number Format", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <label for="numformat1" class="form-radio">
                                    <input type="radio" class="beaut-radio" name="numformat" id="numformat1" value="US" v-model="opts.numformat" /><i class="form-icon"></i> <?php _e('US', 'massive-cryptocurrency-widgets'); ?>
                                </label>
                                <label for="numformat2" class="form-radio">
                                    <input type="radio" class="beaut-radio" name="numformat" id="numformat2" value="EU" v-model="opts.numformat" /><i class="form-icon"></i> <?php _e('European', 'massive-cryptocurrency-widgets'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_ticker"><?php _e("Cryptocurrency Pages", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <p><?php _e('Cryptocurrency pages where widgets should be linked.', 'massive-cryptocurrency-widgets'); ?>
                                <br>
                                <?php _e('Please note you need to enable links in widget settings.', 'massive-cryptocurrency-widgets'); ?>
                                <br>
                                <?php printf(__('You can <a href="%s" target="_blank">generate 2000+ coin detail pages automatically</a> or <a href="%s" target="_blank">create your own pages</a> and link them', 'massive-cryptocurrency-widgets'), 'https://codecanyon.net/item/coinpress-coinmarketcap-for-wordpress/22810400?s_rank=1', 'https://www.youtube.com/watch?v=XLF5y0L-seo'); ?></p>
                            </div>
                            <div class="crypto-cols">
                                <label for="linkto2" class="form-radio">
                                    <input type="radio" class="beaut-radio" name="linkto" id="linkto2" value="coinpress" v-model="opts.linkto" /><i class="form-icon"></i> <?php _e('Coinpress', 'massive-cryptocurrency-widgets'); ?>
                                </label>
                                <label for="linkto1" class="form-radio">
                                    <input type="radio" class="beaut-radio" name="linkto" id="linkto1" value="custom" v-model="opts.linkto" /><i class="form-icon"></i> <?php _e('Custom Pages', 'massive-cryptocurrency-widgets'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="crypto-rows link <?php echo ($this->config['linkto'] == 'custom' ? 'active' : ''); ?>">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_ticker"><?php _e("Link", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <?php _e("Link to coin names in widgets. Use <code>[symbol]</code> parameter to replace with coin symbol in url.", "massive-cryptocurrency-widgets"); ?>
                                <br><b>Examples:</b>
                                <ul>
                                    <li>/currencies/[symbol]</li>
                                    <li>/currencies/token/[symbol]</li>
                                    <li>https://anothersite.com/buy?asset=[symbol]</li>
                                </ul>
                                <input type="text" name="link" class="selectize-input coindt-url-input" v-model="opts.link">
                                <?php _e("Note: You have to create custom pages with the above URL format"); ?>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_font"><?php _e("Fonts to load", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <select id="font-select" name="fonts[]" v-model="opts.fonts" multiple>
                                <?php foreach ($this->fonts as $font) { ?>
                                <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_font"><?php _e("Custom CSS", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <textarea name="custom_css" id="mcw-css-editor" rows="5" v-model="opts.custom_css"></textarea>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_font"><?php _e("Actions", "massive-cryptocurrency-widgets"); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <div class="cmc-row between-md">
                                    <div class="cmc-md-8"><?php _e('Try deleting coins cache if something is not working well', 'massive-cryptocurrency-widgets'); ?></div>
                                    <div class="cmc-md-4 end-md"><a href="<?php echo admin_url('admin-ajax.php?action=mcw_clear_cache'); ?>" class="mcw-button mcw-button-primary"><?php _e('Delete cache', 'massive-cryptocurrency-widgets'); ?></a> </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="page-api" class="page-content" v-show="menu==='api'">

                        <div class="crypto-rows">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_api"><?php _e('Data provided by', 'massive-cryptocurrency-widgets'); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <p><?php _e('Changes take effect in about 15 minutes.', 'massive-cryptocurrency-widgets'); ?>
                                <br>
                                <?php printf(__('Please also update <a href="%s" target="_blank">Coinpress</a> plugin to latest version and select the same provider if you are using it.', 'massive-cryptocurrency-widgets'), 'https://codecanyon.net/item/coinpress-coinmarketcap-for-wordpress/22810400'); ?></p>
                            </div>
                            <div class="crypto-cols">

                                <?php 

                                $providers = apply_filters('mcw_get_providers', $this->providers); 
                                foreach ($providers as $provider => $img) { ?>

                                <label for="api-<?php echo $provider; ?>" class="form-radio">
                                <img height="35" src="<?php echo $img; ?>" alt="">
                                    <input type="radio" class="beaut-radio api-select" name="api" id="api-<?php echo $provider; ?>" value="<?php echo $provider; ?>" v-model="opts.api" /><i class="form-icon"></i>
                                </label>

                                <?php } ?>
                            </div>
                    
                        </div>

                        <div class="crypto-rows" v-show="opts.api==='coinmarketcap'">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_api_key"><?php _e('API Key'); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <input type="text" class="selectize-input" name="api_key" v-model="opts.api_key">
                            </div>
                            <div class="crypto-cols">
                                <p><?php printf(__('Get your Coinmarketcap.com API key <a href="%s" target="_blank">here</a>', 'massive-cryptocurrency-widgets'), 'https://coinmarketcap.com/api/'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows" v-show="opts.api==='coinmarketcap'">
                            <div class="crypto-cols crypto-labels">
                                <label for="crypto_api_interval"><?php _e('Refresh data every'); ?></label>
                            </div>
                            <div class="crypto-cols">
                                <select name="api_interval" id="" class="selectize-select" v-model="opts.api_interval">
                                    <option value="300">5 <?php _e('Minutes', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="600">10 <?php _e('Minutes', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="1200">20 <?php _e('Minutes', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="1800">30 <?php _e('Minutes', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="3600">1 <?php _e('Hour', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="10800">3 <?php _e('Hours', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="21600">6 <?php _e('Hours', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="43200">12 <?php _e('Hours', 'massive-cryptocurrency-widgets'); ?></option>
                                    <option value="86400">1 <?php _e('Day', 'massive-cryptocurrency-widgets'); ?></option>
                                </select>
                            </div>
                            <div class="crypto-cols">
                                <p><?php _e('On average, one call to get latest prices uses 11 api credits. Please calculate optimal refresh interval based on your plan.', 'massive-cryptocurrency-widgets'); ?></p>
                                <p><?php _e('Recommended options: Basic: 1 Hour+, Hobbyist: 20 Minutes+, Startup: 5 Minutes+', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                    </div>

                    <div id="page-shortcodes" class="page-content" v-show="menu==='shortcodes'">
                
                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <h4><?php _e('Text Shortcodes', 'massive-cryptocurrency-widgets'); ?></h4>
                                <p><?php _e('Shortcodes which display information without any styling', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC"]</div>
                                <p><?php _e('Get coin price in US dollar', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" multiply="0.95"]</div>
                                <p><?php _e('Multiply the current market price by number', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" currency="EUR"]</div>
                                <p><?php _e('Get price in another fiat currency', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" info="rank"]</div>
                                <p><?php _e('Get rank of coin', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="LTC" info="pricebtc"]</div>
                                <p><?php _e('Get coin price in bitcoin', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" info="volume"]</div>
                                <p><?php _e('Get volume', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" info="marketcap"]</div>
                                <p><?php _e('Get marketcap', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" info="marketcap" format="symbol"]</div>
                                <p><?php _e('Get marketcap in symbol format. Available options: symbol, text, number', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" info="change"]</div>
                                <p><?php _e('Get price change percentage', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                        <div class="crypto-rows">
                            <div class="crypto-cols">
                                <div class="code">[mcrypto coin="BTC" realtime="off"]</div>
                                <p><?php _e('Turn off realtime update', 'massive-cryptocurrency-widgets'); ?></p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    
    </form>

</template>