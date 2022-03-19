<?php

namespace Enors\Siat\Facturas\CompraVenta;

use Enors\Siat\Facturas\AbstractFactura;
use Enors\Siat\SiatConstants;

class FacturaCompraVenta extends AbstractFactura
{
    public function getCodigoDocumentoSector(): int
    {
        return SiatConstants::DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA;
    }
}
