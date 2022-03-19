<?php

namespace Enors\Siat\Responses;

/**
 * @property int $codigoPuntoVenta
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaCierrePuntoVenta
{
    public function __construct(\stdClass $object)
    {
        $this->codigoPuntoVenta = $object->codigoPuntoVenta;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
