<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIVisionService
{
    protected $apiKey;
    protected $model;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('ai.api_key');
        $this->model = config('ai.gemini.model', 'gemini-1.5-flash');
        $this->apiUrl = config('ai.gemini.api_url', 'https://generativelanguage.googleapis.com/v1beta/models/');
        
        // Validar que la API key esté configurada
        if (empty($this->apiKey)) {
            throw new \Exception('GEMINI_API_KEY no está configurada. Por favor agrega tu API key en el archivo .env');
        }
    }

    /**
     * Analizar heces
     */
    public function analyzeFeces(string $imagePath): array
    {
        $prompt = config('ai.health_analysis.prompts.feces');
        return $this->analyzeImage($imagePath, $prompt);
    }

    /**
     * Analizar orina
     */
    public function analyzeUrine(string $imagePath): array
    {
        $prompt = config('ai.health_analysis.prompts.urine');
        return $this->analyzeImage($imagePath, $prompt);
    }

    /**
     * Analizar piel, lengua y partes visibles
     */
    public function analyzeSkin(string $imagePath): array
    {
        $prompt = config('ai.health_analysis.prompts.skin');
        return $this->analyzeImage($imagePath, $prompt);
    }

    /**
     * Detectar raza de mascota
     */
    public function detectBreed(string $imagePath): array
    {
        $prompt = config('ai.breed_detection.prompt');
        return $this->analyzeImage($imagePath, $prompt);
    }

    /**
     * Método genérico para analizar imágenes con Gemini API
     */
    protected function analyzeImage(string $imagePath, string $prompt): array
    {
        try {
            // Limpiar la ruta - remover 'public/' si existe
            $cleanPath = str_replace('public/', '', $imagePath);
            
            // Verificar que el archivo existe
            if (!Storage::disk('public')->exists($cleanPath)) {
                throw new \Exception('La imagen no existe en la ruta especificada');
            }
            
            // Convertir imagen a base64
            $imageData = Storage::disk('public')->get($cleanPath);
            $base64Image = base64_encode($imageData);
            
            // Detectar MIME type de forma más robusta
            $extension = pathinfo($cleanPath, PATHINFO_EXTENSION);
            $mimeType = $this->getMimeTypeFromExtension($extension);
            
            if (!$mimeType) {
                throw new \Exception('Formato de imagen no soportado: ' . $extension);
            }
            
            // Construir URL de la API
            $url = $this->apiUrl . $this->model . ':generateContent?key=' . $this->apiKey;
            
            // Hacer la petición a Gemini
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'topK' => 32,
                    'topP' => 1,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                throw new \Exception('Error al comunicarse con Gemini API: ' . $response->body());
            }

            $result = $response->json();
            
            // Extraer el texto de la respuesta
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
            
            if (!$text) {
                throw new \Exception('No se recibió respuesta de la IA');
            }

            // Limpiar el texto para obtener solo el JSON
            $text = trim($text);
            $text = preg_replace('/```json\s*/', '', $text);
            $text = preg_replace('/```\s*$/', '', $text);
            $text = trim($text);
            
            // Decodificar JSON
            $analysis = json_decode($text, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error', [
                    'text' => $text,
                    'error' => json_last_error_msg()
                ]);
                throw new \Exception('Error al procesar la respuesta de la IA');
            }

            return [
                'success' => true,
                'data' => $analysis,
                'raw_response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('AI Vision Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener MIME type desde la extensión del archivo
     */
    protected function getMimeTypeFromExtension(string $extension): ?string
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
        ];
        
        return $mimeTypes[strtolower($extension)] ?? null;
    }
}
