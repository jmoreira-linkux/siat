<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    private static $accessToken;
    private static $cuis;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['USER'];
        $credentials->password = $_ENV['PASSWORD'];
        $auth = new Auth($_ENV['NIT'], $credentials);
        self::$accessToken = $auth->getAccessToken();
        $siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], self::$accessToken);
        self::$cuis = $siat->solicitarCUIS()->codigo;
    }

    public function testSolicitarCUIS()
    {
        $siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], self::$accessToken);
        $cuis = $siat->solicitarCUIS();
        $this->assertNotEmpty($cuis->codigo);
        $this->assertNotEmpty($cuis->fechaVigencia);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], self::$accessToken);
        $cufd = $siat->solicitarCUFD(self::$cuis);
        $this->assertNotEmpty($cufd->codigo);
        $this->assertNotEmpty($cufd->codigoControl);
    }

    public function testGenerarCUF()
    {
        $nit = 123456789;
        $siat = new Siat('', $nit, '', Siat::MODALIDAD_ELECTRONICA_EN_LINEA);
        $timestamp = strtotime('2019-01-13 16:37:21');
        $nroFactura = 1;
        $cufd = 'A19E23EF34124CD';
        $cuf = $siat->generarCUF($timestamp, $nroFactura, $cufd);
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }
}
