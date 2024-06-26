<?php

namespace Enors\Siat;

use KingsonDe\Marshal\AbstractXmlMapper;
use KingsonDe\Marshal\MarshalXml;

use Enors\Siat\Contracts\Facturable;
use Enors\Siat\Contracts\Facturacion;
use Enors\Siat\Contracts\PaqueteFacturable;
use Enors\Siat\Responses\RespuestaServicioFacturacion;

abstract class AbstractFacturacion extends AbstractSiat implements Facturacion
{
    abstract protected function getFacturaMapper(): AbstractXmlMapper;

    protected function serializFacturaToXML(Facturable $factura): string
    {
        $xml = MarshalXml::serializeItem($this->getFacturaMapper(), $factura);
        return $xml;
    }

    protected function compressXMLFactura(string $xml): string
    {
        return gzencode($xml);
    }

    protected function firmarFactura(string $xml): string
    {
        return hash('sha256', $xml);
    }

    public function recepcionFactura(Facturable $factura): RespuestaServicioFacturacion
    {
        $archivo = $this->serializFacturaToXML($factura);
        $response = $this->client->recepcionFactura([
            'SolicitudServicioRecepcionFactura' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $factura->getNitEmisor(),
                'codigoDocumentoSector' => $factura->getCodigoDocumentoSector(),
                'codigoEmision' => $factura->getCodigoEmision(),
                'codigoModalidad' => $factura->getCodigoModalidad(),
                'cufd' => htmlspecialchars($factura->getCufd(), ENT_XML1),
                'cuis' => htmlspecialchars($factura->getCuis(), ENT_XML1),
                'tipoFacturaDocumento' => $factura->getTipoFacturaDocumento(),
                'archivo' => $this->compressXMLFactura($archivo),
                'fechaEnvio' => date(SiatConstants::DATE_TIME_FORMAT, time()),
                'hashArchivo' => $this->firmarFactura($archivo),
            ]
        ]);
        return new RespuestaServicioFacturacion($response->RespuestaServicioFacturacion);
    }

    protected function getDirPath()
    {
        return sys_get_temp_dir();
    }

    protected function serializePaqueteFactura(PaqueteFacturable $paqueteFactura)
    {
        $file = tempnam($this->getDirPath(), 'inv-') . '.tar';
        $tar = new \PharData($file);
        $facturas = $paqueteFactura->getFacturas();
        foreach ($facturas as $index => $factura) {
            $xml = $this->serializFacturaToXML($factura);
            $tar->addFromString('fac-' . $index . '.xml', $xml);
        }
        $tar->compress(\Phar::GZ);
        // TODO: should we close the tar file?
        return file_get_contents($file . '.gz');
    }

    public function recepcionPaqueteFactura(
        PaqueteFacturable $paqueteFactura,
        int $codigoEvento
    ): RespuestaServicioFacturacion {
        $archivo = $this->serializePaqueteFactura($paqueteFactura);
        $response = $this->client->recepcionPaqueteFactura([
            'SolicitudServicioRecepcionPaquete' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $paqueteFactura->getNitEmisor(),
                'codigoDocumentoSector' => $paqueteFactura->getCodigoDocumentoSector(),
                'codigoEmision' => $paqueteFactura->getCodigoEmision(),
                'codigoModalidad' => $paqueteFactura->getCodigoModalidad(),
                'cufd' => htmlspecialchars($paqueteFactura->getCufd(), ENT_XML1),
                'cuis' => htmlspecialchars($paqueteFactura->getCuis(), ENT_XML1),
                'tipoFacturaDocumento' => $paqueteFactura->getTipoFacturaDocumento(),
                'archivo' => $archivo,
                'fechaEnvio' => date(SiatConstants::DATE_TIME_FORMAT, time()),
                'hashArchivo' => $this->firmarFactura($archivo),
                'cafc' => htmlspecialchars($paqueteFactura->getCafc(), ENT_XML1),
                'cantidadFacturas' => count($paqueteFactura->getFacturas()),
                'codigoEvento' => $codigoEvento,
            ]
        ]);
        return new RespuestaServicioFacturacion($response->RespuestaServicioFacturacion);
    }

    public function validacionRecepcionPaqueteFactura(PaqueteFacturable $paqueteFactura): RespuestaServicioFacturacion
    {
        $response = $this->client->validacionRecepcionPaqueteFactura([
            'SolicitudServicioValidacionRecepcionPaquete' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $paqueteFactura->getNitEmisor(),
                'codigoDocumentoSector' => $paqueteFactura->getCodigoDocumentoSector(),
                'codigoEmision' => $paqueteFactura->getCodigoEmision(),
                'codigoModalidad' => $paqueteFactura->getCodigoModalidad(),
                'cufd' => htmlspecialchars($paqueteFactura->getCufd(), ENT_XML1),
                'cuis' => htmlspecialchars($paqueteFactura->getCuis(), ENT_XML1),
                'tipoFacturaDocumento' => $paqueteFactura->getTipoFacturaDocumento(),
                'codigoRecepcion' => htmlspecialchars($paqueteFactura->getCodigoRecepcion(), ENT_XML1),
            ]
        ]);
        return new RespuestaServicioFacturacion($response->RespuestaServicioFacturacion);
    }

    public function anulacionFactura(Facturable $factura, int $codigoMotivo): RespuestaServicioFacturacion
    {
        $response = $this->client->anulacionFactura([
            'SolicitudServicioAnulacionFactura' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $factura->getNitEmisor(),
                'codigoDocumentoSector' => $factura->getCodigoDocumentoSector(),
                'codigoEmision' => $factura->getCodigoEmision(),
                'codigoModalidad' => $factura->getCodigoModalidad(),
                'cufd' => htmlspecialchars($factura->getCufd(), ENT_XML1),
                'cuis' => htmlspecialchars($factura->getCuis(), ENT_XML1),
                'tipoFacturaDocumento' => $factura->getTipoFacturaDocumento(),
                'codigoMotivo' => $codigoMotivo,
                'cuf' => htmlspecialchars($factura->getCuf(), ENT_XML1),
            ]
        ]);
        return new RespuestaServicioFacturacion($response->RespuestaServicioFacturacion);
    }

    public function reversionAnulacionFactura(Facturable $factura): RespuestaServicioFacturacion
    {
        $response = $this->client->reversionAnulacionFactura([
            'SolicitudServicioReversionAnulacionFactura' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'nit' => $factura->getNitEmisor(),
                'codigoDocumentoSector' => $factura->getCodigoDocumentoSector(),
                'codigoEmision' => $factura->getCodigoEmision(),
                'codigoModalidad' => $factura->getCodigoModalidad(),
                'cufd' => htmlspecialchars($factura->getCufd(), ENT_XML1),
                'cuis' => htmlspecialchars($factura->getCuis(), ENT_XML1),
                'tipoFacturaDocumento' => $factura->getTipoFacturaDocumento(),
                'cuf' => htmlspecialchars($factura->getCuf(), ENT_XML1),
            ]
        ]);
        return new RespuestaServicioFacturacion($response->RespuestaServicioFacturacion);
    }
}
