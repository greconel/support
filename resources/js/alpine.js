import intersect from '@alpinejs/intersect'

// Livewire 3 auto-starts Alpine — register plugins before it starts
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(intersect)
})
