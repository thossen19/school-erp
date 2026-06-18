@extends('layouts.app')

@section('title', 'Academic Years')

@section('content')
<h1 class="text-2xl font-bold mb-4">Academic Years</h1>
<div class="bg-white rounded shadow p-4 mb-4">
    <h2 class="text-lg font-semibold mb-2">Add New</h2>
    <form method="POST" action="{{ route('settings.academicYears.store') }}" class="space-y-4 max-w-lg">
        @csrf
        <div><label class="block text-sm font-medium">Name</label><input type="text" name="name" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">Start Date</label><input type="date" name="start_date" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">End Date</label><input type="date" name="end_date" required class="w-full border rounded px-3 py-2"></div>
        <div><label><input type="checkbox" name="is_current" value="1" class="mr-2">Set as Current</label></div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
    </form>
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($academicYears as $ay)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm">{{ $ay->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $ay->start_date }}</td>
                    <td class="px-4 py-2 text-sm">{{ $ay->end_date }}</td>
                    <td class="px-4 py-2 text-sm">{{ $ay->is_current ? 'Yes' : 'No' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No academic years found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <x-pagination :paginator="$academicYears" />
</div>
@endsection
