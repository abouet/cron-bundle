<?php

namespace Abouet\CronBundle\Part;

use Abouet\CronBundle\Validator\DayOfWeekValidator;

class DayOfWeek extends AbstractPart {

    const FORMAT = 'N';
    const INTERVAL_PATTERN = 'P%dD';

    public function __construct($pattern) {
        parent::__construct($pattern, 0, 6, new DayOfWeekValidator());
    }

    protected function sanitize($val) {
        if (is_string($val)) {
            $val = strtoupper($val);
        }
        return parent::sanitize($val);
    }

}
