<?php

namespace Antley\EloquentAi;

use Antley\EloquentAi\Services\Audio;
use Antley\EloquentAi\Services\Completion;
use Antley\EloquentAi\Services\Embedding;
use Antley\EloquentAi\Services\Image;
use Antley\EloquentAi\Services\Moderate;
use Antley\EloquentAi\Services\Transcription;

class EloquentAi
{
    /**
     * Converts a prompt to an image
     *
     * @see https://platform.openai.com/docs/api-reference/images
     */
    public static function image(): Image
    {
        return new Image();
    }

    /**
     * Converts a prompt to an audio file
     *
     * @see https://platform.openai.com/docs/api-reference/audio
     */
    public static function audio(): Audio
    {
        return new Audio();
    }

    /**
     * Transcribes a given audio file.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createTranscription
     */
    public static function transcription(): Transcription
    {
        return new Transcription();
    }

    /**
     * Given an array of prompts, the model will return one or more completion predictions
     *
     * @see https://platform.openai.com/docs/api-reference/completions (for Open AI)
     * @see https://docs.mistral.ai/api/#operation/createChatCompletion (for Mistral AI)
     * @see https://docs.anthropic.com/claude/reference/messages_post (for Claude AI)
     */
    public static function completion(): Completion
    {
        return new Completion();
    }

    /**
     * Creates a machine-readable vector from a given input
     *
     * @see https://platform.openai.com/docs/api-reference/embeddings (for Open AI)
     * @see https://docs.mistral.ai/api/#operation/createEmbedding (for Mistral AI)
     */
    public static function embedding(): Embedding
    {
        return new Embedding();
    }

    /**
     * Given some input text, outputs if the model classifies it as potentially harmful across several categories.
     *
     * @see https://platform.openai.com/docs/api-reference/moderations
     */
    public static function moderate(): Moderate
    {
        return new Moderate();
    }

}
