<?php

namespace Enors\Siat\Mappers\FacturaCompraVenta;

use Enors\Siat\Facturas\CompraVenta\FacturaCompraVenta;
use Enors\Siat\Mappers\AbstractMapper;
use Enors\Siat\SiatConstants;

class FacturaCompraVentaCabeceraMapper extends AbstractMapper
{
    public function map(FacturaCompraVenta $factura)
    {
        return [
            'nitEmisor' => $factura->nitEmisor,
            'razonSocialEmisor' => htmlspecialchars($factura->razonSocialEmisor, ENT_XML1),
            'municipio' => htmlspecialchars($factura->municipio, ENT_XML1),
            'telefono' => isset($factura->telefono) ? htmlspecialchars($factura->telefono, ENT_XML1) : $this->nil(),
            'numeroFactura' => $factura->numeroFactura,
            'cuf' => htmlspecialchars($factura->getCuf(), ENT_XML1),
            'cufd' => htmlspecialchars($factura->getCufd(), ENT_XML1),
            'codigoSucursal' => $factura->codigoSucursal,
            'direccion' => htmlspecialchars($factura->direccion, ENT_XML1),
            'codigoPuntoVenta' => isset($factura->codigoPuntoVenta) ? $factura->codigoPuntoVenta : $this->nil(),
            'fechaEmision' => date(SiatConstants::DATE_TIME_FORMAT, $factura->fechaEmision),
            'nombreRazonSocial' => isset($factura->nombreRazonSocial) ? htmlspecialchars($factura->nombreRazonSocial, ENT_XML1) : $this->nil(),
            'codigoTipoDocumentoIdentidad' => $factura->codigoTipoDocumentoIdentidad,
            'numeroDocumento' => htmlspecialchars($factura->numeroDocumento, ENT_XML1),
            'complemento' => isset($factura->complemento) ? htmlspecialchars($factura->complemento, ENT_XML1) : $this->nil(),
            'codigoCliente' => htmlspecialchars($factura->codigoCliente, ENT_XML1),
            'codigoMetodoPago' => $factura->codigoMetodoPago,
            'numeroTarjeta' => isset($factura->numeroTarjeta) ? $factura->numeroTarjeta : $this->nil(),
            'montoTotal' => $factura->montoTotal,
            'montoTotalSujetoIva' => $factura->montoTotalSujetoIva,
            'codigoMoneda' => $factura->codigoMoneda,
            'tipoCambio' => $factura->tipoCambio,
            'montoTotalMoneda' => $factura->montoTotalMoneda,
            'montoGiftCard' => isset($factura->montoGiftCard) ? $factura->montoGiftCard : $this->nil(),
            'descuentoAdicional' => isset($factura->descuentoAdicional) ? $factura->descuentoAdicional : $this->nil(),
            'codigoExcepcion' => isset($factura->codigoExcepcion) ? $factura->codigoExcepcion : $this->nil(),
            'cafc' => isset($factura->cafc) ? htmlspecialchars($factura->cafc, ENT_XML1) : $this->nil(),
            'leyenda' => htmlspecialchars($factura->leyenda, ENT_XML1),
            'usuario' => htmlspecialchars($factura->usuario, ENT_XML1),
            'codigoDocumentoSector' => $factura->getCodigoDocumentoSector(),
        ];
    }
}
