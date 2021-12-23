<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatCodigosTest extends TestCase
{
    const CODIGO_PUNTO_VENTA = 1;

    public function testSolicitarCUIS()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        $cuis0 = $siat->solicitarCUIS();
        $this->assertNotEmpty($cuis0->codigo);
        $this->assertNotEmpty($cuis0->fechaVigencia);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        $cuis0 = $siat->solicitarCUIS();
        $cufd0 = $siat->solicitarCUFD($cuis0->codigo);
        $this->assertNotEmpty($cufd0->codigo);
        $this->assertNotEmpty($cufd0->codigoControl);
    }
}
