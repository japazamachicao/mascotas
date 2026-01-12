<?php

namespace App\Services;

class DietRecommendationService
{
    /**
     * Base de datos de requerimientos nutricionales por raza
     * Valores en kcal por kg de peso corporal por día
     */
    protected array $breedNutrition = [
        // Razas pequeñas (alta tasa metabólica)
        'Chihuahua' => ['calories_per_kg' => 60, 'protein_min' => 25, 'fat_min' => 15],
        'Yorkshire Terrier' => ['calories_per_kg' => 55, 'protein_min' => 25, 'fat_min' => 15],
        'Pomeranian' => ['calories_per_kg' => 55, 'protein_min' => 25, 'fat_min' => 15],
        'Maltés' => ['calories_per_kg' => 55, 'protein_min' => 24, 'fat_min' => 14],
        'Shih Tzu' => ['calories_per_kg' => 50, 'protein_min' => 24, 'fat_min' => 14],
        
        // Razas medianas
        'Beagle' => ['calories_per_kg' => 45, 'protein_min' => 23, 'fat_min' => 12],
        'Cocker Spaniel' => ['calories_per_kg' => 45, 'protein_min' => 23, 'fat_min' => 12],
        'Bulldog' => ['calories_per_kg' => 40, 'protein_min' => 22, 'fat_min' => 12],
        'Border Collie' => ['calories_per_kg' => 50, 'protein_min' => 25, 'fat_min' => 15],
        
        // Razas grandes
        'Labrador Retriever' => ['calories_per_kg' => 40, 'protein_min' => 22, 'fat_min' => 12],
        'Golden Retriever' => ['calories_per_kg' => 40, 'protein_min' => 22, 'fat_min' => 12],
        'Pastor Alemán' => ['calories_per_kg' => 42, 'protein_min' => 24, 'fat_min' => 14],
        'Rottweiler' => ['calories_per_kg' => 38, 'protein_min' => 24, 'fat_min' => 14],
        
        // Razas gigantes (menor tasa metabólica)
        'Gran Danés' => ['calories_per_kg' => 35, 'protein_min' => 23, 'fat_min' => 13],
        'Mastín' => ['calories_per_kg' => 35, 'protein_min' => 23, 'fat_min' => 13],
        'San Bernardo' => ['calories_per_kg' => 35, 'protein_min' => 23, 'fat_min' => 13],
        
        // Gatos (diferentes necesidades)
        'Persa' => ['calories_per_kg' => 50, 'protein_min' => 30, 'fat_min' => 15],
        'Siamés' => ['calories_per_kg' => 55, 'protein_min' => 32, 'fat_min' => 16],
        'Maine Coon' => ['calories_per_kg' => 45, 'protein_min' => 30, 'fat_min' => 15],
        
        // Default para mestizos o razas no listadas
        'mixed_small' => ['calories_per_kg' => 55, 'protein_min' => 24, 'fat_min' => 14],
        'mixed_medium' => ['calories_per_kg' => 45, 'protein_min' => 23, 'fat_min' => 12],
        'mixed_large' => ['calories_per_kg' => 40, 'protein_min' => 22, 'fat_min' => 12],
        'mixed_giant' => ['calories_per_kg' => 35, 'protein_min' => 23, 'fat_min' => 13],
    ];

    /**
     * Calcula las calorías diarias necesarias
     */
    public function calculateCalories(array $breeds, float $weight, int $ageMonths, string $activityLevel = 'moderate'): array
    {
        $baseCalories = 0;
        
        if (empty($breeds)) {
            // Default basado en peso
            $caloriesPerKg = $this->getDefaultCaloriesPerKg($weight);
            $baseCalories = $weight * $caloriesPerKg;
        } else {
            // Calcular basado en razas detectadas
            foreach ($breeds as $breed) {
                $breedName = $breed['name'] ?? '';
                $percentage = ($breed['percentage'] ?? 100) / 100;
                
                $nutrition = $this->breedNutrition[$breedName] ?? $this->getDefaultNutrition($weight);
                $baseCalories += ($weight * $nutrition['calories_per_kg']) * $percentage;
            }
        }
        
        // Ajustar por edad
        $ageMultiplier = $this->getAgeMultiplier($ageMonths);
        $baseCalories *= $ageMultiplier;
        
        // Ajustar por nivel de actividad
        $activityMultiplier = $this->getActivityMultiplier($activityLevel);
        $dailyCalories = $baseCalories * $activityMultiplier;
        
        return [
            'daily_calories' => round($dailyCalories),
            'per_meal' => round($dailyCalories / 2), // Asumiendo 2 comidas al día
            'base_calories' => round($baseCalories),
            'activity_level' => $activityLevel,
        ];
    }

    /**
     * Recomienda nutrientes específicos
     */
    public function recommendNutrients(array $breeds, float $weight): array
    {
        if (empty($breeds)) {
            $nutrition = $this->getDefaultNutrition($weight);
        } else {
            $totalProtein = 0;
            $totalFat = 0;
            
            foreach ($breeds as $breed) {
                $breedName = $breed['name'] ?? '';
                $percentage = ($breed['percentage'] ?? 100) / 100;
                
                $nutrition = $this->breedNutrition[$breedName] ?? $this->getDefaultNutrition($weight);
                $totalProtein += $nutrition['protein_min'] * $percentage;
                $totalFat += $nutrition['fat_min'] * $percentage;
            }
            
            $nutrition = [
                'protein_min' => round($totalProtein, 1),
                'fat_min' => round($totalFat, 1),
            ];
        }
        
        return [
            'protein' => [
                'min_percentage' => $nutrition['protein_min'],
                'grams_per_day' => null, // Se puede calcular si se conocen las calorías
            ],
            'fat' => [
                'min_percentage' => $nutrition['fat_min'],
                'grams_per_day' => null,
            ],
            'carbohydrates' => [
                'max_percentage' => 100 - $nutrition['protein_min'] - $nutrition['fat_min'],
            ],
            'fiber' => [
                'recommended_percentage' => '3-5',
            ],
        ];
    }

    /**
     * Genera recomendaciones completas
     */
    public function generateRecommendations(array $breeds, float $weight, int $ageMonths, string $activityLevel = 'moderate'): array
    {
        $calories = $this->calculateCalories($breeds, $weight, $ageMonths, $activityLevel);
        $nutrients = $this->recommendNutrients($breeds, $weight);
        
        // Calcular gramos de nutrientes
        $dailyCalories = $calories['daily_calories'];
        $nutrients['protein']['grams_per_day'] = round(($dailyCalories * $nutrients['protein']['min_percentage'] / 100) / 4); // 4 kcal por gramo de proteína
        $nutrients['fat']['grams_per_day'] = round(($dailyCalories * $nutrients['fat']['min_percentage'] / 100) / 9); // 9 kcal por gramo de grasa
        
        return [
            'calories' => $calories,
            'nutrients' => $nutrients,
            'feeding_recommendations' => $this->getFeedingRecommendations($ageMonths, $weight),
            'special_considerations' => $this->getSpecialConsiderations($breeds),
        ];
    }

    protected function getDefaultCaloriesPerKg(float $weight): int
    {
        if ($weight < 5) return 55;
        if ($weight < 15) return 45;
        if ($weight < 30) return 40;
        return 35;
    }

    protected function getDefaultNutrition(float $weight): array
    {
        if ($weight < 5) return $this->breedNutrition['mixed_small'];
        if ($weight < 15) return $this->breedNutrition['mixed_medium'];
        if ($weight < 30) return $this->breedNutrition['mixed_large'];
        return $this->breedNutrition['mixed_giant'];
    }

    protected function getAgeMultiplier(int $ageMonths): float
    {
        if ($ageMonths < 4) return 2.0;  // Cachorro muy joven
        if ($ageMonths < 12) return 1.5; // Cachorro/joven
        if ($ageMonths < 84) return 1.0; // Adulto (hasta 7 años)
        return 0.9; // Senior
    }

    protected function getActivityMultiplier(string $level): float
    {
        switch ($level) {
            case 'sedentary':
                return 0.8;
            case 'light':
                return 0.9;
            case 'active':
                return 1.2;
            case 'very_active':
                return 1.4;
            case 'moderate':
            default:
                return 1.0;
        }
    }

    protected function getFeedingRecommendations(int $ageMonths, float $weight): array
    {
        $mealsPerDay = 2;
        if ($ageMonths < 6) $mealsPerDay = 3;
        if ($ageMonths < 3) $mealsPerDay = 4;
        
        return [
            'meals_per_day' => $mealsPerDay,
            'schedule' => $this->getSuggestedSchedule($mealsPerDay),
            'water' => round($weight * 50) . '-' . round($weight * 70) . ' ml por día',
        ];
    }

    protected function getSuggestedSchedule(int $meals): array
    {
        switch ($meals) {
            case 4:
                return ['7:00 AM', '12:00 PM', '5:00 PM', '9:00 PM'];
            case 3:
                return ['8:00 AM', '2:00 PM', '8:00 PM'];
            case 2:
            default:
                return ['8:00 AM', '6:00 PM'];
        }
    }

    protected function getSpecialConsiderations(array $breeds): array
    {
        $considerations = [];
        
        foreach ($breeds as $breed) {
            $breedName = $breed['name'] ?? '';
            
            // Agregar consideraciones especiales por raza
            if (str_contains(strtolower($breedName), 'bulldog')) {
                $considerations[] = 'Propenso a obesidad, controlar porciones cuidadosamente';
            }
            if (str_contains(strtolower($breedName), 'pastor alemán')) {
                $considerations[] = 'Necesita suplementos para articulaciones (glucosamina)';
            }
            if (str_contains(strtolower($breedName), 'labrador')) {
                $considerations[] = 'Alta tendencia a sobrepeso, medir porciones exactas';
            }
        }
        
        return array_unique($considerations);
    }
}
