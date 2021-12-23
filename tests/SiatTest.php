<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use KingsonDe\Marshal\MarshalXml;
use PHPUnit\Framework\TestCase;

use Enors\Siat\Facturas\FacturaCompraVenta;
use Enors\Siat\Facturas\FacturaCompraVentaDetalle;
use Enors\Siat\Mappers\FacturaCompraVentaMapper;

class SiatTest extends TestCase
{
    public function testGenerarCUF()
    {
        $nit = 123456789;
        $siat = new Siat('', $nit, '', 0, 0, Siat::MODALIDAD_ELECTRONICA_EN_LINEA);
        $timestamp = strtotime('2019-01-13 16:37:21');
        $nroFactura = 1;
        $cufd = 'A19E23EF34124CD';
        $cuf = $siat->generarCUF($timestamp, $nroFactura, $cufd);
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }

    public function testFacturaComputarizadaMapper()
    {
        $facturaCompraVenta = new FacturaCompraVenta();
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
        $facturaCompraVenta->codigoDocumentoSector = 1;

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
}
