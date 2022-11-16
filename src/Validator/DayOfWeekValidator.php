<?php

namespace Abouet\CronBundle\Validator;

class DayOfWeekValidator extends AbstratPartValidator {

    const PATTERN = '^(@weekly)|(\*|(?:[0-6]|SUN|MON|TUE|WED|THU|FRI|SAT)(?:(?:\-(?:[0-6]|SUN|MON|TUE|WED|THU|FRI|SAT))?|(?:\,(?:[0-6]|SUN|MON|TUE|WED|THU|FRI|SAT))*))';
    Const NAME = 'day of week';

}
