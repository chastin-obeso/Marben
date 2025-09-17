<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class roundbutton extends Component
{
    public $type;
    public $id;
    public $value;
    public $onclick;
    public $text;
    public $variant;
    public $addclass;
    /**
     * Create a new component instance.
     */
    public function __construct($type, $id, $value, $onclick, $text, $variant, $addclass)
    {
        $this->type = $type;
        $this->id = $id;
        $this->value = $value;
        $this->onclick = $onclick;
        $this->variant = $variant;
        $this->text = $text;
        $this->addclass = $addclass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.roundbutton');
    }
}
