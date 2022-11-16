<?php

namespace Abouet\CronBundle\Validator;

use Cron\Validator\ValidatorInterface;
use Abouet\CronBundle\Exception\IllegalValueException;

class RangeValidator implements ValidatorInterface {

    const PATTERN = '/^(\d)-(\d+)/';

    #[InheritDoc]
    public function validate($pattern) {
        $valid = preg_match(self::PATTERN, $pattern, $matches);
        if (0 == $valid) {
            throw new InvalidPatternException(sprintf('Invalid range pattern "%s", expected format n-n', $pattern));
        }
        if (0 == $matches[2]) {
            throw new IllegalValueException('A range can NOT end with 0');
        }
        if ($matches[1] > $matches[2]) {
            throw new IllegalValueException(sprintf('the start value %s MUST be SMALLER than the end value %s', $matches[1], $matches[2]));
        }
    }

}
