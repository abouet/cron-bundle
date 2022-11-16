<?php

namespace Abouet\CronBundle\Validator;

use Cron\Validator\ValidatorInterface;
use Abouet\CronBundle\Exception\IllegalValueException;

class FrequencyValidator implements ValidatorInterface {

    const PATTERN = '/\/([1-9]+)$/';

    #[InheritDoc]
    public function validate($pattern) {
        $valid = preg_match(self::PATTERN, $pattern, $matches);
        if (0 == $valid) {
            throw new InvalidPatternException(sprintf('Invalid frequency pattern "%s", expected format /n', $pattern));
        }
        if (0 == $matches[1]) {
            throw new IllegalValueException('A frequency MUST be greater than 0');
        }
    }

}
