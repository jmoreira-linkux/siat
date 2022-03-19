<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use KingsonDe\Marshal\MarshalXml;
use PHPUnit\Framework\TestCase;

use Enors\Siat\Facturas\Builders\FacturaCompraVentaBuilder;
use Enors\Siat\Facturas\CompraVenta\FacturaCompraVenta;
use Enors\Siat\Facturas\CompraVenta\FacturaCompraVentaDetalle;
use Enors\Siat\Facturas\CompraVenta\PaqueteFacturaCompraVenta;
use Enors\Siat\Mappers\FacturaCompraVenta\FacturaCompraVentaMapper;

class SiatFacturacionComputarizadaTest extends TestCase
{
    private static $siat0;
    private static $cuis0;
    private static $cufd0;

    const DEFAULT_TIMEZONE = 'America/La_Paz';

    public static function setUpBeforeClass(): void
    {
        $siat = new SiatCodigos($_ENV['SIAT_CODIGO_SISTEMA'], $_ENV['SIAT_NIT'], $_ENV['SIAT_API_KEY']);
        self::$cuis0 = $siat->solicitarCUIS()->codigo;
        self::$cufd0 = $siat->solicitarCUFD(self::$cuis0);
        self::$siat0 = new SiatFacturacionCompraVenta(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
    }

    public function testGenerarCUF()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $builder = new FacturaCompraVentaBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadElectronicaEnLinea();
        $builder->conDerechoCreditoFiscal();

        $factura = $builder->getFactura();
        $factura->fechaEmision = strtotime('2019-01-13 16:37:21');
        $factura->codigoControl = 'A19E23EF34124CD';
        $factura->nitEmisor = 123456789;
        $factura->codigoSucursal = 0;
        $factura->codigoPuntoVenta = 0;
        $factura->numeroFactura = 1;

        $cuf = $factura->getCuf();
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }

    public function testFacturaComputarizadaMapper()
    {
        $builder = new FacturaCompraVentaBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();
        
        $facturaCompraVenta = $builder->getFactura();
        $facturaCompraVenta->nitEmisor = 1003579028;
        $facturaCompraVenta->razonSocialEmisor = 'Carlos Loza';
        $facturaCompraVenta->municipio = 'La Paz';
        $facturaCompraVenta->telefono = '78595684';
        $facturaCompraVenta->numeroFactura = 1;
        $facturaCompraVenta->cuf = '44AAEC00DBD34C53C3E2CCE1A3FA7AF1E2A08606A667A75AC82F24C74';
        $facturaCompraVenta->cufd = 'BQUE+QytqQUDBKVUFOSVRPQkxVRFZNVFVJBMDAwMDAwM';
        $facturaCompraVenta->codigoSucursal = 0;
        $facturaCompraVenta->direccion = 'AV. JORGE LOPEZ #123';
        $facturaCompraVenta->codigoPuntoVenta = null;
        $facturaCompraVenta->fechaEmision = strtotime('2021-10-06 16:03:48.000');
        $facturaCompraVenta->nombreRazonSocial = 'Mi razon social';
        $facturaCompraVenta->codigoTipoDocumentoIdentidad = 1;
        $facturaCompraVenta->numeroDocumento = '5115889';
        $facturaCompraVenta->complemento = null;
        $facturaCompraVenta->codigoCliente = '51158891';
        $facturaCompraVenta->codigoMetodoPago = 1;
        $facturaCompraVenta->numeroTarjeta = null;
        $facturaCompraVenta->montoTotal = 99;
        $facturaCompraVenta->montoTotalSujetoIva = 99;
        $facturaCompraVenta->codigoMoneda = 1;
        $facturaCompraVenta->tipoCambio = 1;
        $facturaCompraVenta->montoTotalMoneda = 99;
        $facturaCompraVenta->montoGiftCard = null;
        $facturaCompraVenta->descuentoAdicional = 1;
        $facturaCompraVenta->codigoExcepcion = null;
        $facturaCompraVenta->cafc = null;
        $facturaCompraVenta->leyenda = 'Ley N° 453: Tienes derecho a recibir información sobre las características y ' .
            'contenidos de los servicios que utilices.';
        $facturaCompraVenta->usuario = 'pperez';
        $facturaCompraVenta->codigoControl = 'AAAAAAAA';

        $detalle = new FacturaCompraVentaDetalle();
        $detalle->actividadEconomica = '451010';
        $detalle->codigoProductoSin = 49111;
        $detalle->codigoProducto = 'JN-131231';
        $detalle->descripcion = 'JUGO DE NARANJA EN VASO';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;
        $detalle->numeroSerie = '124548';
        $detalle->numeroImei = '545454';

        $facturaCompraVenta->agregarLinea($detalle);

        $xml = MarshalXml::serializeItem(new FacturaCompraVentaMapper(), $facturaCompraVenta);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/facturaComputarizadaCompraVenta.xml', $xml);
    }

    public function testRecepcionFacturaCompraVenta()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $builder = new FacturaCompraVentaBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();

        $facturaCompraVenta = $builder->getFactura();
        $facturaCompraVenta->nitEmisor = 346141028;
        $facturaCompraVenta->razonSocialEmisor = 'John Doe';
        $facturaCompraVenta->municipio = 'La Paz';
        $facturaCompraVenta->telefono = '+59178595684';
        $facturaCompraVenta->numeroFactura = 7;
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
        $detalle->actividadEconomica = '620000';
        $detalle->codigoProductoSin = 47813;
        $detalle->codigoProducto = '47813';
        $detalle->descripcion = 'PAQUETE DE SOFTWARE DE ADMINISTRACIÓN DE BASE DE DATOS';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;
        $detalle->numeroSerie = null;
        $detalle->numeroImei = null;

        $facturaCompraVenta->agregarLinea($detalle);

        $facturaCompraVenta->cufd = self::$cufd0->codigo;
        $facturaCompraVenta->codigoControl = self::$cufd0->codigoControl;
        $facturaCompraVenta->cuis = self::$cuis0;

        $response0 = self::$siat0->recepcionFactura($facturaCompraVenta);

        $this->assertEquals(true, $response0->transaccion);
        $this->assertNotEmpty($response0->codigoRecepcion);
    }
    
    public function testRecepcionPaqueteFacturaCompraVenta()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $builder = new FacturaCompraVentaBuilder();
        $facturas = [];

        for ($i=0; $i < 500; $i++) {
            $builder->createFactura();
            $builder->emisionOffline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaCompraVenta = $builder->getFactura();
            $facturaCompraVenta->nitEmisor = 346141028;
            $facturaCompraVenta->razonSocialEmisor = 'John Doe';
            $facturaCompraVenta->municipio = 'La Paz';
            $facturaCompraVenta->telefono = '78595684';
            $facturaCompraVenta->numeroFactura = 4;
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
            $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las ' .
                'modalidades y términos ofertados o convenidos.';
            $facturaCompraVenta->usuario = 'pperez';

            $detalle = new FacturaCompraVentaDetalle();
            $detalle->actividadEconomica = '620000';
            $detalle->codigoProductoSin = 47813;
            $detalle->codigoProducto = '47813';
            $detalle->descripcion = 'PAQUETE DE SOFTWARE DE ADMINISTRACIÓN DE BASE DE DATOS';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;
            $detalle->numeroSerie = null;
            $detalle->numeroImei = null;

            $facturaCompraVenta->agregarLinea($detalle);

            $facturaCompraVenta->cufd = self::$cufd0->codigo;
            $facturaCompraVenta->codigoControl = self::$cufd0->codigoControl;
            $facturaCompraVenta->cuis = self::$cuis0;
            array_push($facturas, $facturaCompraVenta);
        }

        $paqueteFactura = new PaqueteFacturaCompraVenta($facturas);
        $paqueteFactura->cufd = self::$cufd0->codigo;
        $paqueteFactura->cuis = self::$cuis0;
        $paqueteFactura->nitEmisor = 346141028;
        $paqueteFactura->codigoEmision = SiatConstants::EMISION_OFFLINE;
        $paqueteFactura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
        $paqueteFactura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
        $response0 = self::$siat0->recepcionPaqueteFactura($paqueteFactura, 105481);

        $this->assertEquals(901, $response0->codigoEstado);
        $this->assertEquals(true, $response0->transaccion);
        $this->assertNotEmpty($response0->codigoRecepcion);

        $paqueteFactura->codigoRecepcion = $response0->codigoRecepcion;
        $response1 = self::$siat0->validacionRecepcionPaqueteFactura($paqueteFactura);
        $this->assertEquals(true, $response0->transaccion);
    }

    public function testAnulacionFacturaCompraVenta()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $faker = \Faker\Factory::create();

        $builder = new FacturaCompraVentaBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();

        $facturaCompraVenta = $builder->getFactura();
        $facturaCompraVenta->nitEmisor = 346141028;
        $facturaCompraVenta->razonSocialEmisor = 'Carlos Loza';
        $facturaCompraVenta->municipio = 'La Paz';
        $facturaCompraVenta->telefono = '78595684';
        $facturaCompraVenta->numeroFactura = $faker->unique()->randomNumber(8);
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
        $detalle->actividadEconomica = '620000';
        $detalle->codigoProductoSin = 47813;
        $detalle->codigoProducto = '47813';
        $detalle->descripcion = 'PAQUETE DE SOFTWARE DE ADMINISTRACIÓN DE BASE DE DATOS';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;
        $detalle->numeroSerie = null;
        $detalle->numeroImei = null;

        $facturaCompraVenta->agregarLinea($detalle);

        $facturaCompraVenta->cufd = self::$cufd0->codigo;
        $facturaCompraVenta->codigoControl = self::$cufd0->codigoControl;
        $facturaCompraVenta->cuis = self::$cuis0;

        $response0 = self::$siat0->recepcionFactura($facturaCompraVenta);

        $response1 = self::$siat0->anulacionFactura($facturaCompraVenta, 1);

        $this->assertEquals(905, $response1->codigoEstado);
        $this->assertEquals(true, $response1->transaccion);
    }
}
