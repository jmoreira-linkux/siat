<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaCodigos ([codigoClasificador] => int, [descripcion] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaParametricas
{
    public function __construct(\stdClass $object)
    {
        $this->listaCodigos = isset($object->listaCodigos) ? $object->listaCodigos : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
