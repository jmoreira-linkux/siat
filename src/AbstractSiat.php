<?php

namespace Enors\Siat;

abstract class AbstractSiat
{
    public function __construct(
        string $codigoSistema,
        int $nit,
        string $token,
        int $codigoPuntoVenta = 0,
        int $codigoSucursal = 0,
        int $codigoModalidad = SiatConstants::MODALIDAD_COMPUTARIZADA,
        int $codigoAmbiente = SiatConstants::AMBIENTE_PRUEBA_PILOTO
    ) {
        $this->codigoSistema = $codigoSistema;
        $this->nit = $nit;
        $this->token = $token;
        $this->codigoPuntoVenta = $codigoPuntoVenta;
        $this->codigoSucursal = $codigoSucursal;
        $this->codigoModalidad = $codigoModalidad;
        $this->codigoAmbiente = $codigoAmbiente;
        $this->initializeClient();
    }

    protected function initializeClient()
    {
        $opts = [
            'http' => [
                'header' => 'apiKey: TokenApi ' . $this->token
            ]
        ];
        $context = stream_context_create($opts);
        $this->client = new \SoapClient($this->getWSDL(), ['stream_context' => $context]);
    }

    abstract protected function getWSDL(): string;
}
