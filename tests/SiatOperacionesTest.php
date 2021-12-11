<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatOperacionesTest extends TestCase
{
    private static $siat;
    private static $cuis;
    private static $cufd;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['SIAT_USER'];
        $credentials->password = $_ENV['SIAT_PASSWORD'];
        $auth = new Auth($_ENV['SIAT_NIT'], $credentials);
        $accessToken = $auth->getAccessToken();
        self::$siat = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $accessToken);
        self::$cuis = self::$siat->solicitarCUIS()->codigo;
        self::$cufd = self::$siat->solicitarCUFD(self::$cuis)->codigo;
    }

    public function testRegistroEventoSignificativo()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            1,
            'CORTE DEL SERVICIO DE INTERNET',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroPuntoVenta()
    {
        date_default_timezone_set('America/La_Paz');
        $puntoVenta = new PuntoVenta(
            2,
            'nombrePuntoVenta1',
            'descripcion'
        );
        $response = self::$siat->registroPuntoVenta(
            self::$cuis,
            $puntoVenta
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoPuntoVenta);
    }

    public function testConsultaPuntoVenta()
    {
        $response = self::$siat->consultaPuntoVenta(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoPuntoVenta);
        $this->assertIsString($response[0]->nombrePuntoVenta);
        $this->assertIsString($response[0]->tipoPuntoVenta);
    }

    public function testCierrePuntoVenta()
    {
        $response = self::$siat->cierrePuntoVenta(self::$cuis);
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoPuntoVenta);
    }
}
