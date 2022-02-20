<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理者用ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>Eloquent(エロクアント)</p>
                    {{-- 配列でOwnerの名前と作成日時を取得 --}}
                    @foreach ($e_all as $e_owner)
                        {{ $e_owner->name }}
                        {{-- Carbonインスタンスからの取得 --}}
                        {{ $e_owner->created_at->diffForHumans() }}
                    @endforeach
                    <p>QueryBuilder(クエリービルダー)</p>
                    @foreach ($q_get as $q_owner)
                        {{ $q_owner->name }}
                        {{-- Carbonインスタンスからの取得ではないため、methodの宣言が必要 --}}
                        {{ Carbon\Carbon::parse($q_owner->created_at)->diffForHumans() }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
