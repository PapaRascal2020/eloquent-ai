<?php

namespace Antley\EloquentAi\Drivers\OpenAi;

use Antley\EloquentAi\Drivers\Driver;
use Illuminate\Support\Facades\Http;

class Image implements Driver
{
    protected string $model = "dall-e-2";
    protected array $args = [];
    protected array $allowedModels = ['dall-e-2', 'dall-e-3'];

    protected array $allowedArgs = ['prompt', 'size', 'quality', 'n'];

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
     * @return array
     */
    public function fetch(): array
    {
        return Http::withToken($this->token)
            ->post('https://api.openai.com/v1/images/generations', [
                'model' => $this->model,
                ...$this->args
            ])->json('data.0');
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
