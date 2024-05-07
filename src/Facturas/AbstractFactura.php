<?php

namespace Enors\Siat\Facturas;

use Enors\Siat\Contracts\Facturable;
use Enors\Siat\Utils\Base16;
use Enors\Siat\Utils\Module11;

abstract class AbstractFactura implements Facturable
{
    public $nitEmisor = 0;
    public $razonSocialEmisor = '';
    public $municipio = '';
    public $telefono = null;
    public $numeroFactura = '';
    public $cufd = '';
    public $cuis = '';
    public $codigoControl = '';
    public $codigoEmision = 0;
    public $codigoModalidad = 0;
    public $codigoSucursal = 0;
    public $direccion = '';
    public $codigoPuntoVenta = 0;
    public $fechaEmision = 0;
    public $nombreRazonSocial = null;
    public $codigoTipoDocumentoIdentidad = '';
    public $numeroDocumento = '';
    public $complemento = null;
    public $codigoCliente = '';
    public $codigoMetodoPago = '';
    public $numeroTarjeta = null;
    public $montoTotal = '';
    public $montoTotalSujetoIva = '';
    public $codigoMoneda = '';
    public $tipoCambio = '';
    public $montoTotalMoneda = '';
    public $montoGiftCard = null;
    public $descuentoAdicional = null;
    public $codigoExcepcion = null;
    public $cafc = null;
    public $leyenda = '';
    public $usuario = '';
    public $detalle = [];
    public $tipoFacturaDocumento = 0;

    abstract public function getCodigoDocumentoSector(): int;

    public function getCodigoEmision(): int
    {
        return $this->codigoEmision;
    }

    public function getCodigoModalidad(): int
    {
        return $this->codigoModalidad;
    }

    public function getCufd(): string
    {
        return $this->cufd;
    }

    public function getCuis(): string
    {
        return $this->cuis;
    }

    public function getCafc(): string
    {
        return $this->cafc;
    }

    public function getNitEmisor(): int
    {
        return $this->nitEmisor;
    }

    public function getTipoFacturaDocumento(): int
    {
        return $this->tipoFacturaDocumento;
    }

    public function getCuf(): string
    {
        $fechaHora = date('YmdHisv', $this->fechaEmision);
        $nitPad = str_pad($this->nitEmisor, 13, '0', STR_PAD_LEFT);
        $fechaHoraPad = str_pad($fechaHora, 17, '0', STR_PAD_LEFT);
        $codigoSucursalPad = str_pad($this->codigoSucursal, 4, '0', STR_PAD_LEFT);
        $tipoDocumentoSectorPad = str_pad($this->getCodigoDocumentoSector(), 2, '0', STR_PAD_LEFT);
        $nroFacturaPad = str_pad($this->numeroFactura, 10, '0', STR_PAD_LEFT);
        $codigoPuntoVentaPad = str_pad($this->codigoPuntoVenta, 4, '0', STR_PAD_LEFT);

        $concatenated = $nitPad
            . $fechaHoraPad
            . $codigoSucursalPad
            . $this->getCodigoModalidad()
            . $this->getCodigoEmision()
            . $this->getTipoFacturaDocumento()
            . $tipoDocumentoSectorPad
            . $nroFacturaPad
            . $codigoPuntoVentaPad;
        $modulo11 = Module11::generate($concatenated, 1, 9, false);
        $concatenated .= $modulo11;

        return Base16::encode($concatenated) . $this->codigoControl;
    }

    public function agregarLinea(AbstractFacturaDetalle $linea)
    {
        array_push($this->detalle, $linea);
    }
}
