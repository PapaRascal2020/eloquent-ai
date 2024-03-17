<?php

namespace Antley\EloquentAi\Drivers\OpenAi;

use Antley\EloquentAi\Drivers\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Transcription implements Driver
{

    protected string $model = "whisper-1";
    protected array $args = [];
    protected array $allowedModels = ['whisper-1'];

    protected array $allowedArgs = ['file'];

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
            ->asMultipart()
            ->attach('file', file_get_contents($this->args['file']), File::basename($this->args['file']))
            ->post('https://api.openai.com/v1/audio/transcriptions', [
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
    public function __construct(protected string $token)
    {}
}
