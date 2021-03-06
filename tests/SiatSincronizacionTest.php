<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatSincronizacionTest extends TestCase
{
    private static $siat0;
    private static $cuis0;

    public static function setUpBeforeClass(): void
    {
        $siatcodigos = new SiatCodigos($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        self::$cuis0 = $siatcodigos->solicitarCUIS()->codigo;
        self::$siat0 = new SiatSincronizacion($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
    }

    public function testSincronizarActividades()
    {
        $response0 = self::$siat0->sincronizarActividades(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaActividades));
        $this->assertNotEmpty($response0->listaActividades[0]->codigoCaeb);
        $this->assertNotEmpty($response0->listaActividades[0]->descripcion);
    }

    public function testSincronizarFechaHora()
    {
        $fechaHora0 = self::$siat0->sincronizarFechaHora(self::$cuis0);
        $this->assertIsString($fechaHora0->fechaHora);
        $this->assertNotEmpty($fechaHora0->fechaHora);
    }

    public function testSincronizarActividadesDocumentoSector()
    {
        $response0 = self::$siat0->sincronizarActividadesDocumentoSector(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaActividadesDocumentoSector));
        $this->assertNotEmpty($response0->listaActividadesDocumentoSector[0]->codigoActividad);
        $this->assertIsInt($response0->listaActividadesDocumentoSector[0]->codigoDocumentoSector);
        $this->assertNotEmpty($response0->listaActividadesDocumentoSector[0]->tipoDocumentoSector);
    }

    public function testSincronizarListaLeyendasFactura()
    {
        $response0 = self::$siat0->sincronizarListaLeyendasFactura(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaLeyendas));
        $this->assertIsString($response0->listaLeyendas[0]->codigoActividad);
        $this->assertIsString($response0->listaLeyendas[0]->descripcionLeyenda);
    }

    public function testSincronizarListaMensajesServicios()
    {
        $response0 = self::$siat0->sincronizarListaMensajesServicios(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaListaProductosServicios()
    {
        $response0 = self::$siat0->sincronizarParametricaListaProductosServicios(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsString($response0->listaCodigos[0]->codigoActividad);
        $this->assertIsInt($response0->listaCodigos[0]->codigoProducto);
        $this->assertIsString($response0->listaCodigos[0]->descripcionProducto);
    }

    public function testSincronizarParametricaEventosSignificativos()
    {
        $response0 = self::$siat0->sincronizarParametricaEventosSignificativos(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaMotivoAnulacion()
    {
        $response0 = self::$siat0->sincronizarParametricaMotivoAnulacion(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaPaisOrigen()
    {
        $response0 = self::$siat0->sincronizarParametricaPaisOrigen(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoDocumentoIdentidad()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoDocumentoSector()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoDocumentoSector(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoEmision()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoEmision(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoHabitacion()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoHabitacion(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMetodoPago()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoMetodoPago(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMoneda()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoMoneda(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTipoPuntoVenta()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoPuntoVenta(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaTiposFactura()
    {
        $response0 = self::$siat0->sincronizarParametricaTipoFactura(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }

    public function testSincronizarParametricaUnidadMedida()
    {
        $response0 = self::$siat0->sincronizarParametricaUnidadMedida(self::$cuis0);
        $this->assertGreaterThan(0, count($response0->listaCodigos));
        $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
        $this->assertIsString($response0->listaCodigos[0]->descripcion);
    }
}
