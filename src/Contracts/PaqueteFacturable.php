<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\Contracts\Facturable;

interface PaqueteFacturable extends Facturable
{
    public function getCodigoRecepcion(): string;
    public function getFacturas(): array;
}
