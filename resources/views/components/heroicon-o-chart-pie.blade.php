@props([ 'class' => 'w-5 h-5' ])

<svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8V2"></path>
</svg>