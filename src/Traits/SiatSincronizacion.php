<?php

namespace Enors\Siat\Traits;

trait SiatSincronizacion
{
    private function getSincronizacionClient()
    {
        if (!isset($this->sincronizacionClient)) {
            $opts = [
                'http' => [
                    'header' => 'Authorization: Token ' . $this->token
                ]
            ];
            $context = stream_context_create($opts);
            $this->sincronizacionClient = new \SoapClient(self::SIAT_SINCRONIZACION_WSDL, [
                'stream_context' => $context
            ]);
        }
        return $this->sincronizacionClient;
    }

    /**
     * Este proceso tiene como objetivo sincronizar la fecha y hora del Sistema de Facturación del Contribuyente
     * con los datos proporcionadas por el SIN.
     *
     * @param string $cuis
     * @return string Format 2021-12-04T00:19:57.987
     */
    public function sincronizarFechaHora($cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarFechaHora([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaFechaHora->fechaHora;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * países que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaPaisOrigen($cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaPaisOrigen([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * unidades de medida que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaUnidadMedida($cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaUnidadMedida([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }
}
