<?php

namespace Enors\Siat\Traits;

use Enors\Siat\EventoSignificativo;
use Enors\Siat\PuntoVenta;

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

    public function registroEventoSignificativo(string $cuis, string $cufd, EventoSignificativo $eventoSignificativo)
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
                'codigoMotivoEvento' => $eventoSignificativo->codigoEvento,
                'descripcion' => $eventoSignificativo->descripcion,
                'fechaHoraInicioEvento' => $eventoSignificativo->fechaInicioEvento,
                'fechaHoraFinEvento' => $eventoSignificativo->fechaFinEvento,
                'cufdEvento' => $cufd
            ],
        ]);
        return $response->RespuestaListaEventos;
    }

    public function registroPuntoVenta(string $cuis, PuntoVenta $puntoVenta)
    {
        $client = $this->getOperacionesClient();
        $response = $client->registroPuntoVenta([
            'SolicitudRegistroPuntoVenta' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoModalidad' => $this->codigoModalidad,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoTipoPuntoVenta' => $puntoVenta->codigoTipoPuntoVenta,
                'cuis' => $cuis,
                'descripcion' => $puntoVenta->descripcion,
                'nit' => $this->nit,
                'nombrePuntoVenta' => $puntoVenta->nombrePuntoVenta
            ],
        ]);
        return $response->RespuestaRegistroPuntoVenta;
    }
}
