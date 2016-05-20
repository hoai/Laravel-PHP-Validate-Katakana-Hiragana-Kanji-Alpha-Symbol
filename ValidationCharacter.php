<?php namespace App\Utils\Validations;

trait ValidationCharacter
{
    /**
     * Validate In Code
     * @author HMLong
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @SuppressWarnings("UnusedFormalParameter")
     * @return boolean
     */
    protected function validateInCode($attribute, $value, $parameters)
    {
        if (empty($parameters)) {
            return false;
        }

        $pattern = [];
        foreach ($parameters as $code) {
            $pattern[] = \Config::get('validation.' . $code);
        }
        $flag = preg_match("/^(" . implode('|', $pattern) . ")*$/", $value);
        if (in_array('kanji', $parameters) && !$flag) {
            /* Special Case for Kanji - Can not preg_match  */
            $value = preg_replace("/(" . implode('|', $pattern) . ")*/", '', $value);
            $flag = $this->validateKanji($attribute, $value);
        }

        return $flag;
    }

    /**
     * HMLong - Validate Kanji
     * Kanji level 1: 0x889F - 0x9872
     * Kanji level 2: 0x989F - 0xEAA4
     * @param String $attribute
     * @param mixed $value
     * @SuppressWarnings("UnusedFormalParameter")
     * @return boolean
     */
    protected function validateKanji($attribute, $value)
    {
        $length = mb_strlen($value, 'UTF-8');
        for ($counter = 0; $counter < $length; $counter++) {
            $char = mb_substr($value, $counter, 1, 'UTF-8');
            $char = mb_convert_encoding($char, "SJIS", 'UTF-8');
            $hex = bin2hex($char);
            $hex = hexdec($hex);
            if (!((0x889F <= $hex && $hex <= 0x9872) || (0x989F <= $hex && $hex <= 0xEAA4))) {
                return false;
            }
        }

        return true;
    }
    /**
     * HMLong - Validate Full Width | 2 byte | zenkaku_only
     * @param String $attribute
     * @param mixed $value
     * @SuppressWarnings("UnusedFormalParameter")
     * @return boolean
     */
    protected function validateFullWidth($attribute, $value)
    {
        $encoding = "UTF-8";
        // Get length of string
        $len = mb_strlen($value, $encoding);
        // Check each character
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($value, $i, 1, $encoding);
            // Check for non-printable characters
            if (ctype_print($char)) {
                return false;
            }
            // Convert to SHIFT-JIS to include kana characters
            $char = mb_convert_encoding($char, 'SJIS', $encoding);
            // Check if string lengths match
            if (strlen($char) === mb_strlen($char, 'SJIS')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Replace Message for Special Case on InCode
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return string
     */
    protected function replaceInCode($message, $attribute, $rule, $parameters)
    {
        $value = \Request::get($attribute, \Request::get('data'));
        $halfWidthKata = '/^([ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞ])*$/';
        $halfWidthNumber = '/^([0-9])*$/';
        $halfWidthAlpha = '/^([a-zA-Z])*$/';
        $halfWidthSymbol = "/^[ !\"#$%&'()*+,-.\/:;<=>?@\]\[\\\^_`{|}~]+$/u";
        if (
            (in_array('katakana', $parameters) && preg_match($halfWidthKata, $value)) ||
            (in_array('fullwidth_number', $parameters) && preg_match($halfWidthNumber, $value)) ||
            (in_array('fullwidth_alpha', $parameters) && preg_match($halfWidthAlpha, $value)) ||
            (in_array('fullwidth_symbol', $parameters) && preg_match($halfWidthSymbol, $value))
        ) {
            $message = '全角で入力してください';
        }

        return $message;
    }
}
