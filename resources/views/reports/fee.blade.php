@extends('layouts.app')

@section('title', 'Fee Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Fee Report</h1>
<div class="bg-white rounded shadow p-4 mb-4">
    <form method="GET" class="flex gap-4 items-end">
        <div><label class="block text-sm font-medium">Year</label><input type="number" name="year" value="{{ $request->year ?? date('Y') }}" class="border rounded px-3 py-2"></div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">Total Collected</h3><p class="text-2xl font-bold">${{ number_format($totalCollected ?? 0, 2) }}</p></div>
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($summary as $s)
                <tr class="hover:bg-gray-50"><td class="px-4 py-2 text-sm">{{ $s->year }}</td><td class="px-4 py-2 text-sm">{{ date('F', mktime(0,0,0,$s->month,1)) }}</td><td class="px-4 py-2 text-sm">${{ number_format($s->total, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
