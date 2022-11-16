<?php

namespace Abouet\CronBundle\Validator;

use Cron\Validator\ValidatorInterface;

class AbstratPartValidator implements ValidatorInterface {

    #[InheritDoc]
    public function validate($pattern) {
        if (null === $pattern) {
            return;
        }
        $valid = preg_match(static::PATTERN, $pattern);
        if (1 == $valid) {
            return true;
        }
        throw new InvalidPatternException(sprintf('Invalid %s pattern "%s".', static::NAME, $pattern));
    }

}
