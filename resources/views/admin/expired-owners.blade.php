<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            期限オーナー一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font">
                        {{-- flassmessageの表示 --}}
                        <x-flash-message />

                        <div class="container px-5 mx-auto">
                            {{-- 新規作成ボタン --}}
                            <div class="flex justify-end mb-4">
                                <button onclick="location.href='{{ route('admin.owners.create')}}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                            </div>
                            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                                <table class="table-auto w-full text-left whitespace-no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">期限切れ日時</th>
                                            {{-- buttonを同じ形に編集する --}}
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- 配列でデータベースで取得したものを１つずつ取得 --}}
                                        @foreach ($expiredOwners as $owner)
                                        <tr>
                                            <td class="px-4 py-3">{{ $owner->name }}</td>
                                            <td class="px-4 py-3">{{ $owner->email }}</td>
                                            {{-- 削除済みの日に変更 --}}
                                            <td class="px-4 py-3">{{ $owner->deleted_at->diffForHumans() }}</td>
                                            {{-- 削除用ボタン --}}
                                            <form id="delete_{{ $owner-> id }}" method="post" action="{{ route('admin.expired-owners.destroy', ['owner' => $owner->id ])}}">
                                                @csrf
                                                <td class="px-4 py-3">
                                                    {{-- data-id=>owner_id取得 ==>onclickで削除実行 --}}
                                                    <a href="#" data-id="{{ $owner->id }}" onclick="deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded ">削除</a>
                                                </td>
                                            </form>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
{{-- 削除確認用アラート --}}
    <script>
        function deletePost(e) {
        'use strict';
        if (confirm('データを完全削除しますか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
        }
        </script>
</x-app-layout>
