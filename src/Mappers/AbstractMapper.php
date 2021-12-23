<?php

namespace Enors\Siat\Mappers;

use KingsonDe\Marshal\AbstractXmlMapper;

abstract class AbstractMapper extends AbstractXmlMapper
{
    public function nil()
    {
        return [$this->attributes() => ['xsi:nil' => true]];
    }
}
