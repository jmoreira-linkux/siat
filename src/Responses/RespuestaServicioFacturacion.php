<?php

namespace Enors\Siat\Responses;

/**
 * @property int $codigoDescripcion
 * @property int $codigoEstado
 * @property string $codigoRecepcion
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaServicioFacturacion
{
    public function __construct(\stdClass $object)
    {
        $this->codigoDescripcion = $object->codigoDescripcion;
        $this->codigoEstado = $object->codigoEstado;
        $this->codigoRecepcion = isset($object->codigoRecepcion) ? $object->codigoRecepcion: null;
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
