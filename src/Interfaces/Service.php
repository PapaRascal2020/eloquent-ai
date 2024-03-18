<?php

namespace Antley\EloquentAi\Interfaces;

interface Service
{
    /**
     * @param string $token
     */
    public function __construct();

    /**
     * @param string $model
     * @return $this
     *
     * This is to specify the model (optional)
     */
    public function use(string $model):static;

    /**
     * @param array $args
     * @return $this
     */
    public function create(array $args): static;

    /**
     * @return mixed
     */
    public function fetch(): mixed;

}
