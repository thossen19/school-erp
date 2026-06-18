@extends('layouts.app')

@section('title', 'Attendance Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Attendance Report</h1>
<div class="bg-white rounded shadow p-4 mb-4">
    <form method="GET" class="flex gap-4 items-end">
        <div><label class="block text-sm font-medium">From</label><input type="date" name="date_from" value="{{ $request->date_from ?? '' }}" required class="border rounded px-3 py-2"></div>
        <div><label class="block text-sm font-medium">To</label><input type="date" name="date_to" value="{{ $request->date_to ?? '' }}" required class="border rounded px-3 py-2"></div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Generate</button>
    </form>
</div>
@isset($summary)
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        @foreach($summary as $s)
            <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">{{ ucfirst($s->status) }}</h3><p class="text-2xl font-bold">{{ $s->count }}</p></div>
        @endforeach
    </div>
@endisset
@endsection
