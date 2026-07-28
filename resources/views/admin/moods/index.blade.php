@extends('admin.layouts.app')

@section('title', 'Manage Moods')
@section('header', 'Manage Moods')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">Mood Directory</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 text-slate-300 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-700/50">ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Mood Name</th>
                </tr>
            </thead>
            <tbody class="text-slate-300 text-sm divide-y divide-slate-700/50">
                @forelse($items as $item)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">{{ $item->id ?? $item->mood_id }}</td>
                    <td class="p-4 font-medium text-slate-100">{{ $item->name ?? $item->mood_name ?? 'Mood' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="p-8 text-center text-slate-400">
                        <i data-lucide="smile" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        No moods found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($items, 'links'))
    <div class="p-4 border-t border-slate-700/50 bg-slate-800/20">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
