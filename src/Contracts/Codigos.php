<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\Responses\RespuestaCufd;
use Enors\Siat\Responses\RespuestaCuis;
use Enors\Siat\Responses\RespuestaVerificaNit;

interface Codigos
{
    public function solicitarCUFD(string $cuis): RespuestaCufd;
    public function solicitarCUIS(): RespuestaCuis;
    public function verificarNit(string $cuis, string $nit): RespuestaVerificaNit;
}
