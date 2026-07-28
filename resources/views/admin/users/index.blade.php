@extends('admin.layouts.app')

@section('title', 'Manage Users')
@section('header', 'Manage Users')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">User Directory</h2>
        <!-- Future: Add User Button -->
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 text-slate-300 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-700/50">ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Name</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Email</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Location</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Joined</th>
                    <th class="p-4 font-medium border-b border-slate-700/50 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-slate-300 text-sm divide-y divide-slate-700/50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">{{ $user->id }}</td>
                    <td class="p-4 font-medium text-slate-100 flex items-center">
                        <img class="h-8 w-8 rounded-full bg-slate-800 object-cover border border-slate-600 mr-3" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="">
                        {{ $user->name }}
                    </td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4">{{ $user->location ?? 'N/A' }}</td>
                    <td class="p-4 text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="p-4 text-right">
                        <button class="text-indigo-400 hover:text-indigo-300 mr-2 transition-colors" title="Edit">
                            <i data-lucide="edit-2" class="w-4 h-4 inline"></i>
                        </button>
                        <button class="text-rose-400 hover:text-rose-300 transition-colors" title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($users, 'links'))
    <div class="p-4 border-t border-slate-700/50 bg-slate-800/20">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
