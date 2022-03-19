<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaCodigos
 *   ([codigoActividad] => string, [codigoProducto] => int, [descripcionProducto] => string, [nandina] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaProductos
{
    public function __construct(\stdClass $object)
    {
        $this->listaCodigos = isset($object->listaCodigos) ? $object->listaCodigos : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
