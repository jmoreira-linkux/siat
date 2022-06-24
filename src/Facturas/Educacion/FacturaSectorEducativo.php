<?php

namespace Enors\Siat\Facturas\Educacion;

use Enors\Siat\Facturas\AbstractFactura;
use Enors\Siat\SiatConstants;

class FacturaSectorEducativo extends AbstractFactura
{
    public $periodoFacturado = '';
    public $nombreEstudiante = '';
    
    public function getCodigoDocumentoSector(): int
    {
        return SiatConstants::DOCUMENTO_SECTOR_FACTURA_EDUCACION;
    }
}
