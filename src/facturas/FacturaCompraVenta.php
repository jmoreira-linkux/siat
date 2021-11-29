<?php

namespace Enors\Siat\Facturas;

class FacturaCompraVenta
{
    public function __construct(
        int $nitEmisor,
        string $razonSocialEmisor,
        string $municipio
    ) {
        $this->nitEmisor = $nitEmisor;
        $this->razonSocialEmisor = $razonSocialEmisor;
        $this->municipio = $municipio;
    }
}
