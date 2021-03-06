<?php

namespace Enors\Siat\Mappers\FacturaCompraVenta;

use Enors\Siat\Facturas\CompraVenta\FacturaCompraVentaDetalle;
use Enors\Siat\Mappers\AbstractMapper;

class FacturaCompraVentaDetalleMapper extends AbstractMapper
{
    public function map(FacturaCompraVentaDetalle $detalle)
    {
        $map = [
            'actividadEconomica' => $detalle->actividadEconomica,
            'codigoProductoSin' => $detalle->codigoProductoSin,
            'codigoProducto' => $detalle->codigoProducto,
            'descripcion' => $detalle->descripcion,
            'cantidad' => $detalle->cantidad,
            'unidadMedida' => $detalle->unidadMedida,
            'precioUnitario' => $detalle->precioUnitario,
            'montoDescuento' => isset($detalle->montoDescuento) ? $detalle->montoDescuento : $this->nil(),
            'subTotal' => $detalle->subTotal,
            'numeroSerie' => isset($detalle->numeroSerie) ? $detalle->numeroSerie : $this->nil(),
            'numeroImei' => isset($detalle->numeroImei) ? $detalle->numeroImei : $this->nil(),
        ];
        return ['detalle' => $map];
    }
}
