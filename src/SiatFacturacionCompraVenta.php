<?php

namespace Enors\Siat;

use KingsonDe\Marshal\AbstractXmlMapper;
use Enors\Siat\Mappers\FacturaCompraVenta\FacturaCompraVentaMapper;

class SiatFacturacionCompraVenta extends AbstractFacturacion
{
    const SIAT_FACTURACION_COMPRA_VENTA_WSDL =
        'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';

    protected function getWSDL(): string
    {
        return self::SIAT_FACTURACION_COMPRA_VENTA_WSDL;
    }

    protected function getFacturaMapper(): AbstractXmlMapper
    {
        return new FacturaCompraVentaMapper();
    }
}
