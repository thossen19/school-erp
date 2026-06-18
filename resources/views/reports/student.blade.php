@extends('layouts.app')

@section('title', 'Student Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Student Report</h1>
<div class="bg-white rounded shadow p-4 mb-4">
    <form method="GET" class="flex gap-4 items-end">
        <div><label class="block text-sm font-medium">Class</label><select name="class_id" class="border rounded px-3 py-2"><option value="">All</option>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">Total Students</h3><p class="text-2xl font-bold">{{ $total }}</p></div>
    @foreach($byGender as $gender => $count)
        <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-500 text-sm">{{ ucfirst($gender) }}</h3><p class="text-2xl font-bold">{{ $count }}</p></div>
    @endforeach
</div>
@endsection
