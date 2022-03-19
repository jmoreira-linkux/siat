<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaLeyendas ([codigoActividad] => string, [descripcionLeyenda] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaParametricasLeyendas
{
    public function __construct(\stdClass $object)
    {
        $this->listaLeyendas = isset($object->listaLeyendas) ? $object->listaLeyendas : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
