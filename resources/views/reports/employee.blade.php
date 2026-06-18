@extends('layouts.app')

@section('title', 'Employee Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Employee Report</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">Total Employees</h3><p class="text-2xl font-bold">{{ $total }}</p></div>
    @foreach($byType as $t)
        <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">{{ ucfirst($t->employment_type) }}</h3><p class="text-2xl font-bold">{{ $t->count }}</p></div>
    @endforeach
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($employees as $dept => $emps)
                <tr class="hover:bg-gray-50"><td class="px-4 py-2 text-sm font-medium">{{ $dept }}</td><td class="px-4 py-2 text-sm">{{ $emps->count() }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
