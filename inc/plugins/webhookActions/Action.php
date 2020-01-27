<?php

namespace webhookActions;

abstract class Action
{
    protected $options = [];
    protected $data = [];

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getRequiredOptions(): array
    {
        return [];
    }

    public function execute(): bool
    {
        return false;
    }
}
