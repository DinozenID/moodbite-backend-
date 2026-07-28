@extends('admin.layouts.app')

@section('title', 'Manage Restaurants')
@section('header', 'Manage Restaurants')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">Restaurant Directory</h2>
        <a href="{{ route('admin.restaurants.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Restaurant
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 text-slate-300 text-sm uppercase tracking-wider">
                    <th class="p-4 font-medium border-b border-slate-700/50">ID</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Name</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Category</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Location</th>
                    <th class="p-4 font-medium border-b border-slate-700/50">Added</th>
                    <th class="p-4 font-medium border-b border-slate-700/50 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-slate-300 text-sm divide-y divide-slate-700/50">
                @forelse($restaurants as $restaurant)
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="p-4">{{ $restaurant->id ?? $restaurant->restaurant_id }}</td>
                    <td class="p-4 font-medium text-slate-100">{{ $restaurant->name ?? $restaurant->restaurant_name }}</td>
                    <td class="p-4">{{ $restaurant->category ?? 'N/A' }}</td>
                    <td class="p-4">{{ $restaurant->location ?? 'N/A' }}</td>
                    <td class="p-4 text-slate-400">{{ $restaurant->created_at ? $restaurant->created_at->format('M d, Y') : 'N/A' }}</td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.restaurants.edit', $restaurant->id ?? $restaurant->restaurant_id) }}" class="text-indigo-400 hover:text-indigo-300 transition-colors mr-3" title="Edit">
                            <i data-lucide="edit" class="w-4 h-4 inline"></i>
                        </a>
                        <form action="{{ route('admin.restaurants.destroy', $restaurant->id ?? $restaurant->restaurant_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this restaurant?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        <i data-lucide="store" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                        No restaurants found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($restaurants, 'links'))
    <div class="p-4 border-t border-slate-700/50 bg-slate-800/20">
        {{ $restaurants->links() }}
    </div>
    @endif
</div>
@endsection
