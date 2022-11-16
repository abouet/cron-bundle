<?php

namespace Abouet\CronBundle\Validator;

use Cron\Exception\InvalidPatternException;
use Cron\Validator\ValidatorInterface;

final class CrontabValidator implements ValidatorInterface {

    private $parts;

    public function __construct() {
        $this->parts['minute'] = new MinuteValidator();
        $this->parts['hour'] = new HourValidator();
        $this->parts['day'] = new DayValidator();
        $this->parts['month'] = new MonthValidator();
        $this->parts['day of week'] = new DayOfWeekValidator();
        $this->parts['year'] = new YearValidator();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($pattern) {
        // Validate crontab shorthand ex : @monthly
        if ('@' == substr($pattern, 0, 1)) {
            try {
                $shorthand = Shorthand::try($pattern);
            } catch (\Throwable $error) {
                throw new InvalidPatternException(sprintf('Invalid shorthand "%s". Expects %s', $pattern, implode(',', Shorthand::toArray(), ',')));
            }
        }
        // Validate the crontab parts
        $parts = preg_split('/[\s\t]+/', $pattern);
        $i = 0;
        foreach ($this->parts as $name => $part) {
            $status = $part->validate($parts[$i]);
            if ($status === false) {
                throw new InvalidPatternException(sprintf('Invalid %s "%s".', $name, isset($parts[$i]) ? $parts[$i] : ''));
            }
            $i++;
        }
    }

}
