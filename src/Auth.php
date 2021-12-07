<?php

namespace Enors\Siat;

use \SoapClient;

class Auth
{
    const SIAT_AUTENTICACION_WSDL = 'https://pilotosiatservicios.impuestos.gob.bo/v1/ServicioAutenticacionSoap?wsdl';

    public function __construct(int $nit, \stdClass $credentials)
    {
        $this->nit = $nit;
        $this->credentials = $credentials;
        $this->client = new SoapClient(self::SIAT_AUTENTICACION_WSDL);
    }

    public function getAccessToken()
    {
        $response = $this->client->token([
            'DatosUsuarioRequest' => [
                'nit' => $this->nit,
                'login' => $this->credentials->username,
                'password' => $this->credentials->password,
            ],
        ]);
        if ($response->UsuarioAutenticadoDto->ok) {
            return $response->UsuarioAutenticadoDto->token;
        }

        return '';
    }
}
