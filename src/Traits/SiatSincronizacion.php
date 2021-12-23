<?php

namespace Enors\Siat\Traits;

trait SiatSincronizacion
{
    private function getSincronizacionClient()
    {
        if (!isset($this->sincronizacionClient)) {
            $opts = [
                'http' => [
                    'header' => 'apiKey: TokenApi ' . $this->token
                ]
            ];
            $context = stream_context_create($opts);
            $this->sincronizacionClient = new \SoapClient(self::SIAT_SINCRONIZACION_WSDL, [
                'stream_context' => $context
            ]);
        }
        return $this->sincronizacionClient;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * actividades.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoCaeb] => string, [descripcion] => string, [tipoActividad] => string)
     */
    public function sincronizarActividades(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarActividades([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaActividades->listaActividades;
    }

    /**
     * Este proceso tiene como objetivo sincronizar la fecha y hora del Sistema de Facturación del Contribuyente
     * con los datos proporcionadas por el SIN.
     *
     * @param string $cuis
     * @return string Format 2021-12-04T00:19:57.987
     */
    public function sincronizarFechaHora(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarFechaHora([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaFechaHora->fechaHora;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * países que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaPaisOrigen(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaPaisOrigen([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * unidades de medida que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaUnidadMedida(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaUnidadMedida([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización del catálogo de
     * actividades documento sector.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoActividad] => string,
     * [codigoDocumentoSector] => int, [tipoDocumentoSector] => string)
     */
    public function sincronizarActividadesDocumentoSector(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarListaActividadesDocumentoSector([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaActividadesDocumentoSector->listaActividadesDocumentoSector;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la
     * sincronización del catálogo de leyendas que deben incluirse en las Facturas de
     * manera aleatoria de acuerdo a su actividad económica.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoActividad] => string, [descripcionLeyenda] => string)
     */
    public function sincronizarListaLeyendasFactura(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarListaLeyendasFactura([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricasLeyendas->listaLeyendas;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la
     * sincronización del catálogo de mensajes servicios.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarListaMensajesServicios(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarListaMensajesServicios([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la
     * sincronización del catálogo de eventos significativos para el registro de eventos
     * que ocurren en el Sistema Informático de Facturación.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaEventosSignificativos(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaEventosSignificativos([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la
     * sincronización del catálogo de motivos de anulación que deben incluirse en la
     * solicitud de anulación de las Facturas.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaMotivoAnulacion(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaMotivoAnulacion([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización
     * del catálogo de documentos identidad que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoDocumentoIdentidad(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoDocumentoIdentidad([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización
     * del catálogo de tipo documento sector.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoDocumentoSector(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoDocumentoSector([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización
     * del catálogo de tipo emision.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoEmision(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoEmision([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la sincronización
     * del catálogo de métodos de pago que se incluyen en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoMetodoPago(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoMetodoPago([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Método por el cual el Sistema Informático de Facturación autorizado realiza la
     * sincronización del catálogo de monedas que deben incluirse en las Facturas correspondientes.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoMoneda(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoMoneda([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     * Conforme a normativa vigente, la sincronización de catálogos de productos y servicios
     * debe realizarse a través de los Servicios Web disponibles para tal efecto.
     * Este proceso, permite la descarga del catálogo utilizado en el Servicio de
     * Impuestos Nacionales en función a la actividad económica, de tal manera que, con el
     * universo de códigos proporcionados al Sujeto Pasivo, este pueda homologar sus productos
     * con los de la Administración Tributaria.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoActividad] => string,
     * [codigoProducto] => int, [descripcionProducto] => string)
     */
    public function sincronizarParametricaListaProductosServicios(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarListaProductosServicios([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaProductos->listaCodigos;
    }

    /**
     * Conforme a normativa vigente, la sincronización de catálogos de productos y servicios
     * debe realizarse a través de los Servicios Web disponibles para tal efecto.
     * Este proceso, permite la descarga del catálogo utilizado en el Servicio de
     * Impuestos Nacionales en función a la actividad económica, de tal manera que, con el
     * universo de códigos proporcionados al Sujeto Pasivo, este pueda homologar sus productos
     * con los de la Administración Tributaria.
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoHabitacion(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoHabitacion([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTipoPuntoVenta(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTipoPuntoVenta([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }

    /**
     *
     * @param string $cuis
     * @return array<mixed> stdClass([codigoClasificador] => int, [descripcion] => string)
     */
    public function sincronizarParametricaTiposFactura(string $cuis)
    {
        $client = $this->getSincronizacionClient();
        $response = $client->sincronizarParametricaTiposFactura([
            'SolicitudSincronizacion' => [
                'codigoAmbiente' => $this->codigoAmbiente,
                'codigoSistema' => $this->codigoSistema,
                'nit' => $this->nit,
                'cuis' => $cuis,
                'codigoSucursal' => $this->codigoSucursal,
                'codigoPuntoVenta' => $this->codigoPuntoVenta,
            ],
        ]);
        return $response->RespuestaListaParametricas->listaCodigos;
    }
}
