<?php

namespace Abouet\CronBundle\Schedule;

use Cron\Schedule\ScheduleInterface;
use Cron\Validator\CrontabValidator;
use Abouet\CronBundle\Part\Day;
use Abouet\CronBundle\Part\DayOfWeek;
use Abouet\CronBundle\Part\Hour;
use Abouet\CronBundle\Part\Minute;
use Abouet\CronBundle\Part\Month;
use \DateTimeInterface;

final class CrontabSchedule implements ScheduleInterface, \Stringable {

    private $parts,
            $validator;

    public function __construct($pattern = null) {
        $this->parts['min'] = new Minute();
        $this->parts['hour'] = new Hour();
        $this->parts['day'] = new Day();
        $this->parts['month'] = new Month();
        $this->parts['dow'] = new DayOfWeek();
        $this->validator = new CrontabValidator();
        if ($pattern) {
            $this->setPattern($pattern);
        }
    }

    public function getMinute(): CronPartInterface {
        return $this->getPart('min');
    }

    public function getHour(): CronPartInterface {
        return $this->getPart('hour');
    }

    public function getDay(): CronPartInterface {
        return $this->getPart('day');
    }

    public function getMonth(): CronPartInterface {
        return $this->getPart('month');
    }

    public function getDayOfWeek(): CronPartInterface {
        return $this->getPart('dow');
    }

    private function getPart($part): CronPartInterface {
        return $this->parts[$part];
    }

    /**
     * @inheritDoc
     */
    public function getPattern() {
        $pattern = '%s %s %s %s %s';
        return sprintf($pattern,
                $this->parts['min']->getField(),
                $this->parts['hour']->getField(),
                $this->parts['day']->getField(),
                $this->parts['month']->getField(),
                $this->parts['dow']->getField()
        );
    }

    /**
     * @inheritDoc
     */
    public function setPattern($pattern) {
        $this->validator->validate($pattern);
        //
        $parts = preg_split('/[\s\t]+/', $pattern);
        $this->parts['min']->setPattern($parts[0]);
        $this->parts['hour']->setPattern($parts[1]);
        $this->parts['day']->setPattern($parts[2]);
        $this->parts['month']->setPattern($parts[3]);
        $this->parts['dow']->setPattern($parts[4]);
    }

    /**
     * Validate if this pattern can run on the given date.
     *
     * @param \DateTimeInterface $datetime
     *
     * @return bool
     */
    public function valid(\DateTime $datetime): bool {
        foreach ($this->parts as $part) {
            $valid = $part->valid($datetime);
            if ($valid === false) {
                return false;
            }
        }
        return true;
    }

    public function next(DateTimeInterface $datetime = null): DateTimeInterface {
        if ($datetime === null) {
            $datetime = new \DateTime();
        }
        $this->parts['dow']->next($datetime);
        $this->parts['month']->next($datetime);
        $this->parts['day']->next($datetime);
        $this->parts['hour']->next($datetime);
        $this->parts['min']->next($datetime);
        return $datetime;
    }

    public function previous(DateTimeInterface $datetime = null): DateTimeInterface {
        if ($datetime === null) {
            $datetime = new \DateTime();
        }
        $this->parts['dow']->previous($datetime);
        $this->parts['month']->previous($datetime);
        $this->parts['day']->previous($datetime);
        $this->parts['hour']->previous($datetime);
        $this->parts['min']->previous($datetime);
        return $datetime;
    }

    public function __toString(): string {
        return $this->getPattern();
    }

}
