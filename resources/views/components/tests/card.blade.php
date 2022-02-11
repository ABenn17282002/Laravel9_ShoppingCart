{{-- 初期値の設定
　※ title,content,messageの3つがないとエラーが出ます --}}
@props([
    'title' => 'title初期値',
    'content' => '本文の初期値です。',
    'messages' => 'messageの初期値です。'
    ])


{{-- tests/component-test1・2.blade.php用コンポーネント
※ 元の属性+追加CSSの方法: {{ $attributes->merge([
'class' => ‘元の属性クラス’]) }}--}}
<div {{ $attributes ->merge([
    'class' => 'border-2 shadow-md w-1/4 p-2' ])
    }}>
    {{-- tests/component-test1・2.blade.php用コンポーネント --}}
    {{-- tailwindcss:border:2px,box-shadow-middlesize, width:1/4, padding:0.5rem
     ※ tailwindcssの反映には、npm run dev or prodコマンド必須！--}}
        <div>{{ $title }}</div>
        <div>画像</div>
        <div>{{ $content }}</div>
        <div>{{ $messages }}</div>
    </div>
