<?php

namespace Antley\EloquentAi\Drivers;

interface Driver
{
    /**
     * @param string $token
     */
    public function __construct(string $token);

    /**
     * @param string $model
     * @return $this
     *
     * This is to specify the model (optional)
     */
    public function useModel(string $model):static;

    /**
     * @param array $args
     * @return $this
     */
    public function create(array $args): static;

    /**
     * @return array|string
     */
    public function fetch(): array | string;


}
