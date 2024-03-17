<?php

namespace Antley\EloquentAi\Drivers\OpenAi;

use Antley\EloquentAi\Drivers\CompletionsDriver;
use Antley\EloquentAi\Helpers\StreamedResponse;
use Illuminate\Support\Facades\Http;

class Completion implements CompletionsDriver
{

    /**
     * @var array
     */
    protected array $messages = [];

    /**
     * @var string
     */
    protected string $model = 'gpt-3.5-turbo';
    /**
     * @var array|string[]
     */
    protected array $allowedModels = ['gpt-3.5-turbo', 'gpt-4'];

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
        $this->messages = [
            [
                'role' => 'system',
                'content' => $message
            ],
            ...$this->messages
        ];

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
        $response = Http::withToken($this->token)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $this->messages
            ])->json('choices.0.message.content');


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
        $response = (new StreamedResponse)->withToken($this->token)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $this->messages,
            ])->streamedResponse();

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        return $this->messages;
    }

    /**
     * @param string $token
     */
    public function __construct(protected string $token)
    {}
}
