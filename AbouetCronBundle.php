<?php

namespace Abouet\CronBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AbouetCronBundle extends AbstractBundle {

    public function getPath(): string {
        return __DIR__;
    }

}
