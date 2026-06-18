@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Exam Schedule - {{ $exam->title }}</h1>
    <a href="{{ route('exams.show', $exam->id) }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back to Exam</a>
</div>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50"><td class="px-4 py-2 text-sm">{{ $schedule->exam_date }}</td><td class="px-4 py-2 text-sm">{{ $schedule->class$this->name ?? '-' }}</td><td class="px-4 py-2 text-sm">{{ $schedule->subject$this->name ?? '-' }}</td></tr>
            @empty
                <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500">No schedule found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
