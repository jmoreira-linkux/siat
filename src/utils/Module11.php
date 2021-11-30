<?php

namespace Enors\Siat\Utils;

class Module11
{
    /*
    * Generate module 11
    * https://siatinfo.impuestos.gob.bo/index.php/facturacion-en-linea/algoritmos-utilizados/algoritmo-modulo-11
    * @return
    */
    public static function generate($str, $num, $lim, $x10)
    {
        $mult = $soma = $i = $n = $dig = 0;
        if (!$x10) {
            $num = 1;
        }

        for ($n = 1; $n <= $num; $n++) {
            $soma = 0;
            $mult = 2;

            for ($i = strlen($str) - 1; $i >= 0; $i--) {
                $soma += ($mult * (int)substr($str, $i, 1));
                if (++$mult > $lim) {
                    $mult = 2;
                }
            }

            if ($x10) {
                $dig = (($soma * 10) % 11) % 10;
            } else {
                $dig = $soma % 11;
            }

            if ($dig == 10) {
                $str .= "1";
            }

            if ($dig == 11) {
                $str .= "0";
            }

            if ($dig < 10) {
                $str .= $dig;
            }
        }
        return substr($str, strlen($str) - $num, strlen($str));
    }
}
