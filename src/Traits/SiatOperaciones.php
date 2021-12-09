<?php

namespace Enors\Siat\Traits;

trait SiatOperaciones
{
    private function getOperacionesClient()
    {
        if (!isset($this->operacionesClient)) {
            $opts = [
                'http' => [
                    'header' => 'Authorization: Token ' . $this->token
                ]
            ];
            $context = stream_context_create($opts);
            $this->operacionesClient = new \SoapClient(self::SIAT_OPERACIONES_WSDL, [
                'stream_context' => $context
            ]);
        }
        return $this->operacionesClient;
    }

    public function registroEventoSignificativo($cuis, $cufd, $descripcion, $fechaInicioEvento, $fechaFinEvento)
    {
        $client = $this->getOperacionesClient();
        $response = $client->registroEventoSignificativo([
            'SolicitudEventoSignificativo' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'cufd' => $cufd,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoMotivoEvento' => $this->codigoEvento,
                'descripcion' => $descripcion,
                'fechaHoraInicioEvento' => $fechaInicioEvento,
                'fechaHoraFinEvento' => $fechaFinEvento,
                'cufdEvento' => $cufd
            ],
        ]);
        return $response->RespuestaListaEventos;
    }

    public function registroPuntoVenta($cuis, $descripcion, $nombrePuntoVenta)
    {
        $client = $this->getOperacionesClient();
        $response = $client->registroPuntoVenta([
            'SolicitudRegistroPuntoVenta' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoModalidad' => $this->codigoModalidad,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoTipoPuntoVenta' => 2,
                'cuis' => $cuis,
                'descripcion' => $descripcion,
                'nit' => $this->nit,
                'nombrePuntoVenta' => $nombrePuntoVenta
            ],
        ]);
        return $response->RespuestaRegistroPuntoVenta;
    }

}
