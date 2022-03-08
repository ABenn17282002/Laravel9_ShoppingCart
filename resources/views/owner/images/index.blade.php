<x-app-layout>
    {{-- Owner/shop/index.blade.php引用(一部画像用に編集) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            オーナー用ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- flassmessageの表示 --}}
                    <x-flash-message/>
                    {{-- 画像新規作成 --}}
                    <div class="flex justify-end mb-4">
                        <button onclick="location.href='{{ route('owner.images.create')}}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                      </div>
                    {{-- foreach構文でshops_tableからデータを取得 --}}
                    @foreach ($images as $image)
                    {{-- width:1/2→1/4に変更 --}}
                        <div class="w-1/4 p-4">
                            {{-- shop_id取得→編集ページ --}}
                            <a href="{{ route('owner.images.edit', ['image' => $image->id ])}}">
                                <div class="border rounded-md p-4">
                                    {{-- image_title名 --}}
                                    <div class="text-xl">
                                        {{ $image ->title }}
                                    </div>
                                    {{-- コンポーネントより画像の取得
                                    :filename="$shop->filename"=>UpLoad画像取得  製品画像--}}
                                    <x-thumbnail :filename="$shop->filename" type="products" />
                                </div>
                            </a>
                        </div>
                    @endforeach
                    {{-- ページネーション --}}
                    {{ $images->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
