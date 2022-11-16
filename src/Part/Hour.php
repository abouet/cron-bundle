<?php

namespace Abouet\CronBundle\Part;

use Abouet\CronBundle\Validator\HourValidator;

class Hour extends AbstractPart {

    const FORMAT = 'G';
    const INTERVAL_PATTERN = 'PT%dH';

    public function __construct($pattern) {
        parent::__construct($pattern, 0, 23, new HourValidator());
    }

}
