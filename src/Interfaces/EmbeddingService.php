<?php

namespace Antley\EloquentAi\Interfaces;

interface EmbeddingService extends Service
{

    /**
     * @param array $args
     * @return $this
     */
    public function create(array|string $args): static;

}
