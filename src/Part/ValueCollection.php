<?php

namespace Abouet\CronBundle\Part;

use Doctrine\Common\Collections\ArrayCollection;
use Abouet\CronBundle\Exception\OutOfRangeException;

class ValueCollection extends ArrayCollection implements CronCollectionInterface {

    public function isActive(int $value = null): bool {
        if (null === $value) {
            $value = $this->current();
        }
        return (bool) ($this->get($value) === true);
    }

    public function getPossibleValues(): array {
        return $this->getKeys();
    }

    public function getMin(): ?int {
        reset($this->getPossibleValues());
        return key($this->getPossibleValues());
    }

    public function getMax(): ?int {
        end($this->getPossibleValues());
        return key($this->getPossibleValues());
    }

    public function getActiveValues(): array {
        return $this->filter(function ($value) {
                    return $this->get($value) === true;
                });
    }

    public function getFirstActiveValue(): ?int {
        reset($this->getActiveValues());
        return key($this->getActiveValues());
    }

    public function getLastActiveValue(): ?int {
        end($this->getActiveValues());
        return key($this->getActiveValues());
    }

    public function setActive(int $value = null): void {
        if (null === $value) {
            $value = $this->current();
        }
        if (array_key_exists($value, $this->getPossibleValues())) {
            $this->set($value, true);
        } else {
            throw new OutOfRangeException(sprintf('value out of range: %s. MUST be between %s and %s', $value, $this->getMin(), $this->getMax()));
        }
    }

    public function reset(): void {
        foreach ($this as $key => $val) {
            $this->set($key, false);
        }
    }

}
