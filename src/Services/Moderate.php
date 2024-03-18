<?php

namespace Antley\EloquentAi\Services;

use Antley\EloquentAi\Helpers\StreamedResponse;
use Antley\EloquentAi\Interfaces\Service;
use Illuminate\Support\Facades\Http;

class Moderate implements Service
{
    // Supported Model Endpoints
    protected array $endpoints = [
        'open-ai' => 'https://api.openai.com/v1/moderations',
    ];

    protected array $inputFormat = [
      'open-ai' => 'string',
    ];

    // The default service & model when none specified
    protected string $service = 'open-ai';
    protected string $model = 'text-moderation-stable';

    // The allowed models
    protected array $allowedModels = [];


    /**
     * @param string $model
     * @return $this
     */
    public function use(string $model): static
    {
        if (in_array($model, $this->allowedModels)) {

            $parts = explode('.', $model);

            $this->service = $parts[0];
            $this->model = $parts[1];
        }
        return $this;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function create(array|string $inputs): static
    {
        if ($this->inputFormat[$this->service] === 'array')
        {
            if(is_array($inputs))
            {
                $this->payload = $inputs;
            } else {
                $this->payload = explode(' ', $inputs);
            }
        } else {
            if(is_array($inputs))
            {
                $this->payload = implode(' ', $inputs);
            } else {
                $this->payload = $inputs;
            }
        }

        return $this;
    }

    /**
     * @param array $args
     * @return array
     */
    public function fetch(): array
    {
        $response = Http::withHeaders(config("eloquent-ai.config.headers.{$this->service}"))
            ->post($this->endpoints[$this->service], [
                'model' => $this->model,
                'input' => $this->payload
            ])->json();

        return $response;
    }

    /**
     * @param string $token
     */
    public function __construct()
    {
        $this->allowedModels = config('eloquent-ai.config.services.moderate.models');
    }
}
