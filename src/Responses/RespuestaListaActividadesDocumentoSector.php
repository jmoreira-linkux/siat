<?php

namespace Enors\Siat\Responses;

/**
 * @property array $listaActividadesDocumentoSector
 *   ([codigoActividad] => string, [codigoDocumentoSector] => int, [tipoDocumentoSector] => string)
 * @property array $mensajesList
 * @property bool $transaccion
 */
class RespuestaListaActividadesDocumentoSector
{
    public function __construct(\stdClass $object)
    {
        $this->listaActividadesDocumentoSector = isset($object->listaActividadesDocumentoSector)
            ? $object->listaActividadesDocumentoSector
            : [];
        $this->mensajesList = isset($object->mensajesList) ? $object->mensajesList : [];
        $this->transaccion = $object->transaccion;
    }
}
