<?php

namespace Antley\EloquentAi;

use Antley\EloquentAi\Services\Audio;
use Antley\EloquentAi\Services\Completion;
use Antley\EloquentAi\Services\Image;
use Antley\EloquentAi\Services\Transcription;

class EloquentAi
{
    public static function image(): Image
    {
        return new Image();
    }

    public static function audio(): Audio
    {
        return new Audio();
    }

    public static function transcription(): Transcription
    {
        return new Transcription();
    }

    public static function completion(): Completion
    {
        return new Completion();
    }

}
