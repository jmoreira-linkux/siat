<?php

namespace Enors\Siat;

use Enors\Siat\Contracts\Codigos;
use Enors\Siat\Responses\RespuestaCufd;
use Enors\Siat\Responses\RespuestaCuis;
use Enors\Siat\Responses\RespuestaVerificaNit;

class SiatCodigos extends AbstractSiat implements Codigos
{
    const SIAT_CODIGOS_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl';

    protected function getWSDL(): string
    {
        return self::SIAT_CODIGOS_WSDL;
    }

    /**
     * Solicitar Código Único de Facturación Diaria. Este código habilita el sistema del Sujeto Pasivo para la emisión
     * de Facturas Digitales durante un periodo de vigencia de 24 horas.
     *
     * @param string $cuis
     * @return RespuestaCufd
     */
    public function solicitarCUFD(string $cuis): RespuestaCufd
    {
        $response = $this->client->cufd([
            'SolicitudCufd' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'cuis' => htmlspecialchars($cuis, ENT_XML1),
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ]
        ]);
        return new RespuestaCufd($response->RespuestaCufd);
    }

    /**
     * Solicitar Código Único de Inicio de Sistemas para una sucursal o punto de venta.
     *
     * @return RespuestaCuis
     */
    public function solicitarCUIS(): RespuestaCuis
    {
        $response = $this->client->cuis([
            'SolicitudCuis' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);

        return new RespuestaCuis($response->RespuestaCuis);
    }

    /**
     * Verificación de Nit.
     *
     * @return RespuestaVerificaNit
     */
    public function verificarNit(string $cuis, string $nit): RespuestaVerificaNit
    {
        $response = $this->client->verificarNit([
            'SolicitudVerificarNit' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => htmlspecialchars($this->codigoSistema, ENT_XML1),
                'nit' => $this->nit,
                'codigoModalidad' => $this->codigoModalidad,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
                'cuis' => htmlspecialchars($cuis, ENT_XML1),
                'nitParaVerificacion' => $nit,
            ],
        ]);

        return new RespuestaVerificaNit($response->RespuestaVerificarNit);
    }
}
