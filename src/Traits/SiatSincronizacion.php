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
