@props([ 'class' => 'w-5 h-5' ])

<svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <line x1="3" y1="21" x2="21" y2="21"></line><path d="M3 7v1a1 1 0 0 0 1 1h4V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h4a1 1 0 0 0 1-1V7"></path>
</svg>