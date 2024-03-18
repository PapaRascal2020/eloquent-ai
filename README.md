> [!CAUTION]
> This package is in very early development and should not be on a production server.

> [!NOTE]  
> If you are interested in this idea and can help contribute please don't hesitate to 
> do so and create a pull request =)

## Eloquent AI for Laravel
An eloquent approach to AI in Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antley/eloquent-ai.svg?style=flat-square)](https://packagist.org/packages/antley/eloquent-ai)
[![Total Downloads](https://img.shields.io/packagist/dt/antley/eloquent-ai.svg?style=flat-square)](https://packagist.org/packages/antley/eloquent-ai

### About
Inspired by Eloquent ORM, Eloquent AI provides a wrapper for interacting with AI.

**AI Models Supported:**

- Open Ai
  - gpt-3.5-turbo
  - gpt-4
  - tts-1
  - tts-1-hd
  - dall-e-2
  - dall-e-3
  - whisper-1
- Mistral AI
  - mistral-small-latest
- Claude AI
   - claude-3-opus-20240229
   - claude-3-sonnet-20240229
   - claude-3-haiku-20240307

The aim of this project is to create a package where switching between
AIs and there models as simple as possible.

This will be achieved by created a common syntax for calling different services
(Completions, Text To Speech, Speech To Text, Text To Image) in a way that is similar to eloquent.

Examples of the syntax are at the bottom of this readme.

### Installation

First you will need to use an existing or new Laravel app.

Go to `composer.json` file and add the following to the `repositories` section:

```array
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/PapaRascal2020/eloquent-ai"
    }
],
```
Then in the required section add the following:

```array
    "antley/eloquent-ai": "dev-main"
```

Then in the `config/app.php` providers array add the following `ServiceProvidder`:

```php
\Antley\EloquentAi\EloquentAiServiceProvider::class,
```

Last but not least add the following items to your `.env` file:

```dotenv
ELOQUENT_AI_CLAUDEAI_TOKEN=YOUR_CLAUDEAI_API_TOKEN
ELOQUENT_AI_OPENAI_TOKEN=YOUR_OPENAI_API_TOKEN
ELOQUENT_AI_MISTRALAI_TOKEN=YOUR_MISTRALAI_API_TOKEN
```

### Getting Started

For defaults, an account with OpenAI (https://platform.openai.com) is needed.

For Mistral AI (https://console.mistral.ai/) & Claude AI (https://console.anthropic.com/)
models you would need to get sign up on the relevant sites (above)

Currently OpenAI's Provider supports all four methods that currently exist in this package
whereas Mistral AI & Claude AIis only compatible with `completions`. 

To start update your `.env` file with the following fields.

```dotenv
ELOQUENT_AI_CLAUDEAI_TOKEN=YOUR_CLAUDEAI_API_TOKEN
ELOQUENT_AI_OPENAI_TOKEN=YOUR_OPENAI_API_TOKEN
ELOQUENT_AI_MISTRALAI_TOKEN=YOUR_MISTRALAI_API_TOKEN
```
That's it you can now call the services.

#### Example Syntax:

##### Completion (Chat)

```php
return  EloquentAi::completion()->create([
    ['role' => 'user', 'content' => 'Hello'],
    ['role' => 'assistant', 'content' => 'Why hello there! How can I help?'],
    ['role' => 'user', 'content' => 'Are you the bot from Minstral AI or Open AI?'],
])->withInstruction("You are a friendly AI assistant")
->use("open-ai.gpt-4") // <-- Not required, defaults to 'open-ai.gpt-3.5-turbo'..
->fetch();
```
##### Image (Image From Text)

```php
return EloquentAi::image()->create([
    'prompt' => 'A guy on a jet ski looking towards brighton',
    'size' => '512x512',
    'n' => 1 
])->fetch();
```
##### Audio (Text To Speech)

```php
return EloquentAi::audio()->create([
    'input' => 'Hello, this is my audio generation',
    'voice' => 'alloy'
])->fetch();
```
##### Transcription (Speech To Text)

```php
return EloquentAi::transcription()->create([
    'file' => public_path('/harvard.wav')
])->fetch();
```

