<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatOperacionesTest extends TestCase
{
    private static $siat0;
    private static $cuis0;
    private static $cufd0;

    const CODIGO_PUNTO_VENTA = 1;

    public static function setUpBeforeClass(): void
    {
        self::$siat0 = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        self::$cuis0 = self::$siat0->solicitarCUIS()->codigo;
        self::$cufd0 = self::$siat0->solicitarCUFD(self::$cuis0)->codigo;
    }

    // public function testRegistroEventoSignificativo1()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         1,
    //         'CORTE DEL SERVICIO DE INTERNET',
    //         time(),
    //         strtotime('+5 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo2()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         2,
    //         'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
    //         strtotime('+5 minutes'),
    //         strtotime('+10 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo3()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         3,
    //         'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
    //         strtotime('+15 minutes'),
    //         strtotime('+20 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo4()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         4,
    //         'VENTA EN LUGARES SIN INTERNET',
    //         strtotime('+25 minutes'),
    //         strtotime('+30 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo5()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         5,
    //         'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
    //         strtotime('+35 minutes'),
    //         strtotime('+40 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo6()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         6,
    //         'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
    //         strtotime('+45 minutes'),
    //         strtotime('+50 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    // public function testRegistroEventoSignificativo7()
    // {
    //     $eventoSignificativo = new EventoSignificativo(
    //         7,
    //         'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
    //         strtotime('+55 minutes'),
    //         strtotime('+60 minutes')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    // }

    public function testRegistroPuntoVenta()
    {
        $puntoVenta = new PuntoVenta(
            2,
            'nombrePuntoVenta1',
            'descripcion'
        );
        $response0 = self::$siat0->registroPuntoVenta(
            self::$cuis0,
            $puntoVenta
        );
        $this->assertIsBool($response0->transaccion);
        $this->assertIsInt($response0->codigoPuntoVenta);
    }

    public function testConsultaPuntoVenta()
    {
        $response0 = self::$siat0->consultaPuntoVenta(self::$cuis0);
        $this->assertEquals(true, $response0->transaccion);
        if (is_array($response0->listaPuntosVentas)) {
            $this->assertGreaterThan(0, count($response0->listaPuntosVentas));
            $this->assertIsInt($response0->listaPuntosVentas[0]->codigoPuntoVenta);
            $this->assertIsString($response0->listaPuntosVentas[0]->nombrePuntoVenta);
            $this->assertIsString($response0->listaPuntosVentas[0]->tipoPuntoVenta);
        } else {
            $this->assertIsInt($response0->listaPuntosVentas->codigoPuntoVenta);
            $this->assertIsString($response0->listaPuntosVentas->nombrePuntoVenta);
            $this->assertIsString($response0->listaPuntosVentas->tipoPuntoVenta);
        }
    }
}
