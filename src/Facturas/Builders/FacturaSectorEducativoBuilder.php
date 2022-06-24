<?php

namespace Enors\Siat\Facturas\Builders;

use Enors\Siat\Facturas\Educacion\FacturaSectorEducativo;
use Enors\Siat\SiatConstants;

class FacturaSectorEducativoBuilder implements FacturaBuilder
{
    private FacturaSectorEducativo $factura;

    public function createFactura()
    {
        $this->factura = new FacturaSectorEducativo();
    }

    public function emisionOnline()
    {
        $this->factura->codigoEmision = SiatConstants::EMISION_ONLINE;
    }
    public function emisionOffline()
    {
        $this->factura->codigoEmision = SiatConstants::EMISION_OFFLINE;
    }
    public function emisionMasiva()
    {
        $this->factura->codigoEmision = SiatConstants::EMISION_MASIVA;
    }

    public function modalidadComputarizada()
    {
        $this->factura->codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA;
    }

    public function modalidadElectronicaEnLinea()
    {
        $this->factura->codigoModalidad = SiatConstants::MODALIDAD_ELECTRONICA_EN_LINEA;
    }

    public function conDerechoCreditoFiscal()
    {
        $this->factura->tipoFacturaDocumento = SiatConstants::FACTURA_CON_DERECHO_CREDITO_FISCAL;
    }
    public function sinDerechoCreditoFiscal()
    {
        $this->factura->tipoFacturaDocumento = SiatConstants::FACTURA_SIN_DERECHO_CREDITO_FISCAL;
    }
    public function documentoDeAjuste()
    {
        $this->factura->tipoFacturaDocumento = SiatConstants::FACTURA_DOCUMENTO_DE_AJUSTE;
    }

    public function getFactura()
    {
        $clone = $this->factura;
        $this->createFactura();
        return $clone;
    }
}
