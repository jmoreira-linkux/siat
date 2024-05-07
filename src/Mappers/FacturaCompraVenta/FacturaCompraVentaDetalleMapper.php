<?php

namespace Enors\Siat\Mappers\FacturaCompraVenta;

use Enors\Siat\Facturas\CompraVenta\FacturaCompraVentaDetalle;
use Enors\Siat\Mappers\AbstractMapper;

class FacturaCompraVentaDetalleMapper extends AbstractMapper
{
    public function map(FacturaCompraVentaDetalle $detalle)
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
            'numeroSerie' => isset($detalle->numeroSerie) ? htmlspecialchars($detalle->numeroSerie, ENT_XML1) : $this->nil(),
            'numeroImei' => isset($detalle->numeroImei) ? htmlspecialchars($detalle->numeroImei, ENT_XML1) : $this->nil(),
        ];
        return ['detalle' => $map];
    }
}
