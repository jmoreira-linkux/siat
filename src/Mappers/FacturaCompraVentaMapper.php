<?php

namespace Enors\Siat\Mappers;

use Enors\Siat\Facturas\FacturaCompraVenta;

class FacturaCompraVentaMapper extends AbstractMapper
{
    public function map(FacturaCompraVenta $factura)
    {
        return [
            'facturaComputarizadaCompraVenta' => [
                $this->attributes() => [
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:noNamespaceSchemaLocation' => 'facturaComputarizadaCompraVenta.xsd',
                ],
                'cabecera' => $this->item(new FacturaCompraVentaCabeceraMapper(), $factura),
                $this->collection(new FacturaCompraVentaDetalleMapper(), $factura->detalle),
            ]
        ];
    }
}
