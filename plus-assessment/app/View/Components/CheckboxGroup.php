<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckboxGroup extends Component
{
    public $options;
    public $name;
    public $checked;

    public function __construct($options, $name, $checked)
    {
        $this->options = $options;
        $this->name = $name;
        $this->checked = $checked;
    }

    public function render()
    {
        return view('components.checkbox-group');
    }
}
