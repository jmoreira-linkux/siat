<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\EventoSignificativo;
use Enors\Siat\PuntoVenta;
use Enors\Siat\Responses\RespuestaCierrePuntoVenta;
use Enors\Siat\Responses\RespuestaCierreSistemas;
use Enors\Siat\Responses\RespuestaConsultaPuntoVenta;
use Enors\Siat\Responses\RespuestaListaEventos;
use Enors\Siat\Responses\RespuestaRegistroPuntoVenta;

interface Operaciones
{
    public function cierreOperacionesSistema(string $cuis): RespuestaCierreSistemas;
    public function cierrePuntoVenta(string $cuis): RespuestaCierrePuntoVenta;
    public function consultaEventoSignificativo(string $cuis, string $cufd, int $timestamp): RespuestaListaEventos;
    public function consultaPuntoVenta(string $cuis): RespuestaConsultaPuntoVenta;
    public function registroEventoSignificativo(
        string $cuis,
        string $cufd,
        EventoSignificativo $eventoSignificativo
    ): RespuestaListaEventos;
    public function registroPuntoVenta(string $cuis, PuntoVenta $puntoVenta): RespuestaRegistroPuntoVenta;
}
