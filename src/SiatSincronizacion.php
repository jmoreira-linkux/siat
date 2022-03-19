<?php

namespace Enors\Siat;

use Enors\Siat\Contracts\Sincronizacion;
use Enors\Siat\Responses\RespuestaFechaHora;
use Enors\Siat\Responses\RespuestaListaActividades;
use Enors\Siat\Responses\RespuestaListaActividadesDocumentoSector;
use Enors\Siat\Responses\RespuestaListaParametricas;
use Enors\Siat\Responses\RespuestaListaParametricasLeyendas;
use Enors\Siat\Responses\RespuestaListaProductos;

class SiatSincronizacion extends AbstractSiat implements Sincronizacion
{
    const SIAT_SINCRONIZACION_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';

    protected function getWSDL(): string
    {
        return self::SIAT_SINCRONIZACION_WSDL;
    }

    private function callSyncMethod($syncMethod, $cuis)
    {
        $response = $this->client->$syncMethod([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response;
    }

    public function sincronizarActividades(string $cuis): RespuestaListaActividades
    {
        $response = $this->callSyncMethod('sincronizarActividades', $cuis);
        return new RespuestaListaActividades($response->RespuestaListaActividades);
    }

    public function sincronizarActividadesDocumentoSector(string $cuis): RespuestaListaActividadesDocumentoSector
    {
        $response = $this->callSyncMethod('sincronizarListaActividadesDocumentoSector', $cuis);
        return new RespuestaListaActividadesDocumentoSector($response->RespuestaListaActividadesDocumentoSector);
    }

    public function sincronizarFechaHora(string $cuis): RespuestaFechaHora
    {
        $response = $this->callSyncMethod('sincronizarFechaHora', $cuis);
        return new RespuestaFechaHora($response->RespuestaFechaHora);
    }

    public function sincronizarListaLeyendasFactura(string $cuis): RespuestaListaParametricasLeyendas
    {
        $response = $this->callSyncMethod('sincronizarListaLeyendasFactura', $cuis);
        return new RespuestaListaParametricasLeyendas($response->RespuestaListaParametricasLeyendas);
    }

    public function sincronizarListaMensajesServicios(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarListaMensajesServicios', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaEventosSignificativos(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaEventosSignificativos', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaListaProductosServicios(string $cuis): RespuestaListaProductos
    {
        $response = $this->callSyncMethod('sincronizarListaProductosServicios', $cuis);
        return new RespuestaListaProductos($response->RespuestaListaProductos);
    }

    public function sincronizarParametricaMotivoAnulacion(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaMotivoAnulacion', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaPaisOrigen(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaPaisOrigen', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoDocumentoIdentidad(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoDocumentoIdentidad', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoDocumentoSector(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoDocumentoSector', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoEmision(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoEmision', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoHabitacion(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoHabitacion', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoFactura(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTiposFactura', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoMetodoPago(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoMetodoPago', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoMoneda(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoMoneda', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaTipoPuntoVenta(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaTipoPuntoVenta', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }

    public function sincronizarParametricaUnidadMedida(string $cuis): RespuestaListaParametricas
    {
        $response = $this->callSyncMethod('sincronizarParametricaUnidadMedida', $cuis);
        return new RespuestaListaParametricas($response->RespuestaListaParametricas);
    }
}
