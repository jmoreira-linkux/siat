<?php

namespace Enors\Siat;

use Enors\Siat\Traits\SiatCodigos;
use Enors\Siat\Traits\SiatFacturacionCompraVenta;
use Enors\Siat\Traits\SiatSincronizacion;
use Enors\Siat\Traits\SiatOperaciones;
use Enors\Siat\Utils\Base16;
use Enors\Siat\Utils\Module11;

/**
 * @deprecated
 */
class Siat
{
    use SiatCodigos;
    use SiatFacturacionCompraVenta;
    use SiatSincronizacion;
    use SiatOperaciones;

    const DATE_FORMAT = 'Y-m-d';
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.v';

    const AMBIENTE_PRODUCCION = 1;
    const AMBIENTE_PRUEBA_PILOTO = 2;

    const MODALIDAD_ELECTRONICA_EN_LINEA = 1;
    const MODALIDAD_COMPUTARIZADA = 2;

    const TIPO_EMISION_ONLINE = 1;
    const TIPO_EMISION_OFFLINE = 2;
    const TIPO_EMISION_MASIVA = 3;

    const TIPO_FACTURA_CON_DERECHO_CREDITO_FISCAL = 1;
    const TIPO_FACTURA_SIN_DERECHO_CREDITO_FISCAL = 2;
    const TIPO_FACTURA_DOCUMENTO_DE_AJUSTE = 3;

    const TIPO_DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA = 1;

    const SIAT_FACTURACION_COMPRA_VENTA_WSDL =
        'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
    const SIAT_CODIGOS_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl';
    const SIAT_SINCRONIZACION_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';
    const SIAT_OPERACIONES_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl';

    public function __construct(
        string $codigoSistema,
        int $nit,
        string $token,
        int $codigoPuntoVenta = 0,
        int $codigoSucursal = 0,
        int $codigoModalidad = self::MODALIDAD_COMPUTARIZADA,
        int $codigoAmbiente = self::AMBIENTE_PRUEBA_PILOTO
    ) {
        $this->codigoSistema = $codigoSistema;
        $this->nit = $nit;
        $this->token = $token;
        $this->codigoAmbiente = $codigoAmbiente;
        $this->codigoModalidad = $codigoModalidad;
        $this->codigoSucursal = $codigoSucursal;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
    }

    /**
     * @deprecated
     * @param  int         $timestamp           [description]
     * @param  int         $nroFactura          [description]
     * @param  string      $cufd                [description]
     * @param  int|integer $tipoEmision         [description]
     * @param  int|integer $tipoFactura         [description]
     * @param  int|integer $tipoDocumentoSector [description]
     * @return [type]                           [description]
     */
    public function generarCUF(
        int $timestamp,
        int $nroFactura,
        string $cufd,
        int $tipoEmision = 1,
        int $tipoFactura = 1,
        int $tipoDocumentoSector = 1
    ) {
        $fechaHora = date('YmdHisv', $timestamp);
        $nitPad = str_pad($this->nit, 13, '0', STR_PAD_LEFT);
        $fechaHoraPad = str_pad($fechaHora, 17, '0', STR_PAD_LEFT);
        $codigoSucursalPad = str_pad($this->codigoSucursal, 4, '0', STR_PAD_LEFT);
        $tipoDocumentoSectorPad = str_pad($tipoDocumentoSector, 2, '0', STR_PAD_LEFT);
        $nroFacturaPad = str_pad($nroFactura, 10, '0', STR_PAD_LEFT);
        $codigoPuntoVentaPad = str_pad($this->codigoPuntoVenta, 4, '0', STR_PAD_LEFT);

        $concatenated = $nitPad
            . $fechaHoraPad
            . $codigoSucursalPad
            . $this->codigoModalidad
            . $tipoEmision
            . $tipoFactura
            . $tipoDocumentoSectorPad
            . $nroFacturaPad
            . $codigoPuntoVentaPad;
        $modulo11 = Module11::generate($concatenated, 1, 9, false);
        $concatenated .= $modulo11;

        return Base16::encode($concatenated) . $cufd;
    }
}
