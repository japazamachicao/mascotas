@props([
    'level' => 'bronce',
    'size' => 'md',
    'showText' => true
])

@php
    $level = strtolower($level);
    
    $sizes = [
        'xs' => [
            'icon' => 'w-3.5 h-3.5',
            'text' => 'text-[10px] px-1.5 py-0.5 gap-1',
        ],
        'sm' => [
            'icon' => 'w-4 h-4',
            'text' => 'text-xs px-2 py-0.5 gap-1.5',
        ],
        'md' => [
            'icon' => 'w-5 h-5',
            'text' => 'text-sm px-3 py-1 gap-2',
        ],
        'lg' => [
            'icon' => 'w-6 h-6',
            'text' => 'text-base px-4 py-1.5 gap-2.5',
        ],
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];

    $badgeClasses = [
        'bronce' => 'bg-amber-50 text-amber-700 border-amber-200/70 shadow-xs',
        'plata' => 'bg-slate-50 text-slate-700 border-slate-200/70 shadow-xs',
        'oro' => 'bg-yellow-50 text-yellow-700 border-yellow-200/70 shadow-xs',
        'diamante' => 'bg-cyan-50 text-cyan-700 border-cyan-200/70 shadow-xs',
    ][$level] ?? 'bg-gray-50 text-gray-700 border-gray-200';
@endphp

<span class="inline-flex items-center font-bold border rounded-full {{ $badgeClasses }} {{ $sizeConfig['text'] }} {{ $attributes->get('class') }}">
    @if($level === 'bronce')
        <!-- Medalla Bronce SVG Premium -->
        <svg class="{{ $sizeConfig['icon'] }} shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="bronze-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#CD7F32" />
                    <stop offset="50%" stop-color="#B87333" />
                    <stop offset="100%" stop-color="#8C5220" />
                </linearGradient>
            </defs>
            <circle cx="12" cy="12" r="10" fill="url(#bronze-grad)" stroke="#A0522D" stroke-width="1.5" />
            <circle cx="12" cy="12" r="7" fill="none" stroke="#FFE4D0" stroke-width="1" stroke-dasharray="2 2" />
            <path d="M12 7.5L13.2 11.2H17L14 13.5L15.2 17.2L12 15L8.8 17.2L10 13.5L7 11.2H10.8L12 7.5Z" fill="#FFE4D0" />
        </svg>
    @elseif($level === 'plata')
        <!-- Medalla Plata SVG Premium -->
        <svg class="{{ $sizeConfig['icon'] }} shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="silver-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#E6E6E6" />
                    <stop offset="50%" stop-color="#CCCCCC" />
                    <stop offset="100%" stop-color="#999999" />
                </linearGradient>
            </defs>
            <circle cx="12" cy="12" r="10" fill="url(#silver-grad)" stroke="#7F7F7F" stroke-width="1.5" />
            <circle cx="12" cy="12" r="7" fill="none" stroke="#FFFFFF" stroke-width="1" stroke-dasharray="2 2" />
            <path d="M12 7.5L13.2 11.2H17L14 13.5L15.2 17.2L12 15L8.8 17.2L10 13.5L7 11.2H10.8L12 7.5Z" fill="#FFFFFF" />
        </svg>
    @elseif($level === 'oro')
        <!-- Medalla Oro SVG Premium -->
        <svg class="{{ $sizeConfig['icon'] }} shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="gold-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFE066" />
                    <stop offset="50%" stop-color="#F5B800" />
                    <stop offset="100%" stop-color="#C29200" />
                </linearGradient>
            </defs>
            <circle cx="12" cy="12" r="10" fill="url(#gold-grad)" stroke="#B38600" stroke-width="1.5" />
            <circle cx="12" cy="12" r="7" fill="none" stroke="#FFFFE0" stroke-width="1" stroke-dasharray="2 2" />
            <path d="M12 7.5L13.2 11.2H17L14 13.5L15.2 17.2L12 15L8.8 17.2L10 13.5L7 11.2H10.8L12 7.5Z" fill="#FFFFE0" />
        </svg>
    @elseif($level === 'diamante')
        <!-- Diamante SVG Premium -->
        <svg class="{{ $sizeConfig['icon'] }} shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="diamond-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#E0F7FA" />
                    <stop offset="50%" stop-color="#4DD0E1" />
                    <stop offset="100%" stop-color="#00ACC1" />
                </linearGradient>
            </defs>
            <path d="M12 2L20 8L12 22L4 8L12 2Z" fill="url(#diamond-grad)" stroke="#00838F" stroke-width="1.5" stroke-linejoin="round" />
            <path d="M12 2L8 8L12 22L16 8L12 2Z" fill="none" stroke="#E0F7FA" stroke-width="1" stroke-linejoin="round" />
            <path d="M4 8H20" fill="none" stroke="#E0F7FA" stroke-width="1" />
        </svg>
    @endif

    @if($showText)
        <span>
            {{ [
                'bronce' => 'Bronce',
                'plata' => 'Plata',
                'oro' => 'Oro',
                'diamante' => 'Diamante'
            ][$level] ?? 'Socio' }}
        </span>
    @endif
</span>
