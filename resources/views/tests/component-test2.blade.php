
{{-- view/component/tests/app.blade.php用コンポーネント--}}
<x-tests.app>
    {{-- view/component/tests/app.blade.php用header--}}
    <x-slot name="header">
        コンポーネントテスト2Heaeder
    </x-slot>
    コンポーネントテスト2

    <x-tests.card  title="Component2" content="Component2の本文" :messages="$messages"/>
</x-tests.app>
