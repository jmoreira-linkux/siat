<?php

namespace Enors\Siat\Facturas\Builders;

interface FacturaBuilder
{
    public function emisionOnline();
    public function emisionOffline();
    public function emisionMasiva();
    
    public function modalidadComputarizada();
    public function modalidadElectronicaEnLinea();

    public function conDerechoCreditoFiscal();
    public function sinDerechoCreditoFiscal();
    public function documentoDeAjuste();
}
