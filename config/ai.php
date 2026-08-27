<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider Names
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the AI providers below should be the
    | default for AI operations when no explicit provider is provided
    | for the operation. This should be any provider defined below.
    |
    */

    'default' => env('AI_DEFAULT_PROVIDER', 'openrouter'),
    'default_for_images' => 'gemini',
    'default_for_audio' => 'openai',
    'default_for_transcription' => 'openai',
    'default_for_embeddings' => env('AI_EMBEDDINGS_PROVIDER', 'openrouter'),
    'default_for_reranking' => 'cohere',

    /*
    |--------------------------------------------------------------------------
    | PHP Execution Time Limit
    |--------------------------------------------------------------------------
    |
    | AI requests (especially 4-variant prototypes) can exceed PHP's default
    | 30 second max_execution_time. This value is passed to set_time_limit().
    |
    */

    'execution_time_limit' => (int) env('AI_EXECUTION_TIME_LIMIT', 300),

    /*
    |--------------------------------------------------------------------------
    | Vendored AI Skills
    |--------------------------------------------------------------------------
    |
    | Runtime skills loaded from resources/ai/skills/. The prototype palette
    | assigns one skill per variant slot (4 separate agent calls).
    |
    */

    'skills' => [
        'registry' => [
            'refactoring-ui' => [
                'path' => 'refactoring-ui',
            ],
            'apple-design' => [
                'vendor' => 'emilkowalski',
                'skill' => 'apple-design',
            ],
            'animate' => [
                'vendor' => 'emilkowalski',
                'skill' => 'animate',
                'bundle' => ['animation-vocabulary'],
            ],
            'animation-vocabulary' => [
                'vendor' => 'emilkowalski',
                'skill' => 'animation-vocabulary',
            ],
            'emil-design-eng' => [
                'vendor' => 'emilkowalski',
                'skill' => 'emil-design-eng',
            ],
            'prototype' => [
                'vendor' => 'emilkowalski',
                'skill' => 'prototype',
            ],
            'review-animations' => [
                'vendor' => 'emilkowalski',
                'skill' => 'review-animations',
            ],
        ],

        'prototype_palette' => [
            [
                'skill' => 'refactoring-ui',
                'name' => 'Refactoring UI',
                'axis' => 'Typography, spacing, hierarchy',
            ],
            [
                'skill' => 'apple-design',
                'name' => 'Apple',
                'axis' => 'Restrained, fluid, minimal',
            ],
            [
                'skill' => 'animate',
                'name' => 'Motion',
                'axis' => 'Entrance, easing, micro-interactions',
            ],
            [
                'skill' => 'emil-design-eng',
                'name' => 'Design Eng',
                'axis' => 'Balanced polish + motion craft',
            ],
        ],

        /*
        | Skills available in the block editor dropdown (one skill per generation).
        */
        'block_skills' => [
            [
                'skill' => 'refactoring-ui',
                'name' => 'Refactoring UI',
                'description' => 'Adam Wathan — typography, spacing, hierarchy',
            ],
            [
                'skill' => 'apple-design',
                'name' => 'Apple Design',
                'description' => 'Restrained, fluid, minimal',
            ],
            [
                'skill' => 'animate',
                'name' => 'Animate',
                'description' => 'Motion, easing, micro-interactions',
            ],
            [
                'skill' => 'emil-design-eng',
                'name' => 'Design Eng',
                'description' => 'Balanced polish + motion craft',
            ],
            [
                'skill' => 'prototype',
                'name' => 'Prototype',
                'description' => 'Emil — divergent exploration mindset',
            ],
            [
                'skill' => 'review-animations',
                'name' => 'Review Animations',
                'description' => 'Audit motion (returns HTML improvements)',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Editor Models
    |--------------------------------------------------------------------------
    |
    | Models available in the block editor dropdown. Must be valid OpenRouter
    | model IDs. The default selection comes from OPENROUTER_TEXT_MODEL.
    |
    */

    'block_models' => [
        [
            'model' => 'openrouter/free',
            'name' => 'OpenRouter Free',
            'description' => 'Auto-picks a free model (can be slow)',
        ],
        [
            'model' => 'nvidia/nemotron-3.5-lightning:free',
            'name' => 'Nemotron 3.5 Lightning',
            'description' => 'Fast — good default for HTML blocks',
        ],
        [
            'model' => 'nvidia/nemotron-3-super-120b-a12b:free',
            'name' => 'Nemotron Super 120B',
            'description' => 'Stronger output, slower',
        ],
        [
            'model' => 'nvidia/nemotron-3-nano-omni-30b-a3b-reasoning:free',
            'name' => 'Nemotron Nano Reasoning',
            'description' => 'Reasoning model — often slow',
        ],
        [
            'model' => 'nvidia/nemotron-3-ultra-550b-a55b:free',
            'name' => 'Nemotron Ultra 550B',
            'description' => 'Largest free Nemotron',
        ],
        [
            'model' => 'poolside/laguna-xs-2.1:free',
            'name' => 'Laguna XS',
            'description' => 'Compact free model',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Below you may configure caching strategies for AI related operations
    | such as embedding generation. You are free to adjust these values
    | based on your application's available caching stores and needs.
    |
    */

    'caching' => [
        'embeddings' => [
            'cache' => false,
            'store' => env('CACHE_STORE', 'database'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    |
    | Below are each of your AI providers defined for this application. Each
    | represents an AI provider and API key combination which can be used
    | to perform tasks like text, image, and audio creation via agents.
    |
    */

    'providers' => [
        'anthropic' => [
            'driver' => 'anthropic',
            'key' => env('ANTHROPIC_API_KEY'),
            'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com/v1'),
        ],

        'azure' => [
            'driver' => 'azure',
            'key' => env('AZURE_OPENAI_API_KEY'),
            'url' => env('AZURE_OPENAI_URL'),
            'api_version' => env('AZURE_OPENAI_API_VERSION', '2024-10-21'),
            'deployment' => env('AZURE_OPENAI_DEPLOYMENT', 'gpt-4o'),
            'embedding_deployment' => env('AZURE_OPENAI_EMBEDDING_DEPLOYMENT', 'text-embedding-3-small'),
        ],

        'cohere' => [
            'driver' => 'cohere',
            'key' => env('COHERE_API_KEY'),
        ],

        'deepseek' => [
            'driver' => 'deepseek',
            'key' => env('DEEPSEEK_API_KEY'),
        ],

        'eleven' => [
            'driver' => 'eleven',
            'key' => env('ELEVENLABS_API_KEY'),
        ],

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
        ],

        'groq' => [
            'driver' => 'groq',
            'key' => env('GROQ_API_KEY'),
            'url' => env('GROQ_URL', 'https://api.groq.com/openai/v1'),
        ],

        'jina' => [
            'driver' => 'jina',
            'key' => env('JINA_API_KEY'),
        ],

        'mistral' => [
            'driver' => 'mistral',
            'key' => env('MISTRAL_API_KEY'),
            'url' => env('MISTRAL_URL', 'https://api.mistral.ai/v1'),
        ],

        'ollama' => [
            'driver' => 'ollama',
            'key' => env('OLLAMA_API_KEY', ''),
            'url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
        ],

        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY'),
            'url' => env('OPENAI_URL', 'https://api.openai.com/v1'),
            'models' => [
                'text' => [
                    'default' => env('OPENAI_TEXT_MODEL', 'gpt-4o-mini'),
                    'cheapest' => env('OPENAI_TEXT_MODEL_CHEAPEST', 'gpt-4o-mini'),
                    'smartest' => env('OPENAI_TEXT_MODEL_SMARTEST', 'gpt-4o'),
                ],
            ],
        ],

        'openrouter' => [
            'driver' => 'openrouter',
            'key' => env('OPENROUTER_API_KEY'),
            'http_referer' => env('OPENROUTER_HTTP_REFERER', env('APP_URL')),
            'x_title' => env('OPENROUTER_APP_NAME', env('APP_NAME')),
            'models' => [
                'text' => [
                    'default' => env('OPENROUTER_TEXT_MODEL', 'openrouter/free'),
                    'cheapest' => env('OPENROUTER_TEXT_MODEL_CHEAPEST', 'openrouter/free'),
                    'smartest' => env('OPENROUTER_TEXT_MODEL_SMARTEST', 'openrouter/free'),
                ],
                'embeddings' => [
                    'default' => env('OPENROUTER_EMBEDDINGS_MODEL', 'liquid/lfm-2.5-embedding-350m:free'),
                    'dimensions' => (int) env('OPENROUTER_EMBEDDINGS_DIMENSIONS', 1024),
                ],
            ],
        ],

        'voyageai' => [
            'driver' => 'voyageai',
            'key' => env('VOYAGEAI_API_KEY'),
        ],

        'xai' => [
            'driver' => 'xai',
            'key' => env('XAI_API_KEY'),
            'url' => env('XAI_URL', 'https://api.x.ai/v1'),
        ],
    ],

];
