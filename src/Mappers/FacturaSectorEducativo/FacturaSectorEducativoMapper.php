<?php

namespace Enors\Siat\Mappers\FacturaSectorEducativo;

use Enors\Siat\Facturas\Educacion\FacturaSectorEducativo;
use Enors\Siat\Mappers\AbstractMapper;

class FacturaSectorEducativoMapper extends AbstractMapper
{
    public function map(FacturaSectorEducativo $factura)
    {
        return [
            'facturaComputarizadaSectorEducativo' => [
                $this->attributes() => [
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:noNamespaceSchemaLocation' => 'facturaComputarizadaSectorEducativo.xsd',
                ],
                'cabecera' => $this->item(new FacturaSectorEducativoCabeceraMapper(), $factura),
                $this->collection(new FacturaSectorEducativoDetalleMapper(), $factura->detalle),
            ]
        ];
    }
}
