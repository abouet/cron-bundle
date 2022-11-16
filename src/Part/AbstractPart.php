<?php

namespace Abouet\CronBundle\Part;

use Cron\Validator\ValidatorInterface;
use Abouet\CronBundle\Validator\ListValidator;
use Abouet\CronBundle\Validator\FrequencyValidator;
use Abouet\CronBundle\Validator\RangeValidator;
use Abouet\CronBundle\Exception\OutOfRangeException;

abstract class AbstractPart implements CronPartInterface, \Stringable {

    const FULL = '*';
    const LIST_SEPARATOR = ',';
    const RANGE_SEPARATOR = '-';
    const FREQUENCY_TAG = '/';

    protected $pattern,
            $parsed = ['list' => null, 'frequency' => null, 'range' => ['start' => null, 'end' => null]],
            $values;

    public function __construct($pattern = null, $start, $end, protected ValidatorInterface $validator) {
        $this->values = new ValueCollection();
        for ($i = $start; $i <= $end; $i++) {
            $this->values->set($i, false);
        }
        $this->setPattern($pattern);
    }

    public function getValues(): CronCollectionInterface {
        return $this->values;
    }

    public function getPattern(): ?string {
        if ($this->isNull()) {
            if ($this->isAll()) {
                $this->pattern = self::FULL; //should not occur, as activateAll() set $this->pattern
            } elseif ($this->isList()) {
                $this->pattern = implode(self::LIST_SEPARATOR, $this->getList());
            } else {
                $this->pattern = self::FULL;
                if ($this->isRange()) {
                    $this->pattern = implode(self::RANGE_SEPARATOR, $this->getRange());
                }
                if ($this->isFrequency()) {
                    $this->pattern .= self::FREQUENCY_TAG . $this->getFrequency();
                }
            }
        }
        return $this->pattern;
    }

    public function setPattern(string|null $pattern): void {
        $this->pattern = $this->sanitize($pattern);
        // If empty pattern, no parsing
        if (null === $this->pattern) {
            return;
        }
        // Validate
        $this->validator->validate($this->pattern);
        // activate all
        if (self::FULL == $this->pattern) {
            $this->activateAll();
            return;
        }
        // Parse list : n,n[...]
        if (str_contains($this->pattern, self::LIST_SEPARATOR)) {
            $this->setList($this->pattern);
            return;
        }
        /**
         * Parse frequency and/or range
         */
        $this->getValues()->reset();
        $start = $this->getValues()->first();
        $end = $this->getValues()->last();
        $step = 1;
        // parse frequency : /n
        $valid = preg_match(FrequencyValidator::PATTERN, $this->pattern, $matches);
        if (1 == $valid) {
            $step = $this->sanitize($matches[1]);
            $this->parsed['frequency'] = $step;
        }
        // parse range : n-n
        $valid = preg_match(RangeValidator::PATTERN, $this->pattern, $matches);
        if (1 == $valid) {
            $start = $this->sanitize($matches[1]);
            $end = $this->sanitize($matches[2]);
            $this->parsed['range']['start'] = $start;
            $this->parsed['range']['end'] = $end;
        }
        //
        for ($i = $start; $i <= $end; $i + $step) {
            $this->activate($i);
        }
    }

    /**
     * Checks, if the given DateTimeInterface is a hit for this pattern.
     */
    public function valid(\DateTimeInterface $datetime): bool {
        return $this->getValues()->isActive($datetime->format(static::FORMAT));
    }

    public function isNull(): bool {
        return (null === $this->pattern);
    }

    public function isAll(): bool {
        return (self::FULL == $this->pattern);
    }

    /**
     * FREQUENCY
     */
    public function getFrequency(): ?int {
        return $this->parsed['frequency'];
    }

    public function setFrequency(int $frequency): void {
        $this->pattern = null;
        $this->validateFrequency($frequency);
        // set Frequency
        $this->parsed['frequency'] = $frequency;
        $this->getValues()->reset();
        for ($i = $this->getValues()->first(); $i <= $this->getValues()->last(); $i + $frequency) {
            $this->activate($i);
        }
    }

    public function validateFrequency(int $frequency): bool {
        $validator = new FrequencyValidator();
        $validator->validate(static::FREQUENCY_TAG . $frequency);
        if ($frequency > $this->getValues()->getMax()) {
            throw new OutOfRangeException(sprintf('Frequency MUST be less than %s. "%s" given', $this->getValues()->getMax(), $frequency));
        }
        return true;
    }

    public function setBoundFrequency(int $start, int $end, int $frequency): void {
        $this->validateFrequency($frequency);
        $this->validateRange($start, $end);
        $this->parsed['frequency'] = $frequency;
        $this->parsed['range']['start'] = $start;
        $this->parsed['range']['end'] = $end;
        $this->getValues()->reset();
        for ($i = $start; $i <= $end; $i + $frequency) {
            $this->activate($i);
        }
    }

    public function isFrequency(): bool {
        return (bool) (null !== $this->parsed['frequency']);
    }

    /**
     * RANGE
     */
    public function getRange(): array {
        if (!$this->isRange()) {
            return [];
        }
        return [$this->parsed['range']['start'], $this->parsed['range']['end']];
    }

    public function setRange(int $start, int $end): void {
        $this->pattern = null;
        $this->parsed['range']['start'] = $start;
        $this->parsed['range']['end'] = $end;
        $this->getValues()->reset();
        for ($i = $start; $i <= $end; $i++) {
            $this->activate($i);
        }
    }

    public function validateRange(int $start, int $end): bool {
        $validator = new RangeValidator();
        $validator->validate($start . static::RANGE_SEPARATOR . $end);
        if ($start < $this->getValues()->getMin()) {
            throw new OutOfRangeException(sprintf('Range MUST starts with a value GREATER than %s. "%s" given', $this->getValues()->getMin(), $start));
        }
        if ($end > $this->getValues()->getMax()) {
            throw new OutOfRangeException(sprintf('Range MUST end with a value LESS than %s. "%s" given', $this->getValues()->getMax(), $end));
        }
        return true;
    }

    public function isRange(): bool {
        return (bool) (null !== $this->parsed['range']['start']);
    }

    /**
     * LIST
     * */
    public function getList(): ?array {
        return $this->parsed['list'];
    }

    public function setList(array|string $list): void {
        $this->pattern = null;

        $validator = new ListValidator();
        if (is_array($list)) {
            $validator->validate(implode(static::LIST_SEPARATOR, $list));
            $this->parsed['list'] = $list;
        } else {
            $validator->validate($list);
            $this->parsed['list'] = explode(static::LIST_SEPARATOR, $list);
        }
        $this->getValues()->reset();
        $this->activate($this->parsed['list']);
    }

    public function isList(): bool {
        return (bool) ($this->parsed['list'] != null);
    }

    /**
     * Activation
     */
    protected function activate(array|int $value): void {
        if (is_array($value)) {
            forEach ($value as $v) {
                $this->activate($v);
            }
        } else {
            try {
                $this->getValues()->setActive($this->sanitize($value));
            } catch (OutOfRangeException $ex) {
                throw new OutOfRangeException(strtolower(get_class($this)) . ' ' . $ex->getMessage());
            }
        }
    }

    public function activateAll(): void {
        $this->pattern = self::FULL;
        foreach ($this->getValues() as $val => $bool) {
            $this->getValues()->setActive();
        }
    }

    /**
     * Correct entries if need be
     * This is a callback method that could be overwritten if you
     * need to support correction of entries like if you need
     * 0 or 7 for sunday, or if you want to use names.
     * Simply react on $val and return any valid int.
     *
     * @param string $val
     * @return mixed
     */
    protected function sanitize($val) {
        if (empty($val)) {
            $val = null;
        }
        return $val;
    }

    public function next(\DateTimeInterface &$datetime): void {
        // if pattern = *
        if ($this->isAll()) {
            $interval = 1;
            return;
        } else {
            $value = $datetime->format(static::FORMAT);
            $next = false;
            // find next execution
            foreach ($this->getValues()->getActiveValues() as $key => $bool) {
                if ($value === $key) {
                    $next = $this->getValues()->getActiveValues()->next();
                    break;
                }
            }
            // if not an active value
            if (false === $next) {
                $interval = ($this->getValues()->getMax() - $value) + $this->getValues()->getFirstActiveValue();
            } else {
                $interval = $next - $value;
            }
        }
        $pattern = sprintf(static::INTERVAL_PATTERN, $interval);
        $datetime->add($pattern);
    }

    public function previous(\DateTimeInterface &$datetime): void {
        // if pattern = *
        if ($this->isAll()) {
            $interval = 1;
            return;
        } else {
            $value = $datetime->format(static::FORMAT);
            $prev = false;
            // find previous execution
            foreach ($this->getValues()->getActiveValues() as $key => $bool) {
                if ($key == $value) {
                    $prev = prev($this->getValues()->getActiveValues());
                    break;
                }
            }
            // if not an active value
            if (false === $prev) {
                $interval = ($value - $this->getValues()->getMin()) + $this->getValues()->getLastActiveValue();
            } else {
                $interval = $value - $prev;
            }
        }
        $pattern = sprintf(static::INTERVAL_PATTERN, $interval);
        $datetime->sub($pattern);
    }

    public function __toString(): string {
        return $this->getPattern();
    }

}
