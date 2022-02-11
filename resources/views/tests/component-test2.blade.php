
{{-- view/component/tests/app.blade.php用コンポーネント--}}
<x-tests.app>
    {{-- view/component/tests/app.blade.php用header--}}
    <x-slot name="header">
        コンポーネントテスト2Heaeder
    </x-slot>
    コンポーネントテスト2

    {{-- components/tests/card.blade.phpからの呼び出し --}}
    <x-tests.card  title="Component2_title1" content="Component2の本文" :messages="$messages"/>

    {{-- components/tests/card.blade.php -@props([])から呼び出し--}}
    <x-tests.card  title="Component2_title2"/>

    {{-- 属性:CSSの設定 --}}
    <x-tests.card  title="CSSの変更" class="bg-green-400" />
</x-tests.app>
