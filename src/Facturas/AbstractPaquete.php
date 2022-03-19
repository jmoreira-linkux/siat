<?php

namespace Enors\Siat\Facturas;

use Enors\Siat\Contracts\PaqueteFacturable;

abstract class AbstractPaquete implements PaqueteFacturable
{
    public $codigoEmision = 0;
    public $codigoModalidad = 0;
    public $codigoRecepcion = null;
    public $cufd = '';
    public $cuis = '';
    public $nitEmisor = 0;
    public $facturas = [];
    public $tipoFacturaDocumento = 0;

    public function __construct(
        array $facturas,
        string $codigoRecepcion = null
    ) {
        $this->facturas = $facturas;
        $this->codigoRecepcion = $codigoRecepcion;
    }

    abstract public function getCodigoDocumentoSector(): int;

    public function getCodigoEmision(): int
    {
        return $this->codigoEmision;
    }

    public function getCodigoModalidad(): int
    {
        return $this->codigoModalidad;
    }

    public function getCodigoRecepcion(): string
    {
        return $this->codigoRecepcion;
    }

    public function getCuf(): string
    {
        return '';
    }

    public function getCufd(): string
    {
        return $this->cufd;
    }

    public function getCuis(): string
    {
        return $this->cuis;
    }

    public function getFacturas(): array
    {
        return $this->facturas;
    }

    public function getNitEmisor(): int
    {
        return $this->nitEmisor;
    }

    public function getTipoFacturaDocumento(): int
    {
        return $this->tipoFacturaDocumento;
    }
}
