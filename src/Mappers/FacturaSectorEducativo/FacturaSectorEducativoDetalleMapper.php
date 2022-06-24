<?php

namespace Enors\Siat\Mappers\FacturaSectorEducativo;

use Enors\Siat\Facturas\Educacion\FacturaSectorEducativoDetalle;
use Enors\Siat\Mappers\AbstractMapper;

class FacturaSectorEducativoDetalleMapper extends AbstractMapper
{
    public function map(FacturaSectorEducativoDetalle $detalle)
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
        ];
        return ['detalle' => $map];
    }
}
