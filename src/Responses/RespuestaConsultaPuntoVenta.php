<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaPuntosVentas
 *   ([codigoPuntoVenta] => int, [nombrePuntoVenta] => string, [tipoPuntoVenta] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaConsultaPuntoVenta
{
    public function __construct(\stdClass $object)
    {
        $this->listaPuntosVentas = isset($object->listaPuntosVentas) ? $object->listaPuntosVentas : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
