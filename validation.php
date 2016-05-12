<?php
/**
 * File use to validate constants define
 *
 */

return [
    /*
     * HMLong
     * Check Katakana Base on Document from Japan
     * Ref: http://www.utf8-chartable.de/unicode-utf8-table.pl?start=12288&names=-&utf8=string-literal]
     * U+30A1 - U+30BF => \xe3\x82\xa1 - \xe3\x82\xbf
     * U+30C0 - U+30F6 => \xe3\x83\x80 - \xe3\x83\xb6
     * U+30FC - U+30FE => \xe3\x83\xbc - \xe3\x83\xbe
     */
    'katakana'               => '(' .
        '\xe3\x82[\xa1-\xbf]|' .
        '\xe3\x83[\x80-\xb6]|' .
        '\xe3\x83[\xbc-\xbe]' .
        ')',
    /*
     * HMLong
     * Check Hiragana Base on Document from Japan
     * Ref: http://www.utf8-chartable.de/unicode-utf8-table.pl?start=12288&names=-&utf8=string-literal]
     * U+3041 - U+307F => \xe3\x81\x81 - \xe3\x81\xbf
     * U+3080 - U+3094 => \xe3\x82\x80 - \xe3\x82\x94
     * U+309D - U+309E => \xe3\x82\x9d - \xe3\x82\x9e
     */
    'hiragana'               => '(' .
        '\xe3\x81[\x81-\xbf]|' .
        '\xe3\x82[\x80-\x94]|' .
        '\xe3\x82[\x9d-\x9e]' .
        ')',
    /*
     * HMLong
     * Check Full Width Alpha (2 byte)
     * Ref: http://www.utf8-chartable.de/unicode-utf8-table.pl
     * U+FF21 - U+FF3A => \xef\xbc\xa1 - \xef\xbc\xba
     * U+FF40 - U+FF5A => \xef\xbd\x80 - \xef\xbd\x9a
     */
    'fullwidth_alpha'        => '(' .
        '\xef\xbc[\xa1-\xba]|' .
        '\xef\xbd[\x80-\x9a]' .
        ')',
    /*
    * HMLong
    * Check Full Width Number (2 byte)
    * Ref: http://www.utf8-chartable.de/unicode-utf8-table.pl
    * U+FF10 - U+FF19 => \xef\xbc\x90 - \xef\xbc\x99
    */
    'fullwidth_number'       => '(\xef\xbc[\x90-\x99])',
    /*
    * HMLong
    * Check Full Width Symbol (2 byte)
    * Ref: http://www.utf8-chartable.de/unicode-utf8-table.pl
    * U+3000 - U+3003 => e3 80 80 - e3 80 83
    * U+3005 - U+3015 => e3 80 85 - e3 80 95
    * U+301C => e3 80 9c
    * U+FF01 - U+FF0F => ef bc 81 - ef bc 8f
    * U+FF1A - U+FF20 => ef bc 9a - ef bc a0
    * U+FF3B - U+FF3F => ef bc bb - ef bc bf
    * U+FF5B - U+FF5E => ef bd 9b - ef bd 9e
    * U+FFE0 - U+FFE5 => ef bf a0 - ef bf a5
    */
    'fullwidth_symbol'       => '(' .
        '\xe3\x80[\x80-\x83]|' .
        '\xe3\x80[\x85-\x95]|' .
        '\xe3\x80\x9c|' .
        '\xef\xbc[\x81-\x8f]|' .
        '\xef\xbc[\x9a-\xa0]|' .
        '\xef\xbc[\xbb-\xbf]|' .
        '\xef\xbd[\x9b-\x9e]|' .
        '\xef\xbf[\xa0-\xa5]' .
        ')',
];
