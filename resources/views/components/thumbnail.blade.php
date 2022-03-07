@php
/* php関数を使用しpathを変数化する */
// shopsの場合：'storage/shops/'
if($type ==="shops"){
    $path = 'storage/shops/';
}

// productsの場合：'storage/products/'
if($type === 'products'){
  $path = 'storage/products/';
}
@endphp

<div>
    {{-- 画像の取得コンポーネント --}}
    @if(empty($filename))
        <img src="{{ asset('images/no_image.jpg')}}">
    @else
        <img src="{{ asset('storage/shops/' . $filename)}}">
    @endif
</div>
