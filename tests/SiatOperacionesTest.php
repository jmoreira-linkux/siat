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

    public function testRegistroEventoSignificativo11()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            1,
            'CORTE DEL SERVICIO DE INTERNET',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo20()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            2,
            'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
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

    public function testRegistroEventoSignificativo21()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            2,
            'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo30()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            3,
            'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
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

    public function testRegistroEventoSignificativo31()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            3,
            'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo40()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            4,
            'VENTA EN LUGARES SIN INTERNET',
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

    public function testRegistroEventoSignificativo41()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            4,
            'VENTA EN LUGARES SIN INTERNET',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo50()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            5,
            'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
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

    public function testRegistroEventoSignificativo51()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            5,
            'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo60()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            6,
            'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
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

    public function testRegistroEventoSignificativo61()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            6,
            'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
        $response = self::$siat->registroEventoSignificativo(
            self::$cuis,
            self::$cufd,
            $eventoSignificativo
        );
        $this->assertIsBool($response->transaccion);
        $this->assertIsInt($response->codigoRecepcionEventoSignificativo);
    }

    public function testRegistroEventoSignificativo70()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            7,
            'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
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

    public function testRegistroEventoSignificativo71()
    {
        date_default_timezone_set('America/La_Paz');
        $eventoSignificativo = new EventoSignificativo(
            7,
            'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
            date('Y-m-d\TH:i:s.v'),
            date('Y-m-d\TH:i:s.v', strtotime('+3 hours', strtotime(date("Y-m-d\TH:i:s.v"))))
        );
        self::$siat->codigoPuntoVenta = 1;
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

    public function testCierreOperacionesSistema()
    {
        $response = self::$siat->cierreOperacionesSistema(self::$cuis);
        $this->assertIsBool($response->transaccion);
        $this->assertIsString($response->codigoSistema);
    }
}
