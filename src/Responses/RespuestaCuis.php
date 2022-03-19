<?php

namespace Enors\Siat\Responses;

/**
 * @property string $codigo
 * @property string $fechaVigencia
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaCuis
{
    public function __construct(\stdClass $object)
    {
        $this->codigo = $object->codigo;
        $this->fechaVigencia = $object->fechaVigencia;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
