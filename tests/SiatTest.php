<?php

namespace Enors\Siat;

use PHPUnit\Framework\TestCase;
use \DateTime;

class SiatTest extends TestCase
{
    private $siat;

    protected function setUp(): void
    {
        $this->siat = new Siat('6D305EED6572A49A1D74437', '346141028');
    }

    public function testGenerarToken()
    {
        $user = $this->siat->generarToken('99999999', '');
        $this->assertEquals(false, $user->UsuarioAutenticadoDto->ok);
    }

    public function testSolicitarCUIS()
    {
        $cuis = $this->siat->solicitarCUIS();
        $this->assertEquals('', $cuis);
    }

    public function testSolicitarCUFD()
    {
        $cuis = $this->siat->solicitarCUIS();
        $cufd = $this->siat->solicitarCUFD($cuis);
        $this->assertEquals('', $cufd);
    }

    public function testGenerarCUF()
    {
        $nit = 123456789;
        $siat = new Siat('', $nit, Siat::MODALIDAD_ELECTRONICA_EN_LINEA);
        $timestamp = strtotime('2019-01-13 16:37:21');
        $nroFactura = 1;
        $cufd = 'A19E23EF34124CD';
        $cuf = $siat->generarCUF($timestamp, $nroFactura, $cufd);
        $this->assertEquals('8727F63A15F8976591F1ED18C702AE5A95B9E06A0A19E23EF34124CD', $cuf);
    }
}
