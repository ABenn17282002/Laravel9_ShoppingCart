{{-- view/component/tests/app.blade.php用コンポーネント--}}
<x-tests.app>
    {{-- view/component/tests/app.blade.php用header--}}
    <x-slot name="header">
        コンポーネントテスト1Heaeder
    </x-slot>
    コンポーネントテスト1

    {{-- 変数を渡す場合は:プロパティ名="$変数" --}}
    {{-- components/tests/card.blade.phpからの呼び出し --}}
    <x-tests.card  title="Component_title1" content="Component1の本文" :messages="$messages"/>

    {{-- components/tests/card.blade.php -@props([])から呼び出し--}}
    <x-tests.card  title="Component1_title2" />
</x-tests.app>
