<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;

class SpecialsComponent extends Component
{
    public $specials;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->specials = Category::where('name', 'Specials')->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.specials-component');
    }
}
