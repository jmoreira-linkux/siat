<?php

namespace Enors\Siat\Facturas\CompraVenta;

use Enors\Siat\Facturas\AbstractPaquete;
use Enors\Siat\SiatConstants;

class PaqueteFacturaCompraVenta extends AbstractPaquete
{
    public function getCodigoDocumentoSector(): int
    {
        return SiatConstants::DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA;
    }
}
