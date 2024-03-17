> [!NOTE]  
> If you are interested in this idea and can help contribute please don't hesitate to 
> do so and create a pull request =)

## Eloquent AI for Laravel
An eloquent approach to AI in Laravel.

### What does this package do?
Well, at the moment it is a wrapper for OpenAI (four services) and MinstralAI (one service).
The aim of this project is to create a package where switching the AI provider does not mean
a huge rewrite of code.

This will be achieved by created a common syntax for calling different Ai's and change models in a way that is similar to eloquent.
Examples of the syntax are at the bottom of this readme.

> [!CAUTION]
> This package is in very early development and should not be on a production server. 

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
    "antley/eloquent-ai": "^0.0.1"
```

Then in the `config/app.php` providers array add the following `ServiceProvidder`:

```php
\Antley\EloquentAi\EloquentAiServiceProvider::class,
```

Last but not least add the following items to your `.env` file:

```dotenv
ELOQUENT_AI_PROVIDER=
ELOQUENT_AI_PROVIDER_TOKEN=
ELOQUENT_AI_FALLBACK_PROVIDER=
ELOQUENT_AI_FALLBACK_PROVIDER_TOKEN=
```

### Getting Started

To get started you need to be signed up with either OpenAI (https://platform.openai.com)
or Minstral AI (https://console.mistral.ai/) - You can have both.

Currently OpenAI's Provider supports all four methods that currently exist in this package
whereas MinstralAI is only compatible with `completions`. You can, however if you want to use
MinstralAI for completions but OpenAI for others you can do the following in the `.env`:

```dotenv
ELOQUENT_AI_PROVIDER=MinstralAi
ELOQUENT_AI_PROVIDER_TOKEN=YOUR_TOKEN_HERE
ELOQUENT_AI_FALLBACK_PROVIDER=OpenAi
ELOQUENT_AI_FALLBACK_PROVIDER_TOKEN=YOUR_TOKEN_HERE
```
What this will do is when your provider does not support a method it can use the fallback and call that instead.

#### Example Syntax:

##### Completion (Chat)

```php
return  EloquentAi::completion()->create([
    ['role' => 'user', 'content' => 'Hello'],
    ['role' => 'assistant', 'content' => 'Why hello there! How can I help?'],
    ['role' => 'user', 'content' => 'Are you the bot from Minstral AI or Open AI?'],
])->withInstruction("You are a friendly AI assistant")
->useModel("gpt-4") // <-- This line is optional but is here to show you you can change model.
->fetch();
```
##### Image (Image From Text)

```php
return EloquentAi::image()->create([
    'prompt' => 'A guy on a jet ski looking towards brighton united kingdom',
    'size' => '512x512',
    'n' => 1 
])->fetch();
```
##### Audio (Text To Speech)

```php
return EloquentAi::audio()->create([
    'input' => 'Hello Anton, this is my audio generation',
    'voice' => 'alloy'
])->fetch();
```
##### Transcription (Speech To Text)

```php
return EloquentAi::transcription()->create([
    'file' => public_path('/harvard.wav')
])->fetch();
```

