<?php

namespace Enors\Siat\Mappers\FacturaSectorEducativo;

use Enors\Siat\Facturas\Educacion\FacturaSectorEducativoDetalle;
use Enors\Siat\Mappers\AbstractMapper;

class FacturaSectorEducativoDetalleMapper extends AbstractMapper
{
    public function map(FacturaSectorEducativoDetalle $detalle)
    {
        $map = [
            'actividadEconomica' => htmlspecialchars($detalle->actividadEconomica, ENT_XML1),
            'codigoProductoSin' => $detalle->codigoProductoSin,
            'codigoProducto' => htmlspecialchars($detalle->codigoProducto, ENT_XML1),
            'descripcion' => htmlspecialchars($detalle->descripcion, ENT_XML1),
            'cantidad' => $detalle->cantidad,
            'unidadMedida' => $detalle->unidadMedida,
            'precioUnitario' => $detalle->precioUnitario,
            'montoDescuento' => isset($detalle->montoDescuento) ? $detalle->montoDescuento : $this->nil(),
            'subTotal' => $detalle->subTotal,
        ];
        return ['detalle' => $map];
    }
}
