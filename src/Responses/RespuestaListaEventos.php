<?php

namespace Enors\Siat\Responses;

/**
 * @property int $codigoRecepcionEventoSignificativo
 * @property array $listaCodigos
 *   (
 *     [codigoEvento] => int,
 *     [codigoRecepcionEventoSignificativo] => int,
 *     [descripcion] => string,
 *     [fechaInicio] => string,
 *     [fechaFin] => string
 *   )
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaEventos
{
    public function __construct(\stdClass $object)
    {
        $this->codigoRecepcionEventoSignificativo = isset($object->codigoRecepcionEventoSignificativo)
            ? $object->codigoRecepcionEventoSignificativo
            : null;
        $this->listaCodigos = isset($object->listaCodigos) ? $object->listaCodigos : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
