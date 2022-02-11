{{-- view/component/tests/app.blade.php用コンポーネント--}}
<x-tests.app>
    {{-- view/component/tests/app.blade.php用header--}}
    <x-slot name="header">
        コンポーネントテスト1Heaeder
    </x-slot>
    コンポーネントテスト1

    <x-tests.card  title="Component_test1" content="Component1の本文"/>
</x-tests.app>
