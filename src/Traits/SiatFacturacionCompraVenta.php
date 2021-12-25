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
        $archivo = gzencode($xml);

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

    public function recepcionPaqueteFactura(string $cuis, string $cufd, array $facturas)
    {
        $file = tempnam(sys_get_temp_dir(), 'inv-') . '.tar';
        $tar = new \PharData($file);
        foreach ($facturas as $index => $factura) {
            $xml = MarshalXml::serializeItem(new FacturaCompraVentaMapper(), $factura);
            $tar->addFromString('fac-' . $index . '.xml', $xml);
        }
        $compressed = $tar->compress(\Phar::GZ);
        $archivo = file_get_contents($file . '.gz');

        $client = $this->getFacturacionComputarizadaClient();
        $response = $client->recepcionPaqueteFactura([
            'SolicitudServicioRecepcionPaquete' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $this->nit,
                'codigoDocumentoSector' => self::TIPO_DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA,
                'codigoEmision' => self::TIPO_EMISION_OFFLINE,
                'codigoModalidad' => $this->codigoModalidad,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'tipoFacturaDocumento' => self::TIPO_FACTURA_CON_DERECHO_CREDITO_FISCAL,
                'archivo' => $archivo,
                'fechaEnvio' => date(self::DATE_TIME_FORMAT, time()),
                'hashArchivo' => hash('sha256', $archivo),
                'cafc' => '',
                'cantidadFacturas' => count($facturas),
                'codigoEvento' => '105481',
            ]
        ]);
        return $response->RespuestaServicioFacturacion;
    }

    public function validacionRecepcionPaqueteFactura(string $cuis, string $cufd, string $codigoRecepcion)
    {
        $client = $this->getFacturacionComputarizadaClient();
        $response = $client->validacionRecepcionPaqueteFactura([
            'SolicitudServicioValidacionRecepcionPaquete' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => $this->codigoSistema,
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $this->nit,
                'codigoDocumentoSector' => self::TIPO_DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA,
                'codigoEmision' => self::TIPO_EMISION_OFFLINE,
                'codigoModalidad' => $this->codigoModalidad,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'tipoFacturaDocumento' => self::TIPO_FACTURA_CON_DERECHO_CREDITO_FISCAL,
                'codigoRecepcion' => $codigoRecepcion,
            ]
        ]);
        return $response->RespuestaServicioFacturacion;
    }
}
