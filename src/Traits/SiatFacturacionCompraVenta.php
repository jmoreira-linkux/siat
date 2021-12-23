<?php

namespace Enors\Siat\Traits;

use KingsonDe\Marshal\MarshalXml;

use Enors\Siat\Facturas\FacturaCompraVenta;
use Enors\Siat\Mappers\FacturaCompraVentaMapper;

trait SiatFacturacionCompraVenta
{
    private function getFacturacionComputarizadaClient()
    {
        if (!isset($this->facturacionComputarizadaClient)) {
            $opts = [
                'http' => [
                    'header' => 'apiKey: TokenApi ' . $this->token
                ]
            ];
            $context = stream_context_create($opts);
            $this->facturacionComputarizadaClient = new \SoapClient(self::SIAT_FACTURACION_COMPRA_VENTA_WSDL, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1,
                'exceptions' => false,
            ]);
        }
        return $this->facturacionComputarizadaClient;
    }

    public function recepcionFacturaCompraVenta(string $cuis, string $cufd, FacturaCompraVenta $factura)
    {
        $xml = MarshalXml::serializeItem(new FacturaCompraVentaMapper(), $factura);
        $archivo = gzencode($xml, 9);

        $client = $this->getFacturacionComputarizadaClient();
        $response = $client->recepcionFactura([
            'SolicitudServicioRecepcionFactura' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $this->nit,
                'codigoDocumentoSector' => $factura->codigoDocumentoSector,
                'codigoEmision' => $factura->codigoEmision,
                'codigoModalidad' => $this->codigoModalidad,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'tipoFacturaDocumento' => $factura->tipoFacturaDocumento,
                'archivo' => $archivo,
                'fechaEnvio' => date(self::DATE_TIME_FORMAT, time()),
                'hashArchivo' => hash('sha256', $archivo),
            ]
        ]);
        return $response;
    }
}
