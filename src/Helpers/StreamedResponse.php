<?php

namespace Antley\EloquentAi\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class StreamedResponse
{
    protected string $token;
    protected string $url;
    protected array $data;

    public function withToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function post(string $url, array $data): static
    {
        $this->url = $url;
        $this->data = $data;

        return $this;
    }

    public function streamedResponse(): string
    {
        $client = new Client();

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];

        $body = json_encode($this->data);

        try {

            $response = $client->request('POST', $this->url, [
                'headers' => $headers,
                'body' => $body,
                'stream' => true
            ]);

            header("Content-Type: text/event-stream");

            $stream = $response->getBody();

            $result = "";

            while (!$stream->eof()) {
                $line = $this->readLine($stream);

                if (!str_starts_with($line, 'data:')) {
                    continue;
                }

                $data = trim(substr($line, strlen('data:')));

                if ($data === '[DONE]') {
                    break;
                }

                $response = json_decode($data, true);

                echo $response['choices'][0]['delta']['content'] ?? "";
                $result = $response['choices'][0]['delta']['content'] ?? "";

            }

            ob_flush();
            ob_clean();

            return $result;

        } catch (GuzzleException $e) {
            // Handle any errors during the request
            return "Error: " . $e->getMessage();
        }
    }

    private function readLine($stream): string
    {
        $buffer = '';

        while (! $stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            if ($byte === "\n") {
                break;
            }
        }

        return $buffer . "\n";
    }
}
