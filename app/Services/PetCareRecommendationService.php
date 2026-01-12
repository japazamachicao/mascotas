<?php

namespace App\Services;

class PetCareRecommendationService
{
    /**
     * Genera un plan de cuidado completo para la mascota
     */
    public function generateCompleteCareplan(array $petData): array
    {
        $species = $petData['species'] ?? 'Perro';
        $breeds = $petData['detected_breeds'] ?? [];
        $weight = $petData['weight'] ?? 0;
        $ageMonths = $petData['age_months'] ?? 24;
        $energyLevel = $petData['energy_level'] ?? 'media';
        
        return [
            'nutrition' => $this->getNutritionPlan($breeds, $weight, $ageMonths),
            'exercise' => $this->getExercisePlan($species, $breeds, $ageMonths, $energyLevel),
            'grooming' => $this->getGroomingPlan($species, $breeds),
            'health' => $this->getHealthCarePlan($ageMonths),
            'training' => $this->getTrainingRecommendations($species, $ageMonths),
            'socialization' => $this->getSocializationTips($ageMonths),
            'special_care' => $this->getSpecialCare($breeds, $ageMonths),
        ];
    }
    
    /**
     * Plan de nutrición
     */
    protected function getNutritionPlan(array $breeds, float $weight, int $ageMonths): array
    {
        $dietService = new DietRecommendationService();
        $recommendations = $dietService->generateRecommendations($breeds, $weight, $ageMonths);
        
        return [
            'daily_calories' => $recommendations['calories']['daily_calories'],
            'meals_per_day' => $recommendations['feeding_recommendations']['meals_per_day'],
            'schedule' => $recommendations['feeding_recommendations']['schedule'],
            'water_intake' => $recommendations['feeding_recommendations']['water'],
            'protein_min' => $recommendations['nutrients']['protein']['min_percentage'] . '%',
            'fat_min' => $recommendations['nutrients']['fat']['min_percentage'] . '%',
            'food_type' => $this->recommendFoodType($ageMonths, $weight),
            'treats' => 'Máximo 10% de calorías diarias',
            'avoid_foods' => $this->getFoodsToAvoid(),
        ];
    }
    
    /**
     * Plan de ejercicio y paseos
     */
    protected function getExercisePlan(string $species, array $breeds, int $ageMonths, string $energyLevel): array
    {
        if ($species !== 'Perro') {
            return [
                'type' => 'Juego en casa',
                'duration' => '15-30 minutos al día',
                'frequency' => '2-3 sesiones',
                'activities' => ['Juguetes interactivos', 'Rascadores', 'Escondite'],
                'notes' => 'Los gatos necesitan actividad mental más que física intensa',
            ];
        }
        
        // Para perros
        switch ($energyLevel) {
            case 'baja':
                $baseMinutes = 30;
                break;
            case 'alta':
                $baseMinutes = 120;
                break;
            case 'media':
            default:
                $baseMinutes = 60;
                break;
        }
        
        // Ajustar por edad
        if ($ageMonths < 6) {
            $baseMinutes = (int)($baseMinutes * 0.5); // Cachorros: menos tiempo
        } elseif ($ageMonths > 84) {
            $baseMinutes = (int)($baseMinutes * 0.7); // Seniors: menos intensidad
        }
        
        $activities = $this->getActivitiesByBreed($breeds);
        
        return [
            'daily_walks' => $energyLevel === 'alta' ? '3-4 paseos' : '2-3 paseos',
            'duration_per_walk' => round($baseMinutes / 2) . ' minutos',
            'total_daily' => $baseMinutes . ' minutos mínimo',
            'intensity' => $this->getIntensityLevel($energyLevel),
            'recommended_activities' => $activities,
            'avoid' => $this->getExerciseToAvoid($ageMonths),
            'best_times' => ['Temprano en la mañana', 'Tarde (antes de oscurecer)'],
        ];
    }
    
    /**
     * Plan de baños y grooming
     */
    protected function getGroomingPlan(string $species, array $breeds): array
    {
        $coatType = $this->determineCoatType($breeds);
        
        switch ($coatType) {
            case 'short':
            case 'wire':
                $bathFrequency = 'Cada 2-3 meses';
                break;
            case 'long':
                $bathFrequency = 'Cada 3-4 semanas';
                break;
            case 'curly':
                $bathFrequency = 'Cada 4-6 semanas';
                break;
            case 'medium':
            default:
                $bathFrequency = 'Cada 1-2 meses';
                break;
        }
        
        switch ($coatType) {
            case 'short':
            case 'wire':
                $brushingFrequency = 'Semanal';
                break;
            case 'long':
            case 'curly':
                $brushingFrequency = 'Diario';
                break;
            case 'medium':
            default:
                $brushingFrequency = '2-3 veces por semana';
                break;
        }
        
        return [
            'baths' => [
                'frequency' => $bathFrequency,
                'shampoo_type' => $this->recommendShampoo($species),
                'water_temperature' => 'Tibia (no caliente)',
                'tips' => ['Secar completamente', 'Revisar orejas después'],
            ],
            'brushing' => [
                'frequency' => $brushingFrequency,
                'brush_type' => $this->recommendBrushType($coatType),
                'benefits' => ['Reduce muda', 'Previene nudos', 'Estimula piel'],
            ],
            'nails' => [
                'frequency' => 'Cada 3-4 semanas',
                'method' => 'Cortauñas o lima',
                'signs_needed' => ['Hacen ruido al caminar', 'Están curvadas'],
            ],
            'teeth' => [
                'brushing' => '3-4 veces por semana (ideal: diario)',
                'dental_treats' => 'Diario',
                'professional_cleaning' => 'Anual',
            ],
            'ears' => [
                'cleaning' => 'Semanal o según necesidad',
                'check_for' => ['Mal olor', 'Enrojecimiento', 'Secreción'],
            ],
        ];
    }
    
    /**
     * Plan de salud preventiva
     */
    protected function getHealthCarePlan(int $ageMonths): array
    {
        $vaccineSchedule = $this->getVaccineSchedule($ageMonths);
        $checkupFrequency = $ageMonths < 12 || $ageMonths > 84 ? 'Cada 6 meses' : 'Anual';
        
        return [
            'vaccines' => $vaccineSchedule,
            'deworming' => [
                'puppies' => 'Mensual hasta 6 meses',
                'adults' => 'Cada 3-6 meses',
                'types' => ['Interno', 'Externo (pulgas/garrapatas)'],
            ],
            'checkups' => [
                'frequency' => $checkupFrequency,
                'includes' => ['Examen físico', 'Peso', 'Vacunas al día'],
            ],
            'preventive' => [
                'Antipulgas mensual',
                'Antigarrapatas (según zona)',
                'Chequeo dental anual',
                'Análisis de sangre (seniors >7 años)',
            ],
            'signs_to_watch' => [
                'Pérdida de apetito prolongada',
                'Letargo inusual',
                'Vómitos/diarrea persistentes',
                'Cambios en comportamiento',
                'Dificultad para respirar',
            ],
        ];
    }
    
    /**
     * Recomendaciones de entrenamiento
     */
    protected function getTrainingRecommendations(string $species, int $ageMonths): array
    {
        if ($species !== 'Perro') {
            return [
                'message' => 'Los gatos aprenden mejor con refuerzo positivo',
                'basics' => ['Usar arenero', 'Usar rascador', 'Venir al llamado'],
                'methods' => ['Premios', 'Clicker', 'Juego'],
                'avoid' => ['Castigos', 'Gritos', 'Forzar'],
            ];
        }
        
        $isPuppy = $ageMonths < 12;
        
        return [
            'basic_commands' => $isPuppy 
                ? ['Sentado', 'Quieto', 'Ven', 'Junto']
                : ['Sentado', 'Quieto', 'Ven', 'Junto', 'Echado', 'Suelta'],
            'house_training' => [
                'method' => 'Rutina consistente',
                'frequency' => $isPuppy ? 'Cada 2-3 horas' : 'Mañana, mediodía, tarde, noche',
                'reward' => 'Inmediatamente después de hacer fuera',
            ],
            'socialization' => [
                'age' => '3-14 semanas ideal',
                'expose_to' => ['Personas', 'Otros perros', 'Ruidos', 'Lugares nuevos'],
                'frequency' => 'Experiencias positivas diarias',
            ],
            'training_tips' => [
                'Sesiones cortas (5-10 minutos)',
                'Refuerzo positivo siempre',
                'Consistencia es clave',
                'Paciencia',
            ],
            'common_issues' => $this->getCommonBehaviorIssues($ageMonths),
        ];
    }
    
    /**
     * Tips de socialización
     */
    protected function getSocializationTips(int $ageMonths): array
    {
        $isPuppy = $ageMonths < 12;
        
        return [
            'importance' => $isPuppy 
                ? 'CRÍTICO: Ventana de socialización hasta 4 meses'
                : 'Mantener socialización continua',
            'with_people' => [
                'Adultos de diferentes edades',
                'Niños (supervisado)',
                'Personas con sombreros/uniformes',
                'Diferentes etnias',
            ],
            'with_animals' => [
                'Perros vacunados y amigables',
                'Gatos (si aplica)',
                'Animales pequeños (con supervisión)',
            ],
            'environments' => [
                'Parques',
                'Calles transitadas',
                'Tiendas pet-friendly',
                'Auto/transporte',
            ],
            'sounds' => [
                'Aspiradora',
                'Tráfico',
                'Tormentas (grabaciones)',
                'Multitudes',
            ],
            'tips' => [
                'Siempre en experiencias positivas',
                'No forzar si tiene miedo',
                'Premiar comportamiento tranquilo',
                'Gradualmente aumentar exposición',
            ],
        ];
    }
    
    /**
     * Cuidados especiales
     */
    protected function getSpecialCare(array $breeds, int $ageMonths): array
    {
        $specialNeeds = [];
        
        // Por edad
        if ($ageMonths < 6) {
            $specialNeeds['puppy_care'] = [
                'No ejercicio intenso (huesos en crecimiento)',
                'Evitar escaleras',
                'Supervisión constante',
                'Muchas siestas (18-20 horas/día)',
            ];
        } elseif ($ageMonths > 84) {
            $specialNeeds['senior_care'] = [
                'Chequeos veterinarios más frecuentes',
                'Dieta senior (menos calorías)',
                'Suplementos para articulaciones',
                'Rampas en lugar de escaleras',
                'Cama ortopédica',
            ];
        }
        
        // Por raza
        foreach ($breeds as $breed) {
            $breedName = $breed['name'] ?? '';
            
            if (str_contains(strtolower($breedName), 'bulldog')) {
                $specialNeeds['breed_specific'][] = 'Cuidado con el calor (problemas respiratorios)';
                $specialNeeds['breed_specific'][] = 'Limpiar pliegues faciales diariamente';
            }
            
            if (str_contains(strtolower($breedName), 'golden') || str_contains(strtolower($breedName), 'labrador')) {
                $specialNeeds['breed_specific'][] = 'Propenso a obesidad - controlar peso';
                $specialNeeds['breed_specific'][] = 'Necesita natación/ejercicio acuático';
            }
            
            if (str_contains(strtolower($breedName), 'pastor alemán')) {
                $specialNeeds['breed_specific'][] = 'Suplementos para articulaciones (displasia)';
                $specialNeeds['breed_specific'][] = 'Estimulación mental importante';
            }
        }
        
        return $specialNeeds;
    }
    
    // Métodos auxiliares
    
    protected function recommendFoodType(int $ageMonths, float $weight): string
    {
        if ($ageMonths < 12) return 'Alimento para cachorros (Puppy)';
        if ($ageMonths > 84) return 'Alimento para seniors';
        if ($weight < 10) return 'Alimento para razas pequeñas';
        if ($weight > 25) return 'Alimento para razas grandes';
        return 'Alimento para adultos';
    }
    
    protected function getFoodsToAvoid(): array
    {
        return [
            'Chocolate',
            'Uvas y pasas',
            'Cebolla y ajo',
            'Aguacate',
            'Alcohol',
            'Café/cafeína',
            'Huesos cocidos',
            'Productos con xilitol',
        ];
    }
    
    protected function getActivitiesByBreed(array $breeds): array
    {
        $activities = ['Caminar', 'Jugar a la pelota', 'Juegos de olfato'];
        
        foreach ($breeds as $breed) {
            $breedName = strtolower($breed['name'] ?? '');
            
            if (str_contains($breedName, 'labrador') || str_contains($breedName, 'golden')) {
                $activities[] = 'Natación';
                $activities[] = 'Retrieve/Cobro';
            }
            
            if (str_contains($breedName, 'border') || str_contains($breedName, 'pastor')) {
                $activities[] = 'Agility';
                $activities[] = 'Frisbee';
            }
            
            if (str_contains($breedName, 'beagle') || str_contains($breedName, 'terrier')) {
                $activities[] = 'Rastreo';
                $activities[] = 'Juegos de olfato';
            }
        }
        
        return array_unique($activities);
    }
    
    protected function getIntensityLevel(string $energyLevel): string
    {
        switch ($energyLevel) {
            case 'baja':
                return 'Baja - Paseos tranquilos';
            case 'alta':
                return 'Alta - Correr, juegos intensos';
            case 'media':
            default:
                return 'Moderada - Caminar y jugar';
        }
    }
    
    protected function getExerciseToAvoid(int $ageMonths): array
    {
        if ($ageMonths < 12) {
            return ['Saltos altos', 'Carreras largas', 'Ejercicio intenso'];
        }
        
        if ($ageMonths > 84) {
            return ['Impacto alto', 'Calor extremo', 'Sobre-ejercicio'];
        }
        
        return ['Ejercicio en clima extremo'];
    }
    
    protected function determineCoatType(array $breeds): string
    {
        if (empty($breeds)) return 'short';
        
        $breedName = strtolower($breeds[0]['name'] ?? '');
        
        if (str_contains($breedName, 'poodle') || str_contains($breedName, 'bichon')) return 'curly';
        if (str_contains($breedName, 'golden') || str_contains($breedName, 'collie')) return 'long';
        if (str_contains($breedName, 'terrier')) return 'wire';
        if (str_contains($breedName, 'labrador') || str_contains($breedName, 'beagle')) return 'short';
        
        return 'medium';
    }
    
    protected function recommendShampoo(string $species): string
    {
        return $species === 'Gato' 
            ? 'Shampoo específico para gatos (pH diferente)'
            : 'Shampoo hipoalergénico para perros';
    }
    
    protected function recommendBrushType(string $coatType): string
    {
        switch ($coatType) {
            case 'short':
                return 'Cepillo de goma o guante';
            case 'long':
                return 'Cepillo slicker + peine metálico';
            case 'wire':
                return 'Cepillo de cerdas duras';
            case 'curly':
                return 'Cepillo slicker + peine';
            case 'medium':
            default:
                return 'Cepillo de cerdas';
        }
    }
    
    protected function getVaccineSchedule(int $ageMonths): array
    {
        if ($ageMonths < 12) {
            return [
                '6-8 semanas' => 'Primera múltiple (parvo, moquillo, etc.)',
                '10-12 semanas' => 'Segunda múltiple',
                '14-16 semanas' => 'Tercera múltiple + rabia',
                'Anual' => 'Refuerzos',
            ];
        }
        
        return [
            'Anual' => 'Múltiple (parvo, moquillo, etc.)',
            'Cada 1-3 años' => 'Rabia (según ley local)',
            'Opcional' => 'Tos de las perreras, Leptospirosis',
        ];
    }
    
    protected function getCommonBehaviorIssues(int $ageMonths): array
    {
        if ($ageMonths < 12) {
            return [
                'Morder' => 'Ofrecer juguetes para morder',
                'Saltar' => 'No premiar con atención',
                'Llorar de noche' => 'Rutina de sueño consistente',
            ];
        }
        
        return [
            'Ladrido excesivo' => 'Identificar causa, entrenamiento',
            'Ansiedad por separación' => 'Desensibilización gradual',
            'Tirar de la correa' => 'Entrenamiento de "junto"',
        ];
    }
}
