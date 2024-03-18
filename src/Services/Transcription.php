<?php

namespace Antley\EloquentAi\Services;

use Antley\EloquentAi\Interfaces\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Transcription implements Service
{
    // Supported Model Endpoints
    protected array $endpoints = [
        'open-ai' => 'https://api.openai.com/v1/audio/transcriptions'
    ];

    protected array $args = [];

    protected array $allowedArgs = ['file'];

    // The default service & model when none specified
    protected string $service = 'open-ai';
    protected string $model = 'whisper-1';

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

        return Http::withHeaders(config("eloquent-ai.config.headers.{$this->service}"))
            ->asMultipart()
            ->attach('file', file_get_contents($this->args['file']), File::basename($this->args['file']))
            ->post($this->endpoints[$this->service], [
                'model' => $this->model
            ])->json();
    }

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
    public function __construct()
    {
        $this->allowedModels = config('eloquent-ai.config.services.transcription.models');
    }
}
