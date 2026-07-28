@extends('admin.layouts.app')

@section('title', 'Manage Feedback')
@section('header', 'Manage Feedback')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">Feedback Received</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 text-slate-300 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-700/50">ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">User ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Message</th>
                </tr>
            </thead>
            <tbody class="text-slate-300 text-sm divide-y divide-slate-700/50">
                @forelse($items as $item)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">{{ $item->id ?? $item->feedback_id }}</td>
                    <td class="p-4">{{ $item->user_id ?? 'Anonymous' }}</td>
                    <td class="p-4">{{ $item->message ?? $item->feedback_text ?? 'Message' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-slate-400">
                        <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        No feedback found.
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
