<?php

namespace Enors\Siat;

class SiatConstants
{
    const AMBIENTE_PRODUCCION = 1;
    const AMBIENTE_PRUEBA_PILOTO = 2;

    const DATE_FORMAT = 'Y-m-d';
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.v';

    const MODALIDAD_ELECTRONICA_EN_LINEA = 1;
    const MODALIDAD_COMPUTARIZADA = 2;

    const EMISION_ONLINE = 1;
    const EMISION_OFFLINE = 2;
    const EMISION_MASIVA = 3;

    const FACTURA_CON_DERECHO_CREDITO_FISCAL = 1;
    const FACTURA_SIN_DERECHO_CREDITO_FISCAL = 2;
    const FACTURA_DOCUMENTO_DE_AJUSTE = 3;

    const DOCUMENTO_SECTOR_FACTURA_COMPRA_VENTA = 1;
}
