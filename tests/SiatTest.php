<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    private static $siat;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['USERNAME'];
        $credentials->password = $_ENV['PASSWORD'];
        $auth = new Auth($_ENV['NIT'], $credentials);
        $accessToken = $auth->getAccessToken();
        self::$siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], $accessToken);
    }

    public function testSolicitarCUIS()
    {
        $cuis = self::$siat->solicitarCUIS();
        $this->assertNotEmpty($cuis->codigo);
        $this->assertNotEmpty($cuis->fechaVigencia);
    }

    public function testSolicitarCUFD()
    {
        $cufd = self::$siat->solicitarCUFD('');
        $this->assertEquals('', $cufd);
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
