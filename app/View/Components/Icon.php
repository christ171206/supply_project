<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    public string $name;
    public string $class;

    public function __construct(string $name, string $class = 'w-5 h-5')
    {
        $this->name = $name;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.icon');
    }
}
