<?php

namespace Enors\Siat;

use PHPUnit\Framework\TestCase;

class SiatTest extends TestCase
{
    public function testGenerateCUF()
    {
        $siat = new Siat();
        $cuf = $siat->generarCUF();
        $this->assertEquals('', $cuf);
    }
}
