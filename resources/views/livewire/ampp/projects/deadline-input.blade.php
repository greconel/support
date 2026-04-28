<div>
<x-forms.input
    name="deadline"
    type="date"
    wire:model="deadline"
    min="{{$today}}"
    style="max-width: 130px; background-color: {{$inputBackground}}"
/>
</div>
