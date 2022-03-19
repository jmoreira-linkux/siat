<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\Responses\RespuestaCufd;
use Enors\Siat\Responses\RespuestaCuis;

interface Codigos
{
    public function solicitarCUFD(string $cuis): RespuestaCufd;
    public function solicitarCUIS(): RespuestaCuis;
}
