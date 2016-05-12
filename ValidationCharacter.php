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
}
