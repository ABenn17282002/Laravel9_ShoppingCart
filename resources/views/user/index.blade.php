<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                商品一覧
            </h2>
            <div>
                <form method="get" action="{{ route('user.items.index')}}">
                    <div class="flex">
                        <div>
                            <span class="text-sm">表示順</span><br>
                            {{-- プルダウン内に定数化した定義を記載する --}}
                            <select id="sort" name="sort" class="mr-4">
                                <option value="{{ \Constant::SORT_ORDER['recommend']}}"
                                    @if(\Request::get('sort') === \Constant::SORT_ORDER['recommend'] )
                                    selected
                                    @endif>おすすめ順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['higherPrice']}}"
                                    @if(\Request::get('sort') === \Constant::SORT_ORDER['higherPrice'] )
                                    selected
                                    @endif>料金の高い順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['lowerPrice']}}"
                                    @if(\Request::get('sort') === \Constant::SORT_ORDER['lowerPrice'] )
                                    selected
                                    @endif>料金の安い順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['later']}}"
                                    @if(\Request::get('sort') === \Constant::SORT_ORDER['later'] )
                                    selected
                                    @endif>新しい順
                                </option>
                                <option value="{{ \Constant::SORT_ORDER['older']}}"
                                    @if(\Request::get('sort') === \Constant::SORT_ORDER['older'] )
                                    selected
                                    @endif>古い順
                                </option>
                            </select>
                        </div>
                        <div>表示件数</div>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap">

                        @foreach($products as $product)
                            <div class="w-1/4 p-2 md:p-4">
                                {{-- 製品IDを取得して、詳細ページへ　 --}}
                                <a href="{{ route('user.items.show', ['item' => $product->id ])}}">
                                        {{-- 画像が表示されなかったため一旦再修正 --}}
                                        <x-thumbnail filename="{{ $product->filename ?? ''}}" type="products" />
                                        {{-- 引用元:https://tailblocks.cc/ [Ecommerce] --}}
                                        <div class="mt-4">
                                            {{-- カテゴリー名 --}}
                                            <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ $product->category }}</h3>
                                            {{-- 製品名 --}}
                                            <h2 class="text-gray-900 title-font text-lg font-medium">{{ $product->name }}</h2>
                                            {{-- 価格(カンマを表示)--}}
                                            <p class="mt-1">{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span></p>
                                        </div>
                                    </a>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Script --}}
    <script>
        // sorttagの取得
        const select = document.getElementById('sort');
        // 取得時にフォーム発火
        select.addEventListener('change', function(){
            this.form.submit()
        });

    </script>
</x-app-layout>
