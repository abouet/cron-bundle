<?php

namespace Abouet\CronBundle\Part;

final class Month extends AbstractPart {

    const FORMAT = 'j';
    const INTERVAL_PATTERN = 'P%dM';

    public function __construct($pattern) {
        parent::__construct($pattern, 1, 12);
    }

    protected function sanitize($val) {
        if (is_string($val)) {
            $val = strtoupper($val);
        }
        return parent::sanitize($val);
    }
}
