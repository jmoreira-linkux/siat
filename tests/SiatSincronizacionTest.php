<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatSincronizacionTest extends TestCase
{
    private static $siat0;
    private static $siat1;
    private static $cuis0;
    private static $cuis1;

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
    }

    public function testSincronizarActividades()
    {
        $response0 = self::$siat0->sincronizarActividades(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertNotEmpty($response0[0]->codigoCaeb);
        $this->assertNotEmpty($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarActividades(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertNotEmpty($response1[0]->codigoCaeb);
        $this->assertNotEmpty($response1[0]->descripcion);
    }

    public function testSincronizarFechaHora()
    {
        $fechaHora0 = self::$siat0->sincronizarFechaHora(self::$cuis0);
        $this->assertIsString($fechaHora0);
        $this->assertNotEmpty($fechaHora0);

        $fechaHora1 = self::$siat1->sincronizarFechaHora(self::$cuis1);
        $this->assertIsString($fechaHora1);
        $this->assertNotEmpty($fechaHora1);
    }

    public function testSincronizarActividadesDocumentoSector()
    {
        $response0 = self::$siat0->sincronizarActividadesDocumentoSector(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertNotEmpty($response0[0]->codigoActividad);
        $this->assertIsInt($response0[0]->codigoDocumentoSector);
        $this->assertNotEmpty($response0[0]->tipoDocumentoSector);

        $response1 = self::$siat1->sincronizarActividadesDocumentoSector(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertNotEmpty($response1[0]->codigoActividad);
        $this->assertIsInt($response1[0]->codigoDocumentoSector);
        $this->assertNotEmpty($response1[0]->tipoDocumentoSector);
    }

    public function testSincronizarListaLeyendasFactura()
    {
        $response0 = self::$siat0->sincronizarListaLeyendasFactura(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsString($response0[0]->codigoActividad);
        $this->assertIsString($response0[0]->descripcionLeyenda);

        $response1 = self::$siat1->sincronizarListaLeyendasFactura(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsString($response1[0]->codigoActividad);
        $this->assertIsString($response1[0]->descripcionLeyenda);
    }

    public function testSincronizarListaMensajesServicios()
    {
        $response0 = self::$siat0->sincronizarListaMensajesServicios(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarListaMensajesServicios(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaListaProductosServicios()
    {
        $response0 = self::$siat0->sincronizarParametricaListaProductosServicios(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsString($response0[0]->codigoActividad);
        $this->assertIsInt($response0[0]->codigoProducto);
        $this->assertIsString($response0[0]->descripcionProducto);

        $response1 = self::$siat1->sincronizarParametricaListaProductosServicios(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsString($response1[0]->codigoActividad);
        $this->assertIsInt($response1[0]->codigoProducto);
        $this->assertIsString($response1[0]->descripcionProducto);
    }

    public function testSincronizarParametricaEventosSignificativos()
    {
        $response0 = self::$siat0->sincronizarParametricaEventosSignificativos(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaEventosSignificativos(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaMotivoAnulacion()
    {
        $response0 = self::$siat0->sincronizarParametricaMotivoAnulacion(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaMotivoAnulacion(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaPaisOrigen()
    {
        $response0 = self::$siat0->sincronizarParametricaPaisOrigen(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaPaisOrigen(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoDocumentoIdentidad()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoDocumentoSector()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoDocumentoSector(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoDocumentoSector(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoEmision()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoEmision(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoEmision(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoHabitacion()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoHabitacion(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoHabitacion(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMetodoPago()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoMetodoPago(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoMetodoPago(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMoneda()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoMoneda(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoMoneda(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTipoPuntoVenta()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoPuntoVenta(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTipoPuntoVenta(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaTiposFactura()
    {
        $response0 = self::$siat0->sincronizarParametricaTiposFactura(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaTiposFactura(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }

    public function testSincronizarParametricaUnidadMedida()
    {
        $response0 = self::$siat0->sincronizarParametricaUnidadMedida(self::$cuis0);
        $this->assertGreaterThan(0, count($response0));
        $this->assertIsInt($response0[0]->codigoClasificador);
        $this->assertIsString($response0[0]->descripcion);

        $response1 = self::$siat1->sincronizarParametricaUnidadMedida(self::$cuis1);
        $this->assertGreaterThan(0, count($response1));
        $this->assertIsInt($response1[0]->codigoClasificador);
        $this->assertIsString($response1[0]->descripcion);
    }
}
