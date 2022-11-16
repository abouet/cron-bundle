<?php

namespace Abouet\CronBundle\Validator;

class DayValidator extends AbstratPartValidator {

    const PATTERN = '/^(@daily)$|(\*|(?:[1-9]|(?:[12][0-9])|3[01])(?:(?:\-(?:[1-9]|(?:[12][0-9])|3[01]))?|(?:\,(?:[1-9]|(?:[12][0-9])|3[01]))*))/';
    Const NAME = 'day';

}
