<?php

namespace Enors\Siat\Mappers\FacturaSectorEducativo;

use Enors\Siat\Facturas\Educacion\FacturaSectorEducativo;
use Enors\Siat\Mappers\AbstractMapper;
use Enors\Siat\SiatConstants;

class FacturaSectorEducativoCabeceraMapper extends AbstractMapper
{
    public function map(FacturaSectorEducativo $factura)
    {
        return [
            'nitEmisor' => $factura->nitEmisor,
            'razonSocialEmisor' => $factura->razonSocialEmisor,
            'municipio' => $factura->municipio,
            'telefono' => isset($factura->telefono) ? $factura->telefono : $this->nil(),
            'numeroFactura' => $factura->numeroFactura,
            'cuf' => $factura->getCuf(),
            'cufd' => $factura->getCufd(),
            'codigoSucursal' => $factura->codigoSucursal,
            'direccion' => $factura->direccion,
            'codigoPuntoVenta' => isset($factura->codigoPuntoVenta) ? $factura->codigoPuntoVenta : $this->nil(),
            'fechaEmision' => date(SiatConstants::DATE_TIME_FORMAT, $factura->fechaEmision),
            'nombreRazonSocial' => isset($factura->nombreRazonSocial) ? $factura->nombreRazonSocial : $this->nil(),
            'codigoTipoDocumentoIdentidad' => $factura->codigoTipoDocumentoIdentidad,
            'numeroDocumento' => $factura->numeroDocumento,
            'complemento' => isset($factura->complemento) ? $factura->complemento : $this->nil(),
            'codigoCliente' => $factura->codigoCliente,
            'nombreEstudiante' => $factura->nombreEstudiante,
            'periodoFacturado' => $factura->periodoFacturado,
            'codigoMetodoPago' => $factura->codigoMetodoPago,
            'numeroTarjeta' => isset($factura->numeroTarjeta) ? $factura->numeroTarjeta : $this->nil(),
            'montoTotal' => $factura->montoTotal,
            'montoTotalSujetoIva' => $factura->montoTotalSujetoIva,
            'codigoMoneda' => $factura->codigoMoneda,
            'tipoCambio' => $factura->tipoCambio,
            'montoTotalMoneda' => $factura->montoTotalMoneda,
            'montoGiftCard' => isset($factura->montoGiftCard) ? $factura->montoGiftCard : $this->nil(),
            'descuentoAdicional' => isset($factura->descuentoAdicional) ? $factura->descuentoAdicional : $this->nil(),
            'codigoExcepcion' => isset($factura->codigoExcepcion) ? $factura->codigoExcepcion : $this->nil(),
            'cafc' => isset($factura->cafc) ? $factura->cafc : $this->nil(),
            'leyenda' => $factura->leyenda,
            'usuario' => $factura->usuario,
            'codigoDocumentoSector' => $factura->getCodigoDocumentoSector(),
        ];
    }
}
