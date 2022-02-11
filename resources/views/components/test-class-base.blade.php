{{-- view/component/test-class-component,
app/view/component/testClassBaseからの読み込み --}}
<div class="border-2 shadow-md w-1/4 p-2 bg-cyan-300">
    クラスベースのコンポーネントテスト

    {{-- Default値と変更値の確認 --}}
    <div class="border-2 shadow-md  p-2 bg-teal-100">{{ $classBaseMessage }}</div>
    <div class="border-2 shadow-md  p-2 bg-violet-300">{{ $defaultMessage }}</div>
</div>
