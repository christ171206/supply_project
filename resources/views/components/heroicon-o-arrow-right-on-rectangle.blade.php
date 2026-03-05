@props([ 'class' => 'w-5 h-5' ])

<svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <polyline points="9 3 5 3 5 19 19 19 19 15"></polyline><polyline points="16 16 21 11 16 6"></polyline>
</svg>