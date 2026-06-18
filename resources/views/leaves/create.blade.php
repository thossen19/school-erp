@extends('layouts.app')

@section('title', 'Apply Leave')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Apply for Leave</h1>
    <a href="{{ route('leaves.index') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
</div>
<div class="bg-white rounded shadow p-4">
    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-4 max-w-lg">
        @csrf
        <p class="text-gray-500">Leave application form fields go here.</p>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
    </form>
</div>
@endsection
