<?php

namespace Enors\Siat\Responses;

/**
 * @property string $fechaHora
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaFechaHora
{
    public function __construct(\stdClass $object)
    {
        $this->fechaHora = $object->fechaHora;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
