<?php

namespace Abouet\CronBundle\Validator;

class MinuteValidator extends AbstratPartValidator {

    const PATTERN = '(\*|(?:[0-9]|(?:[1-5][0-9]))(?:(?:\-[0-9]|\-(?:[1-5][0-9]))?|(?:\,(?:[0-9]|(?:[1-5][0-9])))*))';
    Const NAME = 'minute';

}
