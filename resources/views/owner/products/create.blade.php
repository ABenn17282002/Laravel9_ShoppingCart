<x-app-layout>
    <x-slot name="header">
        {{-- owner/shops/edit.blade.phpを参考にprodcuts.create用に変更 --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            オーナー用ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Error_message --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    {{-- 商品登録用フォーム --}}
                    <form method="post" action="{{ route('owner.products.store') }}">
                            @csrf
                            <div class="-m-2">
                                {{-- 商品名 --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="title" class="leading-7 text-sm text-gray-600">商品名【必須】</label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                {{-- 商品情報 --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="information" class="leading-7 text-sm text-gray-600">商品情報 【必須】</label>
                                        <textarea id="information" name="information" rows="10" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('information') }}</textarea>
                                    </div>
                                </div>
                                {{-- 価格 --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="title" class="leading-7 text-sm text-gray-600">価格【必須】</label>
                                        <input type="number" id="price" name="price" value="{{ old('price') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                {{-- 表示順 --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="title" class="leading-7 text-sm text-gray-600">表示順</label>
                                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                {{-- 初期在庫 --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="title" class="leading-7 text-sm text-gray-600">初期在庫【必須】</label>
                                        <span class="text-sm">0〜99の範囲で入力してください</span>
                                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="shop_id" class="leading-7 text-sm text-gray-600">販売する店舗</label>
                                        {{-- shopsからforeach構文でid,name取得→Selecttagで選択肢にする --}}
                                        <select name="shop_id" id="shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            @foreach ($shops as $shop )
                                                <option value="{{ $shop->id}}" >
                                                    {{ $shop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- カテゴリー --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative">
                                        <label for="category" class="leading-7 text-sm text-gray-600">
                                            カテゴリー
                                        </label>
                                        <select name="category" id="category" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{-- Primary_Category取得 --}}
                                            @foreach($categories as $category)
                                                <optgroup label="{{ $category->name }}">
                                                    {{-- Secondary_Category idとnameを取得 --}}
                                                    @foreach($category->secondary as $secondary)
                                                        <option value="{{ $secondary->id}}" >
                                                            {{ $secondary->name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- コンポーネントより選択した画像を取得 --}}
                                <x-select-image :images="$images" name="image1" />
                                <x-select-image :images="$images" name="image2" />
                                <x-select-image :images="$images" name="image3" />
                                <x-select-image :images="$images" name="image4" />
                                {{-- 販売情報(販売中 or 販売停止) --}}
                                <div class="p-2 w-1/2 mx-auto">
                                    <div class="relative flex justify-around">
                                        <div><input type="radio" name="is_selling" value="1" class="mr-2" checked>販売中</div>
                                        <div><input type="radio" name="is_selling" value="0" class="mr-2" >停止中</div>
                                    </div>
                                </div>
                                <div class="p-2 w-full flex justify-around mt-4">
                                        {{-- 戻る --}}
                                        <button type="button" onclick="location.href='{{ route('owner.products.index')}}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                        {{-- 更新ボタン --}}
                                        <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict'
        // 全てのimagetagを取得
        const images=document.querySelectorAll('.image');

        // 取得したtagを1つずつ取得
        images.forEach(image => {
            // clickしたら発火
            image.addEventListener('click',function(e){
            /*-- Image_IDの取得--　*/
            //data-idの6文字の取得
            const imageName = e.target.dataset.id.substr(0, 6);
            // 6文字カット
            const imageId = e.target.dataset.id.replace(imageName + '_', '');
            /*-- Image_IDの取得ここまで--*/

            // fileの取得
            const imageFile = e.target.dataset.file;
            // pathの取得
            const imagePath = e.target.dataset.path;
            const modal = e.target.dataset.modal;

            // サムネイルとinput type=hiddenのvalueに設定
            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile;
            document.getElementById(imageName + '_hidden').value = imageId;

    }, )
    })

    </script>
</x-app-layout>
