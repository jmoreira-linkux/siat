<?php

namespace Enors\Siat\Responses;

/**
 * @property string $codigoSistema
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaCierreSistemas
{
    public function __construct(\stdClass $object)
    {
        $this->codigoSistema = $object->codigoSistema;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
