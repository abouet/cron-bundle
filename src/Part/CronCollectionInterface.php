<?php

namespace Abouet\CronBundle\Part;

interface CronCollectionInterface {

    public function isActive(int $value = null): bool;

    public function getPossibleValues(): array;

    public function getMin(): ?int;

    public function getMax(): ?int;

    public function getActiveValues(): array;

    public function getFirstActiveValue(): ?int;

    public function getLastActiveValue(): ?int;

    public function setActive(int $value = null): void;

    public function reset(): void;
}
