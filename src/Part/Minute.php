<?php

namespace Abouet\CronBundle\Part;

use Abouet\CronBundle\Validator\MinuteValidator;

class Minute extends AbstractPart {

    const FORMAT = 'i';
    const INTERVAL_PATTERN = 'PT%dM';

    public function __construct($pattern) {
        parent::__construct($pattern, 0, 59, new MinuteValidator());
    }

}
