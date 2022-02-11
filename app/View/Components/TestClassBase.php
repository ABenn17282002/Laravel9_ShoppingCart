<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TestClassBase extends Component
{
    /**
     * Create a new component instance.
     *<変数の設定>
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     * <ページを返す関数>
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // component/tests/test-class-base-component.blade.phpに返す
        return view('components.test-class-base');
    }
}
