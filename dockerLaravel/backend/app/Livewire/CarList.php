namespace App\Livewire;
<?php
use Livewire\Component;
use App\Models\Car;

class CarList extends Component
{
    public $search = '';

    public function render()
    {
        $cars = Car::query()
            ->when($this->search, fn ($query) =>
                $query->where('brand', 'like', "%{$this->search}%")
                      ->orWhere('model', 'like', "%{$this->search}%")
            )
            ->where('status', 'active')
            ->get();

        return view('livewire.car-list', [
            'cars' => $cars
        ]);
    }
}