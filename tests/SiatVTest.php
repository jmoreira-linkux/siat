<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use KingsonDe\Marshal\MarshalXml;
use PHPUnit\Framework\TestCase;

use Enors\Siat\PuntoVenta;

class SiatVTest extends TestCase
{
    private static $siat0;
    private static $siat1;
    private static $cuis0;
    private static $cuis1;

    const CODIGO_PUNTO_VENTA = 1;
    const CUFD_EXPECTED_TESTS = 100;
    const SINCRONIZACION_EXPECTED_TESTS = 50;

    public static function setUpBeforeClass(): void
    {
        self::$siat0 = new Siat($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        self::$cuis0 = self::$siat0->solicitarCUIS()->codigo;

        $response = self::$siat0->consultaPuntoVenta(self::$cuis0);
        if ($response->transaccion === false) {
            $puntoVenta = new PuntoVenta(
                2,
                'nombrePuntoVenta1',
                'descripcion'
            );
            $responsePuntoVenta = $siat->registroPuntoVenta(
                $cuis0->codigo,
                $puntoVenta
            );
        }

        self::$siat1 = new Siat(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA
        );
        self::$cuis1 = self::$siat1->solicitarCUIS()->codigo;
    }

    public function testSolicitarCUFD()
    {
        for ($i=0; $i < self::CUFD_EXPECTED_TESTS; $i++) {
            $cufd0 = self::$siat0->solicitarCUFD(self::$cuis0);
            $this->assertNotEmpty($cufd0->codigo);
            $this->assertNotEmpty($cufd0->codigoControl);
        }

        for ($i=0; $i < self::CUFD_EXPECTED_TESTS; $i++) {
            $cufd1 = self::$siat1->solicitarCUFD(self::$cuis1);
            $this->assertNotEmpty($cufd1->codigo);
            $this->assertNotEmpty($cufd1->codigoControl);
        }
    }

    public function testSincronizarActividades()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarActividades(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertNotEmpty($response0[0]->codigoCaeb);
            $this->assertNotEmpty($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarActividades(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertNotEmpty($response1[0]->codigoCaeb);
            $this->assertNotEmpty($response1[0]->descripcion);
        }
    }

    public function testSincronizarFechaHora()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $fechaHora0 = self::$siat0->sincronizarFechaHora(self::$cuis0);
            $this->assertIsString($fechaHora0);
            $this->assertNotEmpty($fechaHora0);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $fechaHora1 = self::$siat1->sincronizarFechaHora(self::$cuis1);
            $this->assertIsString($fechaHora1);
            $this->assertNotEmpty($fechaHora1);
        }
    }

    public function testSincronizarActividadesDocumentoSector()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarActividadesDocumentoSector(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertNotEmpty($response0[0]->codigoActividad);
            $this->assertIsInt($response0[0]->codigoDocumentoSector);
            $this->assertNotEmpty($response0[0]->tipoDocumentoSector);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarActividadesDocumentoSector(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertNotEmpty($response1[0]->codigoActividad);
            $this->assertIsInt($response1[0]->codigoDocumentoSector);
            $this->assertNotEmpty($response1[0]->tipoDocumentoSector);
        }
    }

    public function testSincronizarListaLeyendasFactura()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarListaLeyendasFactura(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsString($response0[0]->codigoActividad);
            $this->assertIsString($response0[0]->descripcionLeyenda);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarListaLeyendasFactura(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsString($response1[0]->codigoActividad);
            $this->assertIsString($response1[0]->descripcionLeyenda);
        }
    }

    public function testSincronizarListaMensajesServicios()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarListaMensajesServicios(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarListaMensajesServicios(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaListaProductosServicios()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaListaProductosServicios(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsString($response0[0]->codigoActividad);
            $this->assertIsInt($response0[0]->codigoProducto);
            $this->assertIsString($response0[0]->descripcionProducto);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaListaProductosServicios(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsString($response1[0]->codigoActividad);
            $this->assertIsInt($response1[0]->codigoProducto);
            $this->assertIsString($response1[0]->descripcionProducto);
        }
    }

    public function testSincronizarParametricaEventosSignificativos()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaEventosSignificativos(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaEventosSignificativos(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaMotivoAnulacion()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaMotivoAnulacion(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaMotivoAnulacion(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaPaisOrigen()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaPaisOrigen(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaPaisOrigen(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoDocumentoIdentidad()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoDocumentoSector()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoDocumentoSector(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoDocumentoSector(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoEmision()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoEmision(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoEmision(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoHabitacion()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoHabitacion(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoHabitacion(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoMetodoPago()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoMetodoPago(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoMetodoPago(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoMoneda()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoMoneda(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoMoneda(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoPuntoVenta()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTipoPuntoVenta(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTipoPuntoVenta(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTiposFactura()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaTiposFactura(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaTiposFactura(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }

    public function testSincronizarParametricaUnidadMedida()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siat0->sincronizarParametricaUnidadMedida(self::$cuis0);
            $this->assertGreaterThan(0, count($response0));
            $this->assertIsInt($response0[0]->codigoClasificador);
            $this->assertIsString($response0[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siat1->sincronizarParametricaUnidadMedida(self::$cuis1);
            $this->assertGreaterThan(0, count($response1));
            $this->assertIsInt($response1[0]->codigoClasificador);
            $this->assertIsString($response1[0]->descripcion);
        }
    }
}
