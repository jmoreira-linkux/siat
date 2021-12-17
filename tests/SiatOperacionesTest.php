<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatOperacionesTest extends TestCase
{
    private static $siat0;
    private static $siat1;
    private static $cuis0;
    private static $cuis1;
    private static $cufd0;
    private static $cufd1;

    const CODIGO_PUNTO_VENTA = 3;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['SIAT_USER'];
        $credentials->password = $_ENV['SIAT_PASSWORD'];
        $auth = new Auth($_ENV['SIAT_NIT'], $credentials);
        $accessToken = $auth->getAccessToken();
        self::$siat0 = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $accessToken);
        self::$siat1 = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $accessToken);
        self::$siat1->codigoPuntoVenta = self::CODIGO_PUNTO_VENTA;
        self::$cuis0 = self::$siat0->solicitarCUIS()->codigo;
        self::$cuis1 = self::$siat1->solicitarCUIS()->codigo;
        self::$cufd0 = self::$siat0->solicitarCUFD(self::$cuis0)->codigo;
        self::$cufd1 = self::$siat1->solicitarCUFD(self::$cuis1)->codigo;
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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

    //     $response1 = self::$siat1->registroEventoSignificativo(
    //         self::$cuis1,
    //         self::$cufd1,
    //         $eventoSignificativo
    //     );
    //     $this->assertIsBool($response1->transaccion);
    //     $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
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
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoPuntoVenta);
        $this->assertIsString($response0[0]->nombrePuntoVenta);
        $this->assertIsString($response0[0]->tipoPuntoVenta);
    }
}
