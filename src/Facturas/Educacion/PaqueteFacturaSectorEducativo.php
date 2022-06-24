<?php

namespace Enors\Siat\Facturas\Educacion;

use Enors\Siat\Facturas\AbstractPaquete;
use Enors\Siat\SiatConstants;

class PaqueteFacturaSectorEducativo extends AbstractPaquete
{
    public function getCodigoDocumentoSector(): int
    {
        return SiatConstants::DOCUMENTO_SECTOR_FACTURA_EDUCACION;
    }
}
