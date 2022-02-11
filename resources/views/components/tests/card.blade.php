<div class="border-2 shadow-md w-1/4 p-2">
    {{-- tests/component-test1・2.blade.php用コンポーネント --}}
    {{-- tailwindcss:border:2px,box-shadow-middlesize, width:1/4, padding:0.5rem
     ※ tailwindcssの反映には、npm run dev or prodコマンド必須！--}}
        <div>{{ $title }}</div>
        <div>画像</div>
        <div>{{ $content }}</div>
        <div>{{ $messages }}</div>
    </div>
