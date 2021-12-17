<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatCodigosTest extends TestCase
{
    private static $accessToken;
    const CODIGO_PUNTO_VENTA = 3;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['SIAT_USER'];
        $credentials->password = $_ENV['SIAT_PASSWORD'];
        $auth = new Auth($_ENV['SIAT_NIT'], $credentials);
        self::$accessToken = $auth->getAccessToken();
    }

    public function testSolicitarCUIS()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], self::$accessToken);
        $cuis0 = $siat->solicitarCUIS();
        $this->assertNotEmpty($cuis0->codigo);
        $this->assertNotEmpty($cuis0->fechaVigencia);

        $siat->codigoPuntoVenta = self::CODIGO_PUNTO_VENTA;
        $cuis1 = $siat->solicitarCUIS();
        $this->assertNotEmpty($cuis1->codigo);
        $this->assertNotEmpty($cuis1->fechaVigencia);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], self::$accessToken);
        $cuis0 = $siat->solicitarCUIS();
        $cufd0 = $siat->solicitarCUFD($cuis0->codigo);
        $this->assertNotEmpty($cufd0->codigo);
        $this->assertNotEmpty($cufd0->codigoControl);

        $siat->codigoPuntoVenta = self::CODIGO_PUNTO_VENTA;
        $cuis1 = $siat->solicitarCUIS();
        $cufd1 = $siat->solicitarCUFD($cuis1->codigo);
        $this->assertNotEmpty($cufd1->codigo);
        $this->assertNotEmpty($cufd1->codigoControl);
    }
}
