<?php

namespace Enors\Siat\Facturas;

abstract class AbstractFacturaDetalle
{
    public $actividadEconomica = '';
    public $codigoProductoSin = '';
    public $codigoProducto = '';
    public $descripcion = '';
    public $cantidad = 0;
    public $unidadMedida = '';
    public $precioUnitario = '';
    public $montoDescuento = null;
    public $subTotal = '';
    public $numeroSerie = null;
    public $numeroImei = null;
}
