<?php

namespace Enors\Siat\Contracts;

use Enors\Siat\PaqueteFactura;
use Enors\Siat\Contracts\PaqueteFacturable;
use Enors\Siat\Facturas\AbstractFactura;
use Enors\Siat\Responses\RespuestaServicioFacturacion;

interface Facturacion
{
    public function anulacionFactura(AbstractFactura $factura, int $codigoMotivo): RespuestaServicioFacturacion;
    public function recepcionFactura(AbstractFactura $factura): RespuestaServicioFacturacion;
    public function recepcionPaqueteFactura(
        PaqueteFacturable $paqueteFactura,
        int $codigoEvento
    ): RespuestaServicioFacturacion;
    public function validacionRecepcionPaqueteFactura(PaqueteFacturable $paqueteFactura): RespuestaServicioFacturacion;
}
