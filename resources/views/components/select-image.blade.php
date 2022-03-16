{{-- 取得する画像用の設定 --}}
@php
 if($name ==='image1'){$modal ="modal-1" ;}
 if($name ==='image2'){$modal ="modal-2" ;}
 if($name ==='image3'){$modal ="modal-3" ;}
 if($name ==='image4'){$modal ="modal-4" ;}
@endphp

{{-- modal-1 => {{ $modal }} --}}
<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
        <header class="modal__header">
          <h2 class="modal__title  z-50" id="{{ $modal }}-title">
            ファイルを選択してください。
          </h2>
          {{-- type="button"がないと送信になる。 --}}
          <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="{{ $modal }}-content">
            {{-- 画像をflexで横並びにする --}}
            <div class="flex flex-wrap">
                {{-- foreach構文でimages_tableからデータを取得 --}}
                 @foreach ($images as $image)
                        {{-- width:1/2→1/4に変更, padding-2, md:p-4に変更 --}}
                        <div class="w-1/4 p-2 md:p-4">
                            {{-- data-idを利用し、JSでe.target.dataset.**で
                            データを取得するようにする。--}}
                            <img class="image" data-id="{{ $name }}_{{ $image->id }}"
                            data-file="{{ $image->filename }}"
                            data-path="{{ asset('storage/products/') }}"
                            data-modal="{{ $modal }}"
                            src="{{ asset('storage/products/' . $image->filename)}}">
                            {{-- 画像title --}}
                            <div class="text-gray-700">{{ $image->title }}</div>
                        </div>
                @endforeach
            </div>
        </main>
        <footer class="modal__footer">
          <button type="button" class="modal__btn" data-micromodal-close aria-label="閉じる">閉じる</button>
        </footer>
      </div>
    </div>
  </div>

  {{-- プレビューエリアとinputタグ(hidden) --}}
  <div class="flex justify-around items-center mb-4">
    {{-- 開くボタン --}}
    <a class="py-2 px-4 bg-gray-200" data-micromodal-trigger="{{ $modal }}" href='javascript:void(0);'>ファイルを選択</a>
    <div class="w-1/4">
    <img id="{{ $name }}_thumbnail" src="">
    </div>
    </div>
    <input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="">
