<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use KingsonDe\Marshal\MarshalXml;
use PHPUnit\Framework\TestCase;

use Enors\Siat\PuntoVenta;
use Enors\Siat\Facturas\Builders\FacturaCompraVentaBuilder;
use Enors\Siat\Facturas\CompraVenta\FacturaCompraVentaDetalle;
use Enors\Siat\Facturas\CompraVenta\PaqueteFacturaCompraVenta;

class SiatVTest extends TestCase
{
    private static $siatCodigos0;
    private static $siatCodigos1;
    private static $siatSincronizacion0;
    private static $siatSincronizacion1;
    private static $cuis0;
    private static $cuis1;

    const DEFAULT_TIMEZONE = 'America/La_Paz';
    const CODIGO_PUNTO_VENTA_0 = 0;
    const CODIGO_PUNTO_VENTA_1 = 1;
    const CUFD_EXPECTED_TESTS = 100;
    const SINCRONIZACION_EXPECTED_TESTS = 50;
    const EMISION_FACTURA_INDIVIDUAL_EXPECTED_TESTS = 125;
    const EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS = 5;
    const EMISION_PAQUETE_FACTURA_EXPECTED_TESTS = 10;
    const ANULACION_FACTURA_EXPECTED_TESTS = 125;

    public static function setUpBeforeClass(): void
    {
        self::$siatCodigos0 = new SiatCodigos(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY']
        );
        self::$siatSincronizacion0 = new SiatSincronizacion(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY']
        );
        self::$cuis0 = self::$siatCodigos0->solicitarCUIS()->codigo;
        // var_dump(self::$cuis0);
        // $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);
        // var_dump($cufd0);

        $siatOperaciones = new SiatOperaciones(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY']
        );

        $response = $siatOperaciones->consultaPuntoVenta(self::$cuis0);
        if ($response->transaccion === false) {
            $puntoVenta = new PuntoVenta(
                5,
                'POS 1',
                'descripcion'
            );
            $responsePuntoVenta = $siatOperaciones->registroPuntoVenta(
                $cuis0->codigo,
                $puntoVenta
            );
        }

        self::$siatCodigos1 = new SiatCodigos(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1
        );
        self::$siatSincronizacion1 = new SiatSincronizacion(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1
        );
        self::$cuis1 = self::$siatCodigos1->solicitarCUIS()->codigo;
        // var_dump(self::$cuis1);
        // $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);
        // var_dump($cufd1);
    }

    public function testEventosSignificativos()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $siatOperaciones0 = new SiatOperaciones(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $siatOperaciones1 = new SiatOperaciones(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        // Los valores del CUFD de contingencia deben ser valores de hace 24 - 48 horas atrás.
        $cufdContingencia0 = 'BQT5DcDBpQUE=NzTEwNTdDMTA0Mzc=Q1U2d0pYRUJYVUFI1RjhGOEExQTI4O';
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';

        $starAt = strtotime('2023-01-03 23:30:00');
        $endAt = strtotime('2023-01-03 23:32:00');

        $eventoSignificativo0 = new EventoSignificativo(
            4,
            'VENTA EN LUGARES SIN INTERNET',
            $starAt,
            $endAt,
            $cufdContingencia0
        );
        $response0 = $siatOperaciones0->registroEventoSignificativo(
            self::$cuis0,
            $cufd0->codigo,
            $eventoSignificativo0
        );
        var_dump($response0);
        $this->assertIsBool($response0->transaccion);
        $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);

        
        $eventoSignificativo1 = new EventoSignificativo(
            4,
            'VENTA EN LUGARES SIN INTERNET',
            $starAt,
            $endAt,
            $cufdContingencia1
        );
        $response1 = $siatOperaciones1->registroEventoSignificativo(
            self::$cuis1,
            $cufd1->codigo,
            $eventoSignificativo1
        );
        var_dump($response1);
        $this->assertIsBool($response1->transaccion);
        $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
    }

    public function testSolicitarCUFD()
    {
        for ($i=0; $i < self::CUFD_EXPECTED_TESTS; $i++) {
            $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);
            $this->assertNotEmpty($cufd0->codigo);
            $this->assertNotEmpty($cufd0->codigoControl);
        }

        for ($i=0; $i < self::CUFD_EXPECTED_TESTS; $i++) {
            $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);
            $this->assertNotEmpty($cufd1->codigo);
            $this->assertNotEmpty($cufd1->codigoControl);
        }
    }

    public function testSincronizarActividades()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarActividades(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaActividades));
            $this->assertNotEmpty($response0->listaActividades[0]->codigoCaeb);
            $this->assertNotEmpty($response0->listaActividades[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarActividades(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaActividades));
            $this->assertNotEmpty($response1->listaActividades[0]->codigoCaeb);
            $this->assertNotEmpty($response1->listaActividades[0]->descripcion);
        }
    }

    public function testSincronizarFechaHora()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $fechaHora0 = self::$siatSincronizacion0->sincronizarFechaHora(self::$cuis0);
            $this->assertIsString($fechaHora0->fechaHora);
            $this->assertNotEmpty($fechaHora0->fechaHora);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $fechaHora1 = self::$siatSincronizacion1->sincronizarFechaHora(self::$cuis1);
            $this->assertIsString($fechaHora1->fechaHora);
            $this->assertNotEmpty($fechaHora1->fechaHora);
        }
    }

    public function testSincronizarActividadesDocumentoSector()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarActividadesDocumentoSector(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaActividadesDocumentoSector));
            $this->assertNotEmpty($response0->listaActividadesDocumentoSector[0]->codigoActividad);
            $this->assertIsInt($response0->listaActividadesDocumentoSector[0]->codigoDocumentoSector);
            $this->assertNotEmpty($response0->listaActividadesDocumentoSector[0]->tipoDocumentoSector);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarActividadesDocumentoSector(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaActividadesDocumentoSector));
            $this->assertNotEmpty($response1->listaActividadesDocumentoSector[0]->codigoActividad);
            $this->assertIsInt($response1->listaActividadesDocumentoSector[0]->codigoDocumentoSector);
            $this->assertNotEmpty($response1->listaActividadesDocumentoSector[0]->tipoDocumentoSector);
        }
    }

    public function testSincronizarListaLeyendasFactura()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarListaLeyendasFactura(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaLeyendas));
            $this->assertIsString($response0->listaLeyendas[0]->codigoActividad);
            $this->assertIsString($response0->listaLeyendas[0]->descripcionLeyenda);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarListaLeyendasFactura(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaLeyendas));
            $this->assertIsString($response1->listaLeyendas[0]->codigoActividad);
            $this->assertIsString($response1->listaLeyendas[0]->descripcionLeyenda);
        }
    }

    public function testSincronizarListaMensajesServicios()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarListaMensajesServicios(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarListaMensajesServicios(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaListaProductosServicios()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaListaProductosServicios(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsString($response0->listaCodigos[0]->codigoActividad);
            $this->assertIsInt($response0->listaCodigos[0]->codigoProducto);
            $this->assertIsString($response0->listaCodigos[0]->descripcionProducto);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaListaProductosServicios(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsString($response1->listaCodigos[0]->codigoActividad);
            $this->assertIsInt($response1->listaCodigos[0]->codigoProducto);
            $this->assertIsString($response1->listaCodigos[0]->descripcionProducto);
        }
    }

    public function testSincronizarParametricaEventosSignificativos()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaEventosSignificativos(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaEventosSignificativos(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaMotivoAnulacion()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaMotivoAnulacion(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaMotivoAnulacion(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaPaisOrigen()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaPaisOrigen(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaPaisOrigen(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoDocumentoIdentidad()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoDocumentoIdentidad(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoDocumentoSector()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoDocumentoSector(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoDocumentoSector(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoEmision()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoEmision(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoEmision(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoHabitacion()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoHabitacion(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoHabitacion(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoMetodoPago()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoMetodoPago(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoMetodoPago(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoMoneda()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoMoneda(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoMoneda(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTipoPuntoVenta()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoPuntoVenta(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoPuntoVenta(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaTiposFactura()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaTipoFactura(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaTipoFactura(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testSincronizarParametricaUnidadMedida()
    {
        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response0 = self::$siatSincronizacion0->sincronizarParametricaUnidadMedida(self::$cuis0);
            $this->assertGreaterThan(0, count($response0->listaCodigos));
            $this->assertIsInt($response0->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response0->listaCodigos[0]->descripcion);
        }

        for ($i=0; $i < self::SINCRONIZACION_EXPECTED_TESTS; $i++) {
            $response1 = self::$siatSincronizacion1->sincronizarParametricaUnidadMedida(self::$cuis1);
            $this->assertGreaterThan(0, count($response1->listaCodigos));
            $this->assertIsInt($response1->listaCodigos[0]->codigoClasificador);
            $this->assertIsString($response1->listaCodigos[0]->descripcion);
        }
    }

    public function testEmisionFacturaIndividual()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura =  1100;
        for ($i=0; $i < self::EMISION_FACTURA_INDIVIDUAL_EXPECTED_TESTS; $i++) {
            $builder = new FacturaCompraVentaBuilder();
            $builder->createFactura();
            $builder->emisionOnline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = intval($_ENV['SIAT_NIT']);
            $facturaCompraVenta->razonSocialEmisor = 'Pedrito & Asociados';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '+59178595684';
            $facturaCompraVenta->numeroFactura = $numeroFactura;
            $facturaCompraVenta->codigoSucursal = 0;
            $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
            $facturaCompraVenta->codigoPuntoVenta = 0;
            $facturaCompraVenta->fechaEmision = time();
            $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
            $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
            $facturaCompraVenta->numeroDocumento = '5115889';
            $facturaCompraVenta->complemento = null;
            $facturaCompraVenta->codigoCliente = '51158891';
            $facturaCompraVenta->codigoMetodoPago = 1;
            $facturaCompraVenta->numeroTarjeta = null;
            $facturaCompraVenta->montoTotal = 100;
            $facturaCompraVenta->montoTotalSujetoIva = 100;
            $facturaCompraVenta->codigoMoneda = 1;
            $facturaCompraVenta->tipoCambio = 1;
            $facturaCompraVenta->montoTotalMoneda = 100;
            $facturaCompraVenta->montoGiftCard = null;
            $facturaCompraVenta->descuentoAdicional = 0;
            $facturaCompraVenta->codigoExcepcion = null;
            $facturaCompraVenta->cafc = null;
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
                'términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '477311';
            $detalle->codigoProductoSin = 35270;
            $detalle->codigoProducto = '35270';
            $detalle->descripcion = 'OTROS PRODUCTOS FARMACÉUTICOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = $cufd0->codigo;
            $facturaCompraVenta->codigoControl = $cufd0->codigoControl;
            $facturaCompraVenta->cuis = self::$cuis0;

            $response0 = $siatFacturaCompraVenta0->recepcionFactura($facturaCompraVenta);

            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            $numeroFactura += 1;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        $numeroFactura =  2100;
        for ($i=0; $i < self::EMISION_FACTURA_INDIVIDUAL_EXPECTED_TESTS; $i++) {
            $builder = new FacturaCompraVentaBuilder();
            $builder->createFactura();
            $builder->emisionOnline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = intval($_ENV['SIAT_NIT']);
            $facturaCompraVenta->razonSocialEmisor = 'John Doe';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '+59178595684';
            $facturaCompraVenta->numeroFactura = $numeroFactura;
            $facturaCompraVenta->codigoSucursal = 0;
            $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
            $facturaCompraVenta->codigoPuntoVenta = self::CODIGO_PUNTO_VENTA_1;
            $facturaCompraVenta->fechaEmision = time();
            $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
            $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
            $facturaCompraVenta->numeroDocumento = '5115889';
            $facturaCompraVenta->complemento = null;
            $facturaCompraVenta->codigoCliente = '51158891';
            $facturaCompraVenta->codigoMetodoPago = 1;
            $facturaCompraVenta->numeroTarjeta = null;
            $facturaCompraVenta->montoTotal = 100;
            $facturaCompraVenta->montoTotalSujetoIva = 100;
            $facturaCompraVenta->codigoMoneda = 1;
            $facturaCompraVenta->tipoCambio = 1;
            $facturaCompraVenta->montoTotalMoneda = 100;
            $facturaCompraVenta->montoGiftCard = null;
            $facturaCompraVenta->descuentoAdicional = 0;
            $facturaCompraVenta->codigoExcepcion = null;
            $facturaCompraVenta->cafc = null;
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
                'términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '477311';
            $detalle->codigoProductoSin = 35270;
            $detalle->codigoProducto = '35270';
            $detalle->descripcion = 'OTROS PRODUCTOS FARMACÉUTICOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = $cufd1->codigo;
            $facturaCompraVenta->codigoControl = $cufd1->codigoControl;
            $facturaCompraVenta->cuis = self::$cuis1;

            $response1 = $siatFacturaCompraVenta1->recepcionFactura($facturaCompraVenta);

            $this->assertEquals(true, $response1->transaccion);
            $this->assertNotEmpty($response1->codigoRecepcion);

            $numeroFactura += 1;
        }
    }

    public function testEventosSignificativos()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $siatOperaciones0 = new SiatOperaciones(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $siatOperaciones1 = new SiatOperaciones(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        // Los valores del CUFD de contingencia deben ser valores de hace 24 - 48 horas atrás.
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';

        $starAt = strtotime('2024-05-01 22:10:00');
        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);    // Seconds
            $eventoSignificativo = new EventoSignificativo(
                1,
                'CORTE DEL SERVICIO DE INTERNET',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                1,
                'CORTE DEL SERVICIO DE INTERNET',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                2,
                'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );

            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                2,
                'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                3,
                'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }
        
        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                3,
                'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }
        
        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                4,
                'VENTA EN LUGARES SIN INTERNET',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                4,
                'VENTA EN LUGARES SIN INTERNET',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                5,
                'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                5,
                'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                6,
                'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                6,
                'VIRUS INFORMÁTICO O FALLA DE SOFTWARE',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                7,
                'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
                $starAt,
                $endAt,
                $cufdContingencia0
            );
            $response0 = $siatOperaciones0->registroEventoSignificativo(
                self::$cuis0,
                $cufd0->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response0->transaccion);
            $this->assertIsInt($response0->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }

        for ($i=0; $i < self::EVENTOS_SIGNIFICATIVOS_EXPECTED_TESTS; $i++) {
            $endAt = $starAt + (30 * 1);
            $eventoSignificativo = new EventoSignificativo(
                7,
                'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
                $starAt,
                $endAt,
                $cufdContingencia1
            );
            $response1 = $siatOperaciones1->registroEventoSignificativo(
                self::$cuis1,
                $cufd1->codigo,
                $eventoSignificativo
            );
            $this->assertIsBool($response1->transaccion);
            $this->assertIsInt($response1->codigoRecepcionEventoSignificativo);
            $starAt = $endAt + (30 * 1);
        }
    }
    
    public static function generateInvoices($quantity, $cuis, $cufdCode, $cufdControlCode, $fechaEmision, $codigoPuntoVenta, $numeroFactura, $cafc = null) {
        $builder = new FacturaCompraVentaBuilder();
        $facturas = [];
        // $numeroFactura = 1010501;

        for ($i=0; $i < $quantity; $i++) {
            $builder->createFactura();
            $builder->emisionOffline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = intval($_ENV['SIAT_NIT']);
            $facturaCompraVenta->razonSocialEmisor = 'John Doe';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '78595684';
            $facturaCompraVenta->numeroFactura = $numeroFactura;
            $facturaCompraVenta->codigoSucursal = 0;
            $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
            $facturaCompraVenta->codigoPuntoVenta = $codigoPuntoVenta;
            $facturaCompraVenta->fechaEmision = $fechaEmision;
            $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
            $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
            $facturaCompraVenta->numeroDocumento = '5115889';
            $facturaCompraVenta->complemento = null;
            $facturaCompraVenta->codigoCliente = '51158891';
            $facturaCompraVenta->codigoMetodoPago = 1;
            $facturaCompraVenta->numeroTarjeta = null;
            $facturaCompraVenta->montoTotal = 100;
            $facturaCompraVenta->montoTotalSujetoIva = 100;
            $facturaCompraVenta->codigoMoneda = 1;
            $facturaCompraVenta->tipoCambio = 1;
            $facturaCompraVenta->montoTotalMoneda = 100;
            $facturaCompraVenta->montoGiftCard = null;
            $facturaCompraVenta->descuentoAdicional = 0;
            $facturaCompraVenta->codigoExcepcion = null;
            $facturaCompraVenta->cafc = $cafc;
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las ' .
                'modalidades y términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '477311';
            $detalle->codigoProductoSin = 35270;
            $detalle->codigoProducto = '35270';
            $detalle->descripcion = 'OTROS PRODUCTOS FARMACÉUTICOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = $cufdCode;
            $facturaCompraVenta->codigoControl = $cufdControlCode;
            $facturaCompraVenta->cuis = $cuis;
            array_push($facturas, $facturaCompraVenta);

            $numeroFactura++;
        }

        return $facturas;
    }

    public function testValidacionRecepcionPaqueteFactura()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $codigoRecepcionEvento0 = 1711366;
        $cufdContingencia0 = 'BQT5DcDBpQUE=NzTEwNTdDMTA0Mzc=Q1U2d0pYRUJYVUFI1RjhGOEExQTI4O';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura = 2100000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS * 7; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2023-01-03 23:19:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);

            sleep(10);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta0->validacionRecepcionPaqueteFactura($paqueteFactura);
            var_dump('ValidacionReceptcionPaqueteFactura0');
            var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $codigoRecepcionEvento1 = 1711367;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS * 7; $i++) {
            $facturas = self::generateInvoices(
                10,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2023-01-03 23:19:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            
            sleep(10);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta1->validacionRecepcionPaqueteFactura($paqueteFactura);
            var_dump('ValidacionReceptcionPaqueteFactura1');
            var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);
            
            $numeroFactura += 10;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }
    
    // public function testTempCreateEventosSignificativos()
    // {
    //     date_default_timezone_set(self::DEFAULT_TIMEZONE);
    //     $siatOperaciones0 = new SiatOperaciones(
    //         $_ENV['SIAT_CODIGO_SISTEMA'],
    //         $_ENV['SIAT_NIT'],
    //         $_ENV['SIAT_API_KEY'],
    //         0,
    //         0,
    //         SiatConstants::MODALIDAD_COMPUTARIZADA
    //     );

    //     $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

    //     $siatOperaciones1 = new SiatOperaciones(
    //         $_ENV['SIAT_CODIGO_SISTEMA'],
    //         $_ENV['SIAT_NIT'],
    //         $_ENV['SIAT_API_KEY'],
    //         self::CODIGO_PUNTO_VENTA_1,
    //         0,
    //         SiatConstants::MODALIDAD_COMPUTARIZADA
    //     );

    //     $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

    //     // Los valores del CUFD de contingencia deben ser valores de hace 24 - 48 horas atrás.
    //     $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
    //     $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';

    //     $eventoSignificativo = new EventoSignificativo(
    //         7,
    //         'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
    //         strtotime('2024-05-04 13:00:00'),
    //         strtotime('2024-05-04 13:05:00'),
    //         $cufdContingencia0
    //     );
    //     $response0 = $siatOperaciones0->registroEventoSignificativo(
    //         self::$cuis0,
    //         $cufd0->codigo,
    //         $eventoSignificativo
    //     );
    //     var_dump($response0);
        
    //     $eventoSignificativo = new EventoSignificativo(
    //         7,
    //         'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE',
    //         strtotime('2024-05-04 13:00:00'),
    //         strtotime('2024-05-04 13:05:00'),
    //         $cufdContingencia1
    //     );
    //     $response1 = $siatOperaciones1->registroEventoSignificativo(
    //         self::$cuis1,
    //         $cufd1->codigo,
    //         $eventoSignificativo
    //     );
    //     var_dump($response1);
    // }

    public function testEmisionPaqueteFacturaCorteServicioInternet()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $codigoRecepcionEvento0 = 8792728;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura = 1000000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2024-05-04 12:32:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            var_dump('ReceptcionPaqueteFactura0');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta0->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura0');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );

        $codigoRecepcionEvento1 = 8792729;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2024-05-04 12:32:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta1->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura1');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testInaccesibilidadAlServicioWeb()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 8792733;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura = 1020000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2024-05-04 12:42:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            var_dump('ReceptcionPaqueteFactura0');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta0->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura0');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 8792734;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2024-05-04 12:42:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta1->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura1');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testIngresosAZonasSinInternet()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 8792735;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura = 1030000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2024-05-04 12:52:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            // var_dump('ReceptcionPaqueteFactura0');
            // var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta0->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura0');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 8792736;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2024-05-04 12:52:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta1->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura1');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }
    
    public function testVentaEnLugaresSinInternet()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 8792738;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura = 1040000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2024-05-04 13:02:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            // var_dump('ReceptcionPaqueteFactura0');
            // var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta0->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura0');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 8792739;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2024-05-04 13:02:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = '';

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            // var_dump('ReceptcionPaqueteFactura1');
            // var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            sleep(30);

            $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
            $response1 = $siatFacturaCompraVenta1->validacionRecepcionPaqueteFactura($paqueteFactura);
            // var_dump('ValidacionReceptcionPaqueteFactura1');
            // var_dump($response1);
            $this->assertEquals(true, $response1->transaccion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testCorteSuministroEnergiaElectrica()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 1711347;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);
        $cafc = '1014A4407AA0C';

        $numeroFactura = 1100;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2023-01-03 23:11:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura,
                $cafc
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            var_dump('ReceptcionPaqueteFactura0');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 1711348;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2023-01-03 23:11:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura,
                $cafc
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testVirusInformatico()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 1711349;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);
        $cafc = '1014A4407AA0C';

        $numeroFactura = 1100;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2023-01-03 23:15:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura,
                $cafc
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            var_dump('ReceptcionPaqueteFactura0');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 1711350;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2023-01-03 23:15:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura,
                $cafc
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testCambioInfraestructura()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento0 = 1711352;
        $cufdContingencia0 = 'BQUFvQn0jR0lBN0zQzMzE2MDlEM0U=Q8KhPU1jTUZGWVVM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode0 = '3509A05A91A8E74';
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);
        $cafc = '1014A4407AA0C';

        $numeroFactura = 1000;
        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) { 
            $facturas = self::generateInvoices(
                10,
                self::$cuis0,
                $cufdContingencia0,
                $cufdContingenciaControlCode0,
                strtotime('2023-01-03 23:19:00'),
                self::CODIGO_PUNTO_VENTA_0,
                $numeroFactura,
                $cafc
            );
            
            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd0->codigo;
            $paqueteFactura->cuis = self::$cuis0;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta0->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento0);
            var_dump('ReceptcionPaqueteFactura0');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);

            $numeroFactura += 10;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $codigoRecepcionEvento1 = 1711353;
        $cufdContingencia1 = 'BQW9CfSNHSUE=N0zQzMzE2MDlEM0U=Qz4mTmNNRkZZVUJM0Q0E5N0JDQTdBQ';
        $cufdContingenciaControlCode1 = '64ECA05A91A8E74';
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        for ($i=0; $i < self::EMISION_PAQUETE_FACTURA_EXPECTED_TESTS; $i++) {
            $facturas = self::generateInvoices(
                500,
                self::$cuis1,
                $cufdContingencia1,
                $cufdContingenciaControlCode1,
                strtotime('2023-01-03 23:19:00'),
                self::CODIGO_PUNTO_VENTA_1,
                $numeroFactura,
                $cafc
            );

            $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
            $paqueteFactura->cufd = $cufd1->codigo;
            $paqueteFactura->cuis = self::$cuis1;
            $paqueteFactura->nitEmisor = intval($_ENV['SIAT_NIT']);
            $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
            $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
            $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
            $paqueteFactura->cafc = $cafc;

            $response0 = $siatFacturaCompraVenta1->recepcionPaqueteFactura($paqueteFactura, $codigoRecepcionEvento1);
            var_dump('ReceptcionPaqueteFactura1');
            var_dump($response0);

            $this->assertEquals(901, $response0->codigoEstado);
            $this->assertEquals(true, $response0->transaccion);
            $this->assertNotEmpty($response0->codigoRecepcion);
            
            $numeroFactura += 500;
        }

        var_dump('LAST NRO FACTURA');
        var_dump($numeroFactura);
    }

    public function testAnulacionFactura()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $siatFacturaCompraVenta0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $cufd0 = self::$siatCodigos0->solicitarCUFD(self::$cuis0);

        $numeroFactura =  3006;
        for ($i=0; $i < self::ANULACION_FACTURA_EXPECTED_TESTS; $i++) {
            $builder = new FacturaCompraVentaBuilder();
            $builder->createFactura();
            $builder->emisionOnline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = intval($_ENV['SIAT_NIT']);
            $facturaCompraVenta->razonSocialEmisor = 'John Doe';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '+59178595684';
            $facturaCompraVenta->numeroFactura = $numeroFactura;
            $facturaCompraVenta->codigoSucursal = 0;
            $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
            $facturaCompraVenta->codigoPuntoVenta = 0;
            $facturaCompraVenta->fechaEmision = time();
            $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
            $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
            $facturaCompraVenta->numeroDocumento = '5115889';
            $facturaCompraVenta->complemento = null;
            $facturaCompraVenta->codigoCliente = '51158891';
            $facturaCompraVenta->codigoMetodoPago = 1;
            $facturaCompraVenta->numeroTarjeta = null;
            $facturaCompraVenta->montoTotal = 100;
            $facturaCompraVenta->montoTotalSujetoIva = 100;
            $facturaCompraVenta->codigoMoneda = 1;
            $facturaCompraVenta->tipoCambio = 1;
            $facturaCompraVenta->montoTotalMoneda = 100;
            $facturaCompraVenta->montoGiftCard = null;
            $facturaCompraVenta->descuentoAdicional = 0;
            $facturaCompraVenta->codigoExcepcion = null;
            $facturaCompraVenta->cafc = null;
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
                'términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '477311';
            $detalle->codigoProductoSin = 35270;
            $detalle->codigoProducto = '35270';
            $detalle->descripcion = 'OTROS PRODUCTOS FARMACÉUTICOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = $cufd0->codigo;
            $facturaCompraVenta->codigoControl = $cufd0->codigoControl;
            $facturaCompraVenta->cuis = self::$cuis0;

            $response0 = $siatFacturaCompraVenta0->recepcionFactura($facturaCompraVenta);
            sleep(2);
            $response1 = $siatFacturaCompraVenta0->anulacionFactura($facturaCompraVenta, 1);

            $this->assertEquals(905, $response1->codigoEstado);
            $this->assertEquals(true, $response1->transaccion);

            sleep(2);
            $response1 = $siatFacturaCompraVenta0->reversionAnulacionFactura($facturaCompraVenta);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 1;
        }

        $siatFacturaCompraVenta1 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            self::CODIGO_PUNTO_VENTA_1,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
        $cufd1 = self::$siatCodigos1->solicitarCUFD(self::$cuis1);

        $numeroFactura =  4000;
        for ($i=0; $i < self::ANULACION_FACTURA_EXPECTED_TESTS; $i++) {
            $builder = new FacturaCompraVentaBuilder();
            $builder->createFactura();
            $builder->emisionOnline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = intval($_ENV['SIAT_NIT']);
            $facturaCompraVenta->razonSocialEmisor = 'John Doe';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '+59178595684';
            $facturaCompraVenta->numeroFactura = $numeroFactura;
            $facturaCompraVenta->codigoSucursal = 0;
            $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
            $facturaCompraVenta->codigoPuntoVenta = self::CODIGO_PUNTO_VENTA_1;
            $facturaCompraVenta->fechaEmision = time();
            $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
            $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
            $facturaCompraVenta->numeroDocumento = '5115889';
            $facturaCompraVenta->complemento = null;
            $facturaCompraVenta->codigoCliente = '51158891';
            $facturaCompraVenta->codigoMetodoPago = 1;
            $facturaCompraVenta->numeroTarjeta = null;
            $facturaCompraVenta->montoTotal = 100;
            $facturaCompraVenta->montoTotalSujetoIva = 100;
            $facturaCompraVenta->codigoMoneda = 1;
            $facturaCompraVenta->tipoCambio = 1;
            $facturaCompraVenta->montoTotalMoneda = 100;
            $facturaCompraVenta->montoGiftCard = null;
            $facturaCompraVenta->descuentoAdicional = 0;
            $facturaCompraVenta->codigoExcepcion = null;
            $facturaCompraVenta->cafc = null;
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
                'términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '477311';
            $detalle->codigoProductoSin = 35270;
            $detalle->codigoProducto = '35270';
            $detalle->descripcion = 'OTROS PRODUCTOS FARMACÉUTICOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = $cufd1->codigo;
            $facturaCompraVenta->codigoControl = $cufd1->codigoControl;
            $facturaCompraVenta->cuis = self::$cuis1;

            $response0 = $siatFacturaCompraVenta1->recepcionFactura($facturaCompraVenta);
            sleep(2);
            $response1 = $siatFacturaCompraVenta1->anulacionFactura($facturaCompraVenta, 1);

            $this->assertEquals(905, $response1->codigoEstado);
            $this->assertEquals(true, $response1->transaccion);

            sleep(2);
            $response1 = $siatFacturaCompraVenta1->reversionAnulacionFactura($facturaCompraVenta);
            $this->assertEquals(true, $response1->transaccion);

            $numeroFactura += 1;
        }
    }
}
