<?php

namespace Enors\Siat;

class EventoSignificativo
{
    public function __construct(
        int $codigoEvento,
        string $descripcion,
        string $fechaInicioEvento,
        string $fechaFinEvento
    ) {
        $this->codigoEvento = $codigoEvento;
        $this->descripcion = $descripcion;
        $this->fechaInicioEvento = $fechaInicioEvento;
        $this->fechaFinEvento = $fechaFinEvento;
    }
}
