<?php

namespace Abouet\CronBundle\Validator;

class YearValidator extends AbstratPartValidator {

    const PATTERN = '^(@yearly)|^(@annually)|(\*|(?:[1-9]|(?:[12][0-9])|3[01])(?:(?:\-(?:[1-9]|(?:[12][0-9])|3[01]))?|(?:\,(?:[1-9]|(?:[12][0-9])|3[01]))*))';
    Const NAME = 'year';

}
