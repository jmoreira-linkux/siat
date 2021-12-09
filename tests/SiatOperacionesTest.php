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
        $credentials->username = $_ENV['USER'];
        $credentials->password = $_ENV['PASSWORD'];
        $auth = new Auth($_ENV['NIT'], $credentials);
        $accessToken = $auth->getAccessToken();
        self::$siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], $accessToken);
        self::$cuis = self::$siat->solicitarCUIS()->codigo;
        self::$cufd = self::$siat->solicitarCUFD(self::$cuis)->codigo;
    }

    public function testRegistroEventoSignificativo()
    {
        date_default_timezone_set('America/La_Paz');
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            'CORTE DEL SERVICIO DE INTERNET',
            date(
                "Y-m-d\TH:i:s.v"
            ),
            date(
                "Y-m-d\TH:i:s.v",
                strtotime(
                    '+3 hours',
                    strtotime(
                        date("Y-m-d\TH:i:s.v")
                    )
                )
            ),
            0
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroPuntoVenta()
    {
        date_default_timezone_set('America/La_Paz');
        $response = self::$siat->registroPuntoVenta(
            self::$cuis,
            "descripción",
            "nombrePuntoVenta1",
            2
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoPuntoVenta);
    }
}
