<?php

namespace Abouet\CronBundle\Validator;

use Cron\Validator\ValidatorInterface;
use Abouet\CronBundle\Exception\IllegalValueException;

class ListValidator implements ValidatorInterface {

    const PATTERN = '/^([\d,]+)$/';

    #[InheritDoc]
    public function validate($pattern) {
        $valid = preg_match(self::PATTERN, $pattern, $matches);
        if (0 == $valid) {
            throw new InvalidPatternException(sprintf('Invalid list pattern "%s", expected format n,n[...]', $pattern));
        }
    }

}
