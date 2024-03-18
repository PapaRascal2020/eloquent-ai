> [!CAUTION]
> This package is in very early development and should not be on a production server.

> [!NOTE]  
> If you are interested in this idea and can help contribute please don't hesitate to 
> do so and create a pull request =)

## Eloquent AI for Laravel
An eloquent approach to AI in Laravel.

![Latest Version](https://img.shields.io/badge/Version-0.0.3-blue)
![Stability](https://img.shields.io/badge/Stability-alpha-red)

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
  - text-embedding-3-small
  - text-embedding-3-large
  - text-embedding-ada-002
  - text-moderation-latest
  - text-moderation-stable
  - text-moderation-007
- Mistral AI
  - mistral-small-latest
  - mistral-medium-latest
  - mistral-large-latest
  - open-mistral-7b
  - mistral-embed
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

In your Laravel app do the following:

In `composer.json` add the following repository to the `repositories` section:

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/PapaRascal2020/eloquent-ai"
    }
],
```
Then add the following to the `require` section.

```array
    "antley/eloquent-ai": "dev-main"
```

Save `composer.json`

Then, open `config/app.php` and add the following to `ServiceProvider`:

```php
\Antley\EloquentAi\EloquentAiServiceProvider::class,
```

Once this is done, open the terminal and type the following:

```bash
 composer update
```

That's it! You are now ready to use the package.

### Getting Started

There are six services and they are:

- **Completions** - _To chat with AI_
- **Embedding** - _To create vector representations of your text_
- **Image** - _To generate images by user input._
- **Audio** - _Take text and convert to audio_
- **Transcription** - _Take an audio file and return text_
- **Moderation** - _Moderate a string of text (i.e Comment) for harmful content_

Currently, Open AI offers all of them where as Claude AI & Mistral AI are for some.
To get the best out of this plugin you will need at least an Open AI api key, you 
can get this by going to https://platform.openai.com and registering an account.

For Mistral AI (https://console.mistral.ai/) & Claude AI (https://console.anthropic.com/)
models you would need to get sign up on the relevant sites (above)

Start by updating your `.env` file with the following fields.

```dotenv
ELOQUENT_AI_CLAUDEAI_TOKEN=YOUR_CLAUDEAI_API_TOKEN (Optional - if you want to use its models)
ELOQUENT_AI_OPENAI_TOKEN=YOUR_OPENAI_API_TOKEN (Required)
ELOQUENT_AI_MISTRALAI_TOKEN=YOUR_MISTRALAI_API_TOKEN (optional - if you want to use its models)
```
You are now ready to use the examples below to create your AI calls.

#### Examples:

##### Completion

```php
return  EloquentAi::completion()->create([
    ['role' => 'user', 'content' => 'Hello'],
    ['role' => 'assistant', 'content' => 'Why hello there! How can I help?'],
    ['role' => 'user', 'content' => 'Are you the bot from Minstral AI or Open AI?'],
])->withInstruction("You are a friendly AI assistant")
->use("open-ai.gpt-4") // <-- Not required, defaults to 'open-ai.gpt-3.5-turbo'..
->fetch();
```

##### Embedding

```php
return EloquentAi::embedding()->create([
        'This is a sample embedding'
    ])->use('mistral-ai.mistral-embed')->fetch();
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
###### Example Response
```json
{
  "text":"The stale smell of old beer lingers. It takes heat to bring out the odor. A cold dip restores health and zest. A salt pickle tastes fine with ham. Tacos al pastor are my favorite. A zestful food is the hot cross bun."
}
```

##### Moderation 
This is a service where you feed it text from a comment for example and it will return 
with an array of boolean values for certain moderation points.

```php
return EloquentAi::moderate()
->create('This is an example thread with no bad content')
->fetch();
```
###### Example Response

```json
{
  "id":"modr-94DxgkEGhw7yJDlq8oCrLOVXnqli5",
  "model":"text-moderation-007",
  "results":[
    {
      "flagged":true,
      "categories":{
        "sexual":false,
        "hate":false,
        "harassment":true,
        "self-harm":false,
        "sexual\/minors":false,
        "hate\/threatening":false,
        "violence\/graphic":false,
        "self-harm\/intent":false,
        "self-harm\/instructions":false,
        "harassment\/threatening":false,
        "violence":false
      },
      "category_scores":{
        "sexual":0.02169245481491089,
        "hate":0.024598680436611176,
        "harassment":0.9903337359428406,
        "self-harm":5.543852603295818e-5,
        "sexual\/minors":2.5174302209052257e-5,
        "hate\/threatening":2.9870452635805123e-6,
        "violence\/graphic":6.8601830207626335e-6,
        "self-harm\/intent":0.0002317160106031224,
        "self-harm\/instructions":0.00011696072033373639,
        "harassment\/threatening":1.837775380408857e-5,
        "violence":0.00020553809008561075
      }
    }
  ]}
```

