<?php

namespace Enors\Siat;

use Enors\Siat\Utils\Base16;
use Enors\Siat\Utils\Module11;

use \SoapClient;

class Siat
{
    const AMBIENTE_PRODUCCION = 1;
    const AMBIENTE_PRUEBA_PILOTO = 2;

    const MODALIDAD_ELECTRONICA_EN_LINEA = 1;
    const MODALIDAD_COMPUTARIZADA = 2;

    const SIAT_WSDL = ' https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';

    public function __construct(
        string $codigoSistema,
        int $nit,
        int $codigoAmbiente = 2,
        int $codigoModalidad = 2,
        int $codigoSucursal = 0,
        int $codigoPuntoVenta = 0
    ) {
        $this->codigoSistema = $codigoSistema;
        $this->nit = $nit;
        $this->codigoAmbiente = $codigoAmbiente;
        $this->codigoModalidad = $codigoModalidad;
        $this->codigoSucursal = $codigoSucursal;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
        // $this->client = new SoapClient(self::SIAT_WSDL);
    }

    public function solicitarCUIS()
    {
        return '';
    }

    public function solicitarCUFD(string $cuis)
    {
        return '';
    }

    public function generarCUF()
    {
        return '';
    }
}
