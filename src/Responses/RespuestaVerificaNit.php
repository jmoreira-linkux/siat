<?php

namespace Enors\Siat\Responses;

/**
 * @property string $codigoSistema
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaVerificaNit
{
    public function __construct(\stdClass $object)
    {
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
