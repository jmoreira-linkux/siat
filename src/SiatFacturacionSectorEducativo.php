<?php

namespace Enors\Siat;

use KingsonDe\Marshal\AbstractXmlMapper;
use Enors\Siat\Mappers\FacturaSectorEducativo\FacturaSectorEducativoMapper;

class SiatFacturacionSectorEducativo extends AbstractFacturacion
{
    const SIAT_FACTURACION_SECTOR_EDUCACION_WSDL = 
        'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';

    protected function getWSDL(): string
    {
        return self::SIAT_FACTURACION_SECTOR_EDUCACION_WSDL;
    }

    protected function getFacturaMapper(): AbstractXmlMapper
    {
        return new FacturaSectorEducativoMapper();
    }
}
