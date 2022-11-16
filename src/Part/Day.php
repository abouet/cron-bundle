<?php

namespace Abouet\CronBundle\Part;

use Abouet\CronBundle\Validator\DayValidator;

class Day extends AbstractPart {

    const FORMAT = 'j';
    const INTERVAL_PATTERN = 'P%dD';

    public function __construct($pattern) {
        parent::__construct($pattern, 0, 31, new DayValidator());
    }

}
