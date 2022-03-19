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
        $siatcodigos = new SiatCodigos($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        self::$cuis0 = $siatcodigos->solicitarCUIS()->codigo;
        self::$cufd0 = $siatcodigos->solicitarCUFD(self::$cuis0)->codigo;
        self::$siat0 = new SiatOperaciones($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
    }

    // public function testConsultaEventoSignificativo()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $response0 = self::$siat0->consultaEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         time()
    //     );
    //     var_dump($response0);
    // }

    // public function testRegistroEventoSignificativo1()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         1,
    //         'CORTE DEL SERVICIO DE INTERNET',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo2()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         2,
    //         'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo3()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         3,
    //         'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo4()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         4,
    //         'VENTA EN LUGARES SIN INTERNET',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo5()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         5,
    //         'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo6()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         6,
    //         'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
    // }

    // public function testRegistroEventoSignificativo7()
    // {
    //     date_default_timezone_set('America/La_Paz');
    //     $eventoSignificativo = new EventoSignificativo(
    //         7,
    //         'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
    //         time(),
    //         strtotime('+5 seconds')
    //     );
    //     $response0 = self::$siat0->registroEventoSignificativo(
    //         self::$cuis0,
    //         self::$cufd0,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response0->transaccion);
    //     $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
    //     sleep(6);
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
