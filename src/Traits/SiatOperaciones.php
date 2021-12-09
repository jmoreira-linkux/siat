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
            var_dump($this->operacionesClient);
        }
        return $this->operacionesClient;
    }

    public function registroEventoSignificativo($cuis, $cufd, $descripcion, $fechaInicioEvento, $fechaFinEvento)
    {
        var_dump('fecha inicio: '.$fechaInicioEvento. ' fecha fin: '.$fechaFinEvento);
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
                'descripcion' => $this->codigoEvento,
                'fechaHoraInicioEvento' => $fechaInicioEvento,
                'fechaHoraFinEvento' => $fechaFinEvento,
                'cufdEvento' => $cufd
            ],
        ]);
        var_dump($response);
        return $response->RespuestaListaEventos;
    }
}