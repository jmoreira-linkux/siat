<?php

namespace Enors\Siat;

use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    public function testSolicitarCUIS()
    {
        $siat = new Siat();
        $cuis = $siat->solicitarCUIS();
        $this->assertEquals('', $cuis);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat();
        $cufd = $siat->solicitarCUFD();
        $this->assertEquals('', $cufd);
    }

    public function testGenerarCUF()
    {
        $siat = new Siat();
        $cuf = $siat->generarCUF();
        $this->assertEquals('', $cuf);
    }
}
