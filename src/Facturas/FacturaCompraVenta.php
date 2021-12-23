<?php

namespace Enors\Siat\Facturas;

use Enors\Siat\Siat;

class FacturaCompraVenta
{
    public $nitEmisor = '';
    public $razonSocialEmisor = '';
    public $municipio = '';
    public $telefono = null;
    public $numeroFactura = '';
    public $cuf = '';
    public $cufd = '';
    public $codigoSucursal = 0;
    public $direccion = '';
    public $codigoPuntoVenta = 0;
    public $fechaEmision = 0;
    public $nombreRazonSocial = null;
    public $codigoTipoDocumentoIdentidad = '';
    public $numeroDocumento = '';
    public $complemento = null;
    public $codigoCliente = '';
    public $codigoMetodoPago = '';
    public $numeroTarjeta = null;
    public $montoTotal = '';
    public $montoTotalSujetoIva = '';
    public $codigoMoneda = '';
    public $tipoCambio = '';
    public $montoTotalMoneda = '';
    public $montoGiftCard = null;
    public $descuentoAdicional = null;
    public $codigoExcepcion = null;
    public $cafc = null;
    public $leyenda = '';
    public $usuario = '';
    /**
     * Código que identifica el sector de la Factura.
     */
    public $codigoDocumentoSector = Siat::TIPO_DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA;
    /**
     * Describe si la emisión se realizó en línea.
     */
    public $codigoEmision = Siat::TIPO_EMISION_ONLINE;
    /**
     * Código que identifica el Tipo de Factura que se está enviando.
     */
    public $tipoFacturaDocumento = Siat::TIPO_FACTURA_CON_DERECHO_CREDITO_FISCAL;

    public $detalle = [];

    public function agregarLinea(FacturaCompraVentaDetalle $linea)
    {
        array_push($this->detalle, $linea);
    }
}
