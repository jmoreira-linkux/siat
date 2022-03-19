<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaActividades ([codigoCaeb] => string, [descripcion] => string, [tipoActividad] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaActividades
{
    public function __construct(\stdClass $object)
    {
        $this->listaActividades = isset($object->listaActividades) ? $object->listaActividades : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
