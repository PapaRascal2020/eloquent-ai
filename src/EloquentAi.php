<?php

namespace Antley\EloquentAi;

use Antley\EloquentAi\Drivers\Driver;

class EloquentAi
{
    protected array $messages = [];

    /**
     * @return Driver|\InvalidArgumentException
     */
    public static function image(): Driver | \InvalidArgumentException
    {
        return self::getDriver("Image");
    }

    /**
     * @return Driver|\InvalidArgumentException
     */
    public static function audio(): Driver | \InvalidArgumentException
    {
        return self::getDriver("Audio");
    }

    /**
     * @return Driver|\InvalidArgumentException
     */
    public static function transcription(): Driver | \InvalidArgumentException
    {
        return self::getDriver("Transcription");
    }

    /**
     * @return Driver|\InvalidArgumentException
     */
    public static function completion(): Driver | \InvalidArgumentException
    {
        return self::getDriver("Completion");
    }

    /**
     * @param string $method
     * @return Driver|\InvalidArgumentException
     */
    public static function getDriver(string $method): Driver | \InvalidArgumentException
    {
        // Get the driver from config
        $provider = config('eloquent-ai.provider');

        // Get the supported methods of the provider
        $supportedMethods = config("eloquent-ai.providers.{$provider}");

        // If the method isn't supported look for a fallback and if one
        // is present try and load from that.
        if(!in_array($method, $supportedMethods)) {

            // Check if the user has a fallback provider
            if ($fallbackProvider = config('eloquent-ai.fallback_provider')) {

                // Let's check if fallback provider supports this method
                $supportedMethods = config("eloquent-ai.providers.{$fallbackProvider}");

                if(!in_array($method, $supportedMethods)) {
                    throw new \InvalidArgumentException(
                        "The method {$method} you are trying to access is unavailable on your chosen provider {$provider}."
                    );
                } else {
                    // Check to see if the class is available
                    if (!class_exists($driverClass = "Antley\\EloquentAi\\Drivers\\$fallbackProvider\\$method")) {
                        throw new \RuntimeException("Driver class '$driverClass' not found.");
                    }

                    // If all is good return the driver instance
                    return new $driverClass(config('eloquent-ai.fallback_provider_token'));
                }

            } else {
                throw new \InvalidArgumentException(
                    "The method {$method} you are trying to access is unavailable on your chosen provider {$provider}."
                );
            }
        }

        // If the method is supported check if class is available
        if (!class_exists($driverClass = "Antley\\EloquentAi\\Drivers\\$provider\\$method")) {
            throw new \RuntimeException("Driver class '$driverClass' not found.");
        }

        // All good, we can return the method instance.
        return new $driverClass(config('eloquent-ai.provider_token'));
    }

}
