<?php

namespace Antley\EloquentAi\Services;

use Antley\EloquentAi\Helpers\StreamedResponse;
use Antley\EloquentAi\Interfaces\CompletionService;
use Illuminate\Support\Facades\Http;

class Completion implements CompletionService
{
    // Supported Model Endpoints
    protected array $endpoints = [
        'open-ai' => 'https://api.openai.com/v1/chat/completions',
        'mistral-ai' =>'https://api.mistral.ai/v1/chat/completions',
        'claude-ai' => 'https://api.anthropic.com/v1/messages'
    ];

    // System Prompt Config
    protected array $promptConfig = [
        'open-ai' => 'MESSAGE',
        'mistral-ai' =>'MESSAGE',
        'claude-ai' => 'SYSTEM'
    ];

    // The default service & model when none specified
    protected string $service = 'open-ai';
    protected string $model = 'gpt-3.5-turbo';

    // The messages array
    protected array $payload = ['messages' => []];

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
     * @param string $message
     * @return $this
     */
    public function withInstruction(string $message): static
    {
        if ($this->promptConfig[$this->service] === 'SYSTEM') {
            $this->payload[] = ['system' => $message];
        } else {
            $this->payload['messages'] = [
                ['role' => 'system', 'content' => $message],
                ...$this->payload['messages']
            ];
        }

        return $this;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function create(array $args): static
    {
        $this->payload['messages'] = [
            ...$this->payload['messages'],
            ...$args
        ];

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
                'max_tokens' => 1024,
                ...$this->payload
            ])->json();

        return $response;
    }

    /**
     * @return array
     */
    public function fetchStreamed(): array
    {
        $response = (new StreamedResponse)->withHeaders(config("eloquent-ai.config.headers.{$this->service}"))
            ->post($this->endpoints[$this->service], [
                'model' => $this->model,
                'max_tokens' => 1024,
                $this->payload,
            ])->streamedResponse();

        return $response;
    }

    /**
     * @param string $token
     */
    public function __construct()
    {
        $this->allowedModels = config('eloquent-ai.config.services.completions.models');
    }
}
