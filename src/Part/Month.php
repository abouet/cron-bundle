<?php

namespace Abouet\CronBundle\Part;

final class Month extends AbstractPart {

    const FORMAT = 'j';
    const PATTERN = '^(@monthly)|((\*|(?:[1-9]|1[012]|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(?:(?:\-(?:[1-9]|1[012]|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))?|(?:\,(?:[1-9]|1[012]|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*))';
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
