<?php

namespace Enors\Siat;

use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    public function testSolicitarCUIS()
    {
        $siat = new Siat('', 0);
        $cuis = $siat->solicitarCUIS();
        $this->assertEquals('', $cuis);
    }

    public function testSolicitarCUFD()
    {
        $siat = new Siat('', 0);
        $cuis = $siat->solicitarCUIS();
        $cufd = $siat->solicitarCUFD($cuis);
        $this->assertEquals('', $cufd);
    }

    public function testGenerarCUF()
    {
        $siat = new Siat('', 0);
        $cuf = $siat->generarCUF();
        $this->assertEquals('', $cuf);
    }
}
