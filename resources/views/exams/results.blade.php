@extends('layouts.app')

@section('title', 'Exam Results')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Results - {{ $exam->title }}</h1>
    <a href="{{ route('exams.show', $exam->id) }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back to Exam</a>
</div>
<div class="bg-white rounded shadow p-4">
    <p class="text-gray-500">Exam results view. Total results: {{ $exam->results_count ?? 0 }}</p>
</div>
@endsection
