<?php

namespace Abouet\CronBundle\Validator;

class HourValidator extends AbstratPartValidator {

    const PATTERN = '^(@hourly)$|(\*|(?:[0-9]|1[0-9]|2[0-3])(?:(?:\-(?:[0-9]|1[0-9]|2[0-3]))?|(?:\,(?:[0-9]|1[0-9]|2[0-3]))*))';
    Const NAME = 'hour';

}
