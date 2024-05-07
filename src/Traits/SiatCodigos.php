<?php

namespace Enors\Siat\Traits;

/**
 * @deprecated
 */
trait SiatCodigos
{
    private function getCodigosClient()
    {
        if (!isset($this->codigosClient)) {
            $opts = [
                'http' => [
                    'header' => 'apiKey: TokenApi ' . $this->token
                ]
            ];
            $context = stream_context_create($opts);
            $this->codigosClient = new \SoapClient(self::SIAT_CODIGOS_WSDL, ['stream_context' => $context]);
        }
        return $this->codigosClient;
    }

    public function solicitarCUIS()
    {
        $client = $this->getCodigosClient();
        $response = $client->cuis([
            'SolicitudCuis' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaCuis;
    }

    public function solicitarCUFD(string $cuis)
    {
        $client = $this->getCodigosClient();
        $response = $client->cufd([
            'SolicitudCufd' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ]
        ]);
        return $response->RespuestaCufd;
    }
}
