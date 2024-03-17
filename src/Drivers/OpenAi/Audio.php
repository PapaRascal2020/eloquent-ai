<?php

namespace Antley\EloquentAi\Drivers\OpenAi;

use Antley\EloquentAi\Drivers\Driver;
use Illuminate\Support\Facades\Http;

class Audio implements Driver
{
    protected string $model = 'tts-1';
    protected array $args = [];
    protected array $allowedModels = ['tts-1', 'tts-2'];
    protected array $allowedArgs = ['input', 'voice'];


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
     * @param array $args
     * @return $this
     */
    public function create(array $args): static
    {
        $this->args = $this->filter($args);
        return $this;
    }

    /**
     * @return string
     */
    public function fetch(): string
    {
        $response = Http::withToken($this->token)
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => $this->model,
                ...$this->args
            ]);

        return $response;
    }

    /**
     * @param array $args
     * @return array
     */
    protected function filter (array $args): array
    {
        $filtered = [];

        foreach ($args as $key => $value) {
            if (in_array($key, $this->allowedArgs)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * @param string $token
     */
    public function __construct(protected string $token)
    {}
}
