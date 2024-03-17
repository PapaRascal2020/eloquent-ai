<?php

namespace Antley\EloquentAi\Drivers\ClaudeAi;

use Antley\EloquentAi\Drivers\CompletionsDriver;
use Antley\EloquentAi\Helpers\StreamedResponse;
use Illuminate\Support\Facades\Http;

class Completion implements CompletionsDriver
{

    /**
     * @var array
     */
    protected array $messages = [];

    protected string $systemMessage;

    /**
     * @var string
     */
    protected string $model = 'claude-3-opus-20240229';
    /**
     * @var array|string[]
     */
    protected array $allowedModels = ['claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307'];

    /**
     * @param string $model
     * @return $this
     */
    public function useModel(string $model): static
    {
        if (in_array($model, $this->allowedModels)) {
            $this->model = $model;
        }
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function withInstruction(string $message): static
    {
        $this->systemMessage = $message;

        return $this;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function create(array $args): static
    {
        $this->messages = [
            ...$this->messages,
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
        $response = Http::withHeaders([
            "anthropic-version" => "2023-06-01",
            "x-api-key" => $this->token
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'system' => $this->systemMessage,
            'max_tokens' => 1024,
            'messages' => $this->messages
        ])->json('content.0.text');


        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        return $this->messages;
    }

    /**
     * @return array
     */
    public function fetchStreamed(): array
    {
        return $this->fetch();
    }

    /**
     * @param string $token
     */
    public function __construct(protected string $token)
    {}
}
