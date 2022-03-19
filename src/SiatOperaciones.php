<?php

namespace Enors\Siat;

use Enors\Siat\EventoSignificativo;
use Enors\Siat\PuntoVenta;
use Enors\Siat\Contracts\Operaciones;
use Enors\Siat\Responses\RespuestaCierrePuntoVenta;
use Enors\Siat\Responses\RespuestaCierreSistemas;
use Enors\Siat\Responses\RespuestaConsultaPuntoVenta;
use Enors\Siat\Responses\RespuestaListaEventos;
use Enors\Siat\Responses\RespuestaRegistroPuntoVenta;

class SiatOperaciones extends AbstractSiat implements Operaciones
{
    const SIAT_OPERACIONES_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl';

    protected function getWSDL(): string
    {
        return self::SIAT_OPERACIONES_WSDL;
    }

    public function consultaEventoSignificativo(string $cuis, string $cufd, int $timestamp): RespuestaListaEventos
    {
        $response = $this->client->consultaEventoSignificativo([
            'SolicitudConsultaEvento' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'cufd' => $cufd,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'fechaEvento' => date(SiatConstants::DATE_FORMAT, $timestamp)
            ]
        ]);
        return new RespuestaListaEventos($response->RespuestaListaEventos);
    }

    public function registroEventoSignificativo(
        string $cuis,
        string $cufd,
        EventoSignificativo $eventoSignificativo
    ): RespuestaListaEventos {
        $response = $this->client->registroEventoSignificativo([
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
                'fechaHoraInicioEvento' =>
                    date(SiatConstants::DATE_TIME_FORMAT, $eventoSignificativo->fechaInicioEvento),
                'fechaHoraFinEvento' => date(SiatConstants::DATE_TIME_FORMAT, $eventoSignificativo->fechaFinEvento),
                'cufdEvento' => $cufd
            ],
        ]);
        return new RespuestaListaEventos($response->RespuestaListaEventos);
    }

    public function registroPuntoVenta(string $cuis, PuntoVenta $puntoVenta): RespuestaRegistroPuntoVenta
    {
        $response = $this->client->registroPuntoVenta([
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
        return new RespuestaRegistroPuntoVenta($response->RespuestaRegistroPuntoVenta);
    }

    public function consultaPuntoVenta(string $cuis): RespuestaConsultaPuntoVenta
    {
        $response = $this->client->consultaPuntoVenta([
            'SolicitudConsultaPuntoVenta' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'cuis' => $cuis,
                'nit' => $this->nit,
            ],
        ]);
        return new RespuestaConsultaPuntoVenta($response->RespuestaConsultaPuntoVenta);
    }

    public function cierrePuntoVenta(string $cuis): RespuestaCierrePuntoVenta
    {
        $response = $this->client->CierrePuntoVenta([
            'SolicitudCierrePuntoVenta' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'cuis' => $cuis,
                'nit' => $this->nit,
            ],
        ]);
        return new RespuestaCierrePuntoVenta($response->RespuestaCierrePuntoVenta);
    }

    public function cierreOperacionesSistema(string $cuis): RespuestaCierreSistemas
    {
        $response = $this->client->cierreOperacionesSistema([
            'SolicitudOperaciones' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return new RespuestaCierreSistemas($response->RespuestaCierreSistemas);
    }
}
