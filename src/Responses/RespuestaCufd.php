<?php

namespace Enors\Siat\Responses;

/**
 * @property string $codigo
 * @property string $codigoControl
 * @property string $direccion
 * @property string $fechaVigencia
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaCufd
{
    public function __construct(\stdClass $object)
    {
        $this->codigo = $object->codigo;
        $this->codigoControl = $object->codigoControl;
        $this->direccion = $object->direccion;
        $this->fechaVigencia = $object->fechaVigencia;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
