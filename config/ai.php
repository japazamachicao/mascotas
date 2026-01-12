<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI services used in the application
    |
    */

    'provider' => env('AI_PROVIDER', 'gemini'),
    'api_key' => env('GEMINI_API_KEY'),
    'max_analyses_per_day' => env('AI_MAX_DAILY_ANALYSES', 10),
    
    'health_analysis' => [
        'enabled' => env('AI_HEALTH_ANALYSIS_ENABLED', true),
        'prompts' => [
            'feces' => 'Eres un asistente veterinario experto. Analiza esta imagen de heces de mascota y proporciona un análisis detallado en español.

Identifica:
- Color y consistencia
- Presencia de sangre (fresca o digerida)
- Posibles parásitos o cuerpos extraños
- Signos de diarrea o estreñimiento
- Cualquier anomalía visible

Responde en formato JSON con esta estructura exacta:
{
  "findings": ["hallazgo 1", "hallazgo 2", ...],
  "requires_attention": true/false,
  "recommendations": "texto con recomendaciones",
  "confidence_score": 0.XX
}',

            'urine' => 'Eres un asistente veterinario experto. Analiza esta imagen de orina de mascota y proporciona un análisis detallado en español.

Identifica:
- Color (normal, oscuro, muy claro, rojizo, etc.)
- Claridad (transparente, turbia, con sedimentos)
- Presencia de sangre
- Signos de deshidratación
- Cualquier anomalía visible

Responde en formato JSON con esta estructura exacta:
{
  "findings": ["hallazgo 1", "hallazgo 2", ...],
  "requires_attention": true/false,
  "recommendations": "texto con recomendaciones",
  "confidence_score": 0.XX
}',

            'skin' => 'Eres un asistente veterinario experto. Analiza esta imagen de piel, lengua u otra parte visible de la mascota y proporciona un análisis detallado en español.

Identifica:
- Lesiones cutáneas (enrojecimiento, inflamación, heridas)
- Presencia de parásitos (pulgas, garrapatas)
- Erupciones, costras o descamación
- Color anormal (palidez, cianosis, ictericia)
- Tumores o bultos visibles
- Estado de mucosas (lengua, encías)
- Pérdida de pelo o alopecia
- Signos de infección o irritación

Responde en formato JSON con esta estructura exacta:
{
  "findings": ["hallazgo 1", "hallazgo 2", ...],
  "requires_attention": true/false,
  "recommendations": "texto con recomendaciones",
  "confidence_score": 0.XX
}',
        ],
    ],
    
    'breed_detection' => [
        'enabled' => env('AI_BREED_DETECTION_ENABLED', true),
        
        'prompt' => 'Eres un experto en razas caninas y felinas. Analiza esta imagen y proporciona un análisis detallado en formato JSON con la siguiente estructura:
{
    "breeds": [
        {
            "name": "nombre de la raza",
            "percentage": porcentaje (0-100),
            "characteristics": ["características visibles que lo identifican"]
        }
    ],
    "primary_species": "dog/cat",
    "is_mixed": true/false,
    "confidence_score": 0.0-1.0,
    "size_category": "toy/small/medium/large/giant",
    "coat_type": "short/medium/long/wire/curly"
}
Para perros mestizos, identifica las razas predominantes (máximo 3). Para razas puras, indica solo esa raza con 100%.',
    ],

    'gemini' => [
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/',
    ],
];
