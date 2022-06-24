<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use KingsonDe\Marshal\MarshalXml;
use PHPUnit\Framework\TestCase;

use Enors\Siat\Facturas\Builders\FacturaSectorEducativoBuilder;
use Enors\Siat\Facturas\Educacion\FacturaSectorEducativo;
use Enors\Siat\Facturas\Educacion\FacturaSectorEducativoDetalle;
use Enors\Siat\Facturas\Educacion\PaqueteFacturaSectorEducativo;
use Enors\Siat\Mappers\FacturaSectorEducativo\FacturaSectorEducativoMapper;

class SiatFacturacionComputarizadaSectorEducativo extends TestCase
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
        self::$siat0 = new SiatFacturacionSectorEducativo(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            SiatConstants::MODALIDAD_COMPUTARIZADA
        );
    }

    /*public function testGenerarCUF()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $builder = new FacturaSectorEducativoBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadElectronicaEnLinea();
        $builder->conDerechoCreditoFiscal();

        $factura = $builder->getFactura();
        $factura->fechaEmision = strtotime('2022-06-23 16:37:21');
        $factura->codigoControl = 'A19E23EF34124CD';
        $factura->nitEmisor = 123456789;
        $factura->codigoSucursal = 0;
        $factura->codigoPuntoVenta = 0;
        $factura->numeroFactura = 1;

        $cuf = $factura->getCuf();
        var_dump("cuf generate: ".$cuf);
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }

    
    public function testFacturaComputarizadaMapper()
    {
        $builder = new FacturaSectorEducativoBuilder();
        
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();
        
        $facturaSectorEducativo = $builder->getFactura();
        $facturaSectorEducativo->nitEmisor = 1003579028;
        $facturaSectorEducativo->razonSocialEmisor = 'PRUEBA';
        $facturaSectorEducativo->municipio = 'La Paz';
        $facturaSectorEducativo->telefono = '2846005';
        $facturaSectorEducativo->numeroFactura = 1;
        $facturaSectorEducativo->cuf = '44AAEC00DBD34C53C3E44CB1766171E788E04706A167A75AC82F24C74';
        $facturaSectorEducativo->cufd = 'BQUE+QytqQUDBKVUFOSVRPQkxVRFZNVFVJBMDAwMDAwM';
        $facturaSectorEducativo->codigoSucursal = 0;
        $facturaSectorEducativo->direccion = 'AV. JORGE LOPEZ #123';
        $facturaSectorEducativo->codigoPuntoVenta = null;
        $facturaSectorEducativo->fechaEmision = strtotime('2021-10-06T16:03:49.139');
        $facturaSectorEducativo->nombreRazonSocial = 'Nombre o Razon Social';
        $facturaSectorEducativo->codigoTipoDocumentoIdentidad = 1;
        $facturaSectorEducativo->numeroDocumento = '5115889';
        $facturaSectorEducativo->complemento = true;
        $facturaSectorEducativo->codigoCliente = '51158891';
        $facturaSectorEducativo->nombreEstudiante = 'Marcelo Campos Vargas';
        $facturaSectorEducativo->periodoFacturado = 'I-2022';
        $facturaSectorEducativo->codigoMetodoPago = 1;
        $facturaSectorEducativo->numeroTarjeta = null;
        $facturaSectorEducativo->montoTotal = 100;
        $facturaSectorEducativo->montoTotalSujetoIva = 99;
        $facturaSectorEducativo->codigoMoneda = 1;
        $facturaSectorEducativo->tipoCambio = 1;
        $facturaSectorEducativo->montoTotalMoneda = 99;
        $facturaSectorEducativo->montoGiftCard = null;
        $facturaSectorEducativo->descuentoAdicional = 1;
        $facturaSectorEducativo->codigoExcepcion = null;
        $facturaSectorEducativo->cafc = null;
        $facturaSectorEducativo->leyenda = 'Ley N° 453: Tienes derecho a recibir información sobre las características y ' .
            'contenidos de los servicios que utilices.';
        $facturaSectorEducativo->usuario = 'vjcm';
        $facturaSectorEducativo->codigoControl = 'AAAAAAAA';

        $detalle = new FacturaSectorEducativoDetalle();
        $detalle->actividadEconomica = '451010';
        $detalle->codigoProductoSin = 49111;
        $detalle->codigoProducto = '123456';
        $detalle->descripcion = 'Pension mes de Junio';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;

        $facturaSectorEducativo->agregarLinea($detalle);

        $xml = MarshalXml::serializeItem(new FacturaSectorEducativoMapper(), $facturaSectorEducativo);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/facturaComputarizadaSectorEducativo.xml', $xml);
    }*/

    public function testRecepcionFacturaSectorEducativo()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $builder = new FacturaSectorEducativoBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();

        $facturaSectorEducativo = $builder->getFactura();
        $facturaSectorEducativo->nitEmisor = 124849020;
        $facturaSectorEducativo->razonSocialEmisor = 'CORPORACION EDUCATIVA CUMBRE S.A.';
        $facturaSectorEducativo->municipio = 'Santa Cruz de la Sierra';
        $facturaSectorEducativo->telefono = '+59178595684';
        $facturaSectorEducativo->numeroFactura = 7;
        $facturaSectorEducativo->codigoSucursal = 0;
        $facturaSectorEducativo->direccion = 'AVENIDA CAÑOTO NRO.580 ZONA PANAMERICANA';
        $facturaSectorEducativo->codigoPuntoVenta = 0;
        $facturaSectorEducativo->fechaEmision = time();
        $facturaSectorEducativo->nombreRazonSocial = 'Mi razon social';
        $facturaSectorEducativo->codigoTipoDocumentoIdentidad = 1;
        $facturaSectorEducativo->numeroDocumento = '5115889';
        $facturaSectorEducativo->complemento = null;
        $facturaSectorEducativo->codigoCliente = '51158891';
        $facturaSectorEducativo->nombreEstudiante = 'Marcelo Campos Vargas';
        $facturaSectorEducativo->periodoFacturado = 'I-2022';
        $facturaSectorEducativo->codigoMetodoPago = 1;
        $facturaSectorEducativo->numeroTarjeta = null;
        $facturaSectorEducativo->montoTotal = 100;
        $facturaSectorEducativo->montoTotalSujetoIva = 100;
        $facturaSectorEducativo->codigoMoneda = 1;
        $facturaSectorEducativo->tipoCambio = 1;
        $facturaSectorEducativo->montoTotalMoneda = 100;
        $facturaSectorEducativo->montoGiftCard = null;
        $facturaSectorEducativo->descuentoAdicional = 0;
        $facturaSectorEducativo->codigoExcepcion = null;
        $facturaSectorEducativo->cafc = null;
        $facturaSectorEducativo->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
            'términos ofertados o convenidos.';
        $facturaSectorEducativo->usuario = 'pperez';

        $detalle = new FacturaSectorEducativoDetalle();
        $detalle->actividadEconomica = '853000';
        $detalle->codigoProductoSin = 925109;
        $detalle->codigoProducto = '123456';
        $detalle->descripcion = 'Pension mes de Junio';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;

        $facturaSectorEducativo->agregarLinea($detalle);

        $facturaSectorEducativo->cufd = self::$cufd0->codigo;
        $facturaSectorEducativo->codigoControl = self::$cufd0->codigoControl;
        $facturaSectorEducativo->cuis = self::$cuis0;
        var_dump($facturaSectorEducativo);
        $response0 = self::$siat0->recepcionFactura($facturaSectorEducativo);
        var_dump($response0);
        $this->assertEquals(true, $response0->transaccion);
        $this->assertNotEmpty($response0->codigoRecepcion);
    }
    
    public function testRecepcionPaquetefacturaSectorEducativo()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);
        $builder = new FacturaSectorEducativoBuilder();
        $facturas = [];

        for ($i=0; $i < 500; $i++) {
            $builder->createFactura();
            $builder->emisionOffline();
            $builder->modalidadComputarizada();
            $builder->conDerechoCreditoFiscal();

            $facturaSectorEducativo = $builder->getFactura();
            $facturaSectorEducativo->nitEmisor = 124849020;
            $facturaSectorEducativo->razonSocialEmisor = 'CORPORACION EDUCATIVA CUMBRE S.A.';
            $facturaSectorEducativo->municipio = 'Santa Cruz de la Sierra';
            $facturaSectorEducativo->telefono = '+59178595684';
            $facturaSectorEducativo->numeroFactura = 7;
            $facturaSectorEducativo->codigoSucursal = 0;
            $facturaSectorEducativo->direccion = 'AVENIDA CAÑOTO NRO.580 ZONA PANAMERICANA';
            $facturaSectorEducativo->codigoPuntoVenta = 0;
            $facturaSectorEducativo->fechaEmision = time();
            $facturaSectorEducativo->nombreRazonSocial = 'Mi razon social';
            $facturaSectorEducativo->codigoTipoDocumentoIdentidad = 1;
            $facturaSectorEducativo->numeroDocumento = '5115889';
            $facturaSectorEducativo->complemento = null;
            $facturaSectorEducativo->codigoCliente = '51158891';
            $facturaSectorEducativo->nombreEstudiante = 'Marcelo Campos Vargas';
            $facturaSectorEducativo->periodoFacturado = 'I-2022';
            $facturaSectorEducativo->codigoMetodoPago = 1;
            $facturaSectorEducativo->numeroTarjeta = null;
            $facturaSectorEducativo->montoTotal = 100;
            $facturaSectorEducativo->montoTotalSujetoIva = 100;
            $facturaSectorEducativo->codigoMoneda = 1;
            $facturaSectorEducativo->tipoCambio = 1;
            $facturaSectorEducativo->montoTotalMoneda = 100;
            $facturaSectorEducativo->montoGiftCard = null;
            $facturaSectorEducativo->descuentoAdicional = 0;
            $facturaSectorEducativo->codigoExcepcion = null;
            $facturaSectorEducativo->cafc = null;
            $facturaSectorEducativo->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
                'términos ofertados o convenidos.';
            $facturaSectorEducativo->usuario = 'pperez';

            $detalle = new FacturaSectorEducativoDetalle();
            $detalle->actividadEconomica = '853000';
            $detalle->codigoProductoSin = 925109;
            $detalle->codigoProducto = '123456';
            $detalle->descripcion = 'Pension mes de Junio';
            $detalle->cantidad = 1;
            $detalle->unidadMedida = 1;
            $detalle->precioUnitario = 100;
            $detalle->montoDescuento = 0;
            $detalle->subTotal = 100;

            $facturaSectorEducativo->agregarLinea($detalle);

            $facturaSectorEducativo->cufd = self::$cufd0->codigo;
            $facturaSectorEducativo->codigoControl = self::$cufd0->codigoControl;
            $facturaSectorEducativo->cuis = self::$cuis0;
            array_push($facturas, $facturaSectorEducativo);
        }

        $paqueteFactura = new PaqueteFacturaSectorEducativo($facturas);
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

    public function testAnulacionFacturaSectorEducativo()
    {
        date_default_timezone_set(self::DEFAULT_TIMEZONE);

        $faker = \Faker\Factory::create();

        $builder = new FacturaSectorEducativoBuilder();
        $builder->createFactura();
        $builder->emisionOnline();
        $builder->modalidadComputarizada();
        $builder->conDerechoCreditoFiscal();

        $facturaSectorEducativo = $builder->getFactura();
        $facturaSectorEducativo->nitEmisor = 124849020;
        $facturaSectorEducativo->razonSocialEmisor = 'CORPORACION EDUCATIVA CUMBRE S.A.';
        $facturaSectorEducativo->municipio = 'Santa Cruz de la Sierra';
        $facturaSectorEducativo->telefono = '+59178595684';
        $facturaSectorEducativo->numeroFactura = 7;
        $facturaSectorEducativo->codigoSucursal = 0;
        $facturaSectorEducativo->direccion = 'AVENIDA CAÑOTO NRO.580 ZONA PANAMERICANA';
        $facturaSectorEducativo->codigoPuntoVenta = 0;
        $facturaSectorEducativo->fechaEmision = time();
        $facturaSectorEducativo->nombreRazonSocial = 'Mi razon social';
        $facturaSectorEducativo->codigoTipoDocumentoIdentidad = 1;
        $facturaSectorEducativo->numeroDocumento = '5115889';
        $facturaSectorEducativo->complemento = null;
        $facturaSectorEducativo->codigoCliente = '51158891';
        $facturaSectorEducativo->nombreEstudiante = 'Marcelo Campos Vargas';
        $facturaSectorEducativo->periodoFacturado = 'I-2022';
        $facturaSectorEducativo->codigoMetodoPago = 1;
        $facturaSectorEducativo->numeroTarjeta = null;
        $facturaSectorEducativo->montoTotal = 100;
        $facturaSectorEducativo->montoTotalSujetoIva = 100;
        $facturaSectorEducativo->codigoMoneda = 1;
        $facturaSectorEducativo->tipoCambio = 1;
        $facturaSectorEducativo->montoTotalMoneda = 100;
        $facturaSectorEducativo->montoGiftCard = null;
        $facturaSectorEducativo->descuentoAdicional = 0;
        $facturaSectorEducativo->codigoExcepcion = null;
        $facturaSectorEducativo->cafc = null;
        $facturaSectorEducativo->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
            'términos ofertados o convenidos.';
        $facturaSectorEducativo->usuario = 'pperez';


        $detalle = new FacturaSectorEducativoDetalle();
        $detalle->actividadEconomica = '853000';
        $detalle->codigoProductoSin = 925109;
        $detalle->codigoProducto = '123456';
        $detalle->descripcion = 'Pension mes de Junio';
        $detalle->cantidad = 1;
        $detalle->unidadMedida = 1;
        $detalle->precioUnitario = 100;
        $detalle->montoDescuento = 0;
        $detalle->subTotal = 100;

        $facturaSectorEducativo->agregarLinea($detalle);

        $facturaSectorEducativo->cufd = self::$cufd0->codigo;
        $facturaSectorEducativo->codigoControl = self::$cufd0->codigoControl;
        $facturaSectorEducativo->cuis = self::$cuis0;

        $response0 = self::$siat0->recepcionFactura($facturaSectorEducativo);

        $response1 = self::$siat0->anulacionFactura($facturaSectorEducativo, 1);

        $this->assertEquals(905, $response1->codigoEstado);
        $this->assertEquals(true, $response1->transaccion);
    }
}
