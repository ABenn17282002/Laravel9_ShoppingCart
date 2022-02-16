<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     * Admin用component_レンダー
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.admin');
    }
}
