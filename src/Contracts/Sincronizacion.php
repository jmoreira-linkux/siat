<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\Responses\RespuestaFechaHora;
use Enors\Siat\Responses\RespuestaListaActividades;
use Enors\Siat\Responses\RespuestaListaActividadesDocumentoSector;
use Enors\Siat\Responses\RespuestaListaParametricas;
use Enors\Siat\Responses\RespuestaListaParametricasLeyendas;
use Enors\Siat\Responses\RespuestaListaProductos;

interface Sincronizacion
{
    public function sincronizarActividades(string $cuis): RespuestaListaActividades;
    public function sincronizarActividadesDocumentoSector(string $cuis): RespuestaListaActividadesDocumentoSector;
    public function sincronizarFechaHora(string $cuis): RespuestaFechaHora;
    public function sincronizarListaLeyendasFactura(string $cuis): RespuestaListaParametricasLeyendas;
    public function sincronizarListaMensajesServicios(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaEventosSignificativos(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaListaProductosServicios(string $cuis): RespuestaListaProductos;
    public function sincronizarParametricaMotivoAnulacion(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaPaisOrigen(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoDocumentoIdentidad(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoDocumentoSector(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoEmision(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoHabitacion(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoFactura(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoMetodoPago(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoMoneda(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaTipoPuntoVenta(string $cuis): RespuestaListaParametricas;
    public function sincronizarParametricaUnidadMedida(string $cuis): RespuestaListaParametricas;
}
