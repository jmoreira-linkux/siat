<?php

namespace Enors\Siat\Contracts;

interface Facturable
{
    public function getCodigoDocumentoSector(): int;
    public function getCodigoEmision(): int;
    public function getCodigoModalidad(): int;
    public function getCuf(): string;
    public function getCufd(): string;
    public function getCuis(): string;
    public function getCafc(): string;
    public function getNitEmisor(): int;
    public function getTipoFacturaDocumento(): int;
}
