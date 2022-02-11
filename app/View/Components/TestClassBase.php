<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TestClassBase extends Component
{
    // public変数(制限なし)
    public $classBaseMessage;
    public $defaultMessage;

    /**
     * Create a new component instance.
     *<変数の設定>
     * @return void
     */
    public function __construct($classBaseMessage,$defaultMessage="初期値です")
    {
        // Instanceからオブジェクトの変数にアクセスし、変数化
        $this->classBaseMessage = $classBaseMessage;
        $this->defaultMessage = $defaultMessage;
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
