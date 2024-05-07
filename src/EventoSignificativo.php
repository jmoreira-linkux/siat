<?php

namespace Enors\Siat;

class EventoSignificativo
{
    public function __construct(
        int $codigoEvento,
        string $descripcion,
        int $fechaInicioEvento,
        int $fechaFinEvento,
        string $cufdEvento
    ) {
        $this->codigoEvento = $codigoEvento;
        $this->descripcion = $descripcion;
        $this->fechaInicioEvento = $fechaInicioEvento;
        $this->fechaFinEvento = $fechaFinEvento;
        $this->cufdEvento = $cufdEvento;
    }
}
