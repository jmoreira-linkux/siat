<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    public function testGenerarCUF()
    {
        $nit = 123456789;
        $siat = new Siat('', $nit, '', 0, 0, Siat::MODALIDAD_ELECTRONICA_EN_LINEA);
        $timestamp = strtotime('2019-01-13 16:37:21');
        $nroFactura = 1;
        $cufd = 'A19E23EF34124CD';
        $cuf = $siat->generarCUF($timestamp, $nroFactura, $cufd);
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }
}
