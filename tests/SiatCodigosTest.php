<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatCodigosTest extends TestCase
{
    private static $accessToken;
    private static $cuis;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['SIAT_USER'];
        $credentials->password = $_ENV['SIAT_PASSWORD'];
        $auth = new Auth($_ENV['SIAT_NIT'], $credentials);
        self::$accessToken = $auth->getAccessToken();
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], self::$accessToken);
        self::$cuis = $siat->solicitarCUIS()->codigo;
    }

    public function testSolicitarCUIS()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], self::$accessToken);
        $cuis = $siat->solicitarCUIS();
        $this->assertNotEmpty($cuis->codigo);
        $this->assertNotEmpty($cuis->fechaVigencia);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], self::$accessToken);
        $cufd = $siat->solicitarCUFD(self::$cuis);
        $this->assertNotEmpty($cufd->codigo);
        $this->assertNotEmpty($cufd->codigoControl);
    }
}
