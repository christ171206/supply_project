@props(['class' => 'w-5 h-5'])

<svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="7" cy="4" r="1"></circle>
  <path d="M7 4v9a5 5 0 1 0 10 0v-9"></path>
  <rect x="3" y="7" width="18" height="14" rx="2" ry="2"></rect>
</svg>
