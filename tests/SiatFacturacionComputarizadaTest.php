<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

use Enors\Siat\Facturas\FacturaCompraVenta;
use Enors\Siat\Facturas\FacturaCompraVentaDetalle;

class SiatFacturacionComputarizadaTest extends TestCase
{
    private static $siat0;
    private static $cuis0;
    private static $cufd0;

    const CODIGO_PUNTO_VENTA = 3;

    public static function setUpBeforeClass(): void
    {
        self::$siat0 = new Siat(
            $_ENV['SIAT_CODIGO_SISTEMA'],
            $_ENV['SIAT_NIT'],
            $_ENV['SIAT_API_KEY'],
            0,
            0,
            Siat::MODALIDAD_COMPUTARIZADA
        );
        self::$cuis0 = self::$siat0->solicitarCUIS()->codigo;
        self::$cufd0 = self::$siat0->solicitarCUFD(self::$cuis0);
    }

    public function testRecepcionFacturaCompraVenta()
    {
        date_default_timezone_set('America/La_Paz');
        $facturaCompraVenta = new FacturaCompraVenta();
        $facturaCompraVenta->nitEmisor = 346141028;
        $facturaCompraVenta->razonSocialEmisor = 'Carlos Loza';
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
        $facturaCompraVenta->leyenda = 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y ' .
            'términos ofertados o convenidos.';
        $facturaCompraVenta->usuario = 'pperez';
        $facturaCompraVenta->codigoDocumentoSector = 1;

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

        $cuf = self::$siat0->generarCUF(
            $facturaCompraVenta->fechaEmision,
            $facturaCompraVenta->numeroFactura,
            self::$cufd0->codigoControl
        );
        $facturaCompraVenta->cuf = $cuf;
        $facturaCompraVenta->cufd = self::$cufd0->codigo;

        $response0 = self::$siat0->recepcionFacturaCompraVenta(
            self::$cuis0,
            self::$cufd0->codigo,
            $facturaCompraVenta
        );

        $this->assertEquals(true, $response0->RespuestaServicioFacturacion->transaccion);
        $this->assertNotEmpty($response0->RespuestaServicioFacturacion->codigoRecepcion);
    }
    
    public function testRecepcionPaqueteFacturaCompraVenta()
    {
        date_default_timezone_set('America/La_Paz');
        $facturas = [];

        for ($i=0; $i < 500; $i++) {
            $facturaCompraVenta = new FacturaCompraVenta();
            $facturaCompraVenta->nitEmisor = 346141028;
            $facturaCompraVenta->razonSocialEmisor = 'Carlos Loza';
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
            $facturaCompraVenta->codigoDocumentoSector = 1;
            $facturaCompraVenta->codigoEmision = Siat::TIPO_EMISION_OFFLINE;

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

            $cuf = self::$siat0->generarCUF(
                $facturaCompraVenta->fechaEmision,
                $facturaCompraVenta->numeroFactura,
                self::$cufd0->codigoControl
            );
            $facturaCompraVenta->cuf = $cuf;
            $facturaCompraVenta->cufd = self::$cufd0->codigo;

            array_push($facturas, $facturaCompraVenta);
        }

        $response0 = self::$siat0->recepcionPaqueteFactura(
            self::$cuis0,
            self::$cufd0->codigo,
            $facturas
        );

        $this->assertEquals(901, $response0->codigoEstado);
        $this->assertEquals(true, $response0->transaccion);
        $this->assertNotEmpty($response0->codigoRecepcion);

        $response1 = self::$siat0->validacionRecepcionPaqueteFactura(
            self::$cuis0,
            self::$cufd0->codigo,
            $response0->codigoRecepcion
        );
        $this->assertEquals(true, $response0->transaccion);
    }

    public function testAnulacionFacturaCompraVenta()
    {
        date_default_timezone_set('America/La_Paz');

        $faker = \Faker\Factory::create();

        $facturaCompraVenta = new FacturaCompraVenta();
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
        $facturaCompraVenta->codigoDocumentoSector = 1;

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

        $cuf = self::$siat0->generarCUF(
            $facturaCompraVenta->fechaEmision,
            $facturaCompraVenta->numeroFactura,
            self::$cufd0->codigoControl
        );
        $facturaCompraVenta->cuf = $cuf;
        $facturaCompraVenta->cufd = self::$cufd0->codigo;

        $response0 = self::$siat0->recepcionFacturaCompraVenta(
            self::$cuis0,
            self::$cufd0->codigo,
            $facturaCompraVenta
        );

        $response1 = self::$siat0->anulacionFactura(
            self::$cuis0,
            self::$cufd0->codigo,
            $cuf,
            1,
        );

        $this->assertEquals(905, $response1->codigoEstado);
        $this->assertEquals(true, $response1->transaccion);
    }
}
