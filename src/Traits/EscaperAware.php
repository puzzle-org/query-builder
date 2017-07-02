<?php

declare(strict_types = 1);

namespace Puzzle\QueryBuilder\Traits;

use Puzzle\QueryBuilder\Escaper;

trait EscaperAware
{
    protected
        $escaper;

    public function setEscaper(Escaper $escaper): self
    {
        $this->escaper = $escaper;

        return $this;
    }
}
