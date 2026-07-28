@extends('admin.layouts.app')

@section('title', 'Manage Foods')
@section('header', 'Manage Foods')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">Food Directory</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 text-slate-300 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-700/50">ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Name</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Actions</th>
                </tr>
            </thead>
            <tbody class="text-slate-300 text-sm divide-y divide-slate-700/50">
                @forelse($items as $item)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">{{ $item->id ?? $item->food_id }}</td>
                    <td class="p-4 font-medium text-slate-100">{{ $item->name ?? $item->food_name ?? 'Item' }}</td>
                    <td class="p-4">
                        <button class="text-indigo-400 hover:text-indigo-300 transition-colors" title="View">
                            <i data-lucide="eye" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-slate-400">
                        <i data-lucide="pizza" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        No foods found.
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
