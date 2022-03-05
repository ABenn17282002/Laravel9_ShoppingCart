<div>
    {{-- 画像の取得コンポーネント --}}
    @if(empty($shop->filename))
        <img src="{{ asset('images/no_image.jpg')}}">
    @else
        <img src="{{ asset('storage/shops/' . $shop->filename)}}">
    @endif
</div>
