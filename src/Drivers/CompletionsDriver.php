<?php

namespace Antley\EloquentAi\Drivers;

interface CompletionsDriver extends Driver
{
    /**
     * @param string $message
     * @return $this
     *
     * This is for prompt engineering (optional)
     */
    public function withInstruction(string $message): static;

    /**
     * @return array
     */
    public function fetchStreamed(): array;

}
