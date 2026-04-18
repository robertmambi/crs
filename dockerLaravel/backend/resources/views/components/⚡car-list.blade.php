<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div style="padding:20px;">

    <h1>Available Cars</h1>

    <input 
        type="text" 
        wire:model.live="search" 
        placeholder="Search cars..." 
        style="padding:8px; width:300px; margin-bottom:20px;"
    />

    @forelse ($cars as $car)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong>{{ $car->brand }} {{ $car->model }}</strong><br>
            Price: ${{ $car->price_per_day }} / day<br>
            Transmission: {{ $car->transmission }}<br>
            Fuel: {{ $car->fuel_type }}
        </div>
    @empty
        <p>No cars found.</p>
    @endforelse

</div>