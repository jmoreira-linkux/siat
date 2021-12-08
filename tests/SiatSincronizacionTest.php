<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class SiatSincronizacionTest extends TestCase
{
    private static $siat;
    private static $cuis;

    public static function setUpBeforeClass(): void
    {
        $credentials = new \stdClass;
        $credentials->username = $_ENV['USER'];
        $credentials->password = $_ENV['PASSWORD'];
        $auth = new Auth($_ENV['NIT'], $credentials);
        $accessToken = $auth->getAccessToken();
        self::$siat = new Siat($_ENV['CODIGO_SISTEMA'], $_ENV['NIT'], $accessToken);
        self::$cuis = self::$siat->solicitarCUIS()->codigo;
    }

    public function testSincronizarFechaHora()
    {
        $fechaHora = self::$siat->sincronizarFechaHora(self::$cuis);
        $this->assertIsString($fechaHora);
        $this->assertNotEmpty($fechaHora);
    }

    public function testSincronizarParametricaPaisOrigen()
    {
        $response = self::$siat->sincronizarParametricaPaisOrigen(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaUnidadMedida()
    {
        $response = self::$siat->sincronizarParametricaUnidadMedida(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarListaLeyendasFactura()
    {
        $response = self::$siat->sincronizarListaLeyendasFactura(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsString($response[0]->codigoActividad);
        $this->assertIsString($response[0]->descripcionLeyenda);
    }

    public function testSincronizarParametricaEventosSignificativos()
    {
        $response = self::$siat->sincronizarParametricaEventosSignificativos(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaMotivoAnulacion()
    {
        $response = self::$siat->sincronizarParametricaMotivoAnulacion(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaTipoDocumentoIdentidad()
    {
        $response = self::$siat->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMetodoPago()
    {
        $response = self::$siat->sincronizarParametricaTipoMetodoPago(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaTipoMoneda()
    {
        $response = self::$siat->sincronizarParametricaTipoMoneda(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }

    public function testSincronizarParametricaListaProductosServicios()
    {
        $response = self::$siat->sincronizarParametricaListaProductosServicios(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsString($response[0]->codigoActividad);
        $this->assertIsInt($response[0]->codigoProducto);
        $this->assertIsString($response[0]->descripcionProducto);
    }

    public function testSincronizarParametricaTipoHabitacion()
    {
        $response = self::$siat->sincronizarParametricaTipoHabitacion(self::$cuis);
        $this->assertGreaterThan(0, count($response));
        $this->assertIsInt($response[0]->codigoClasificador);
        $this->assertIsString($response[0]->descripcion);
    }
}
