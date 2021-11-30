<?php

namespace Enors\Siat\Utils;

/*
* Test tool: https://www.rapidtables.com/convert/number/decimal-to-hex.html
*/
class Base16
{
    /*
    * Encode a decimal number to base 16
    * @param number string to encode
    * @return string base 16
    */
    public static function encode($number)
    {
        $hexvalues = array('0','1','2','3','4','5','6','7',
                   '8','9','A','B','C','D','E','F');
        $hexval = '';
        while ($number != '0') {
            $hexval = $hexvalues[bcmod($number, '16')] . $hexval;
            $number = bcdiv($number, '16', 0);
        }
        return $hexval;
    }

    /*
    * Decode a hexadecimal number to base 10
    * @param number string to decode
    * @return string decoded string
    */
    public static function decode($hex)
    {
        $decvalues = array('0' => '0', '1' => '1', '2' => '2',
                   '3' => '3', '4' => '4', '5' => '5',
                   '6' => '6', '7' => '7', '8' => '8',
                   '9' => '9', 'A' => '10', 'B' => '11',
                   'C' => '12', 'D' => '13', 'E' => '14',
                   'F' => '15');
        $decval = '0';
        $number = strrev($hex);
        for ($i = 0; $i < strlen($hex); $i++) {
            $decval = bcadd(bcmul(bcpow('16', $i, 0), $decvalues[$hex[$i]]), $decval);
        }
        return $decval;
    }
}
