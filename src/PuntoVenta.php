<?php

namespace Enors\Siat;

class PuntoVenta
{
    public function __construct(
        int $codigoTipoPuntoVenta,
        string $nombrePuntoVenta,
        string $descripcion
    ) {
        $this->codigoTipoPuntoVenta = $codigoTipoPuntoVenta;
        $this->nombrePuntoVenta = $nombrePuntoVenta;
        $this->descripcion = $descripcion;
    }
}
