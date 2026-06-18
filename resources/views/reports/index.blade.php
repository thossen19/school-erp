@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<h1 class="text-2xl font-bold mb-4">Reports</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <a href="{{ route('reports.student') }}" class="bg-white rounded shadow p-4 hover:shadow-lg transition"><h2 class="text-lg font-semibold text-blue-600">Student Report</h2><p class="text-gray-500 text-sm">View student demographics</p></a>
    <a href="{{ route('reports.attendance') }}" class="bg-white rounded shadow p-4 hover:shadow-lg transition"><h2 class="text-lg font-semibold text-blue-600">Attendance Report</h2><p class="text-gray-500 text-sm">View attendance summary</p></a>
    <a href="{{ route('reports.fee') }}" class="bg-white rounded shadow p-4 hover:shadow-lg transition"><h2 class="text-lg font-semibold text-blue-600">Fee Report</h2><p class="text-gray-500 text-sm">View fee collections</p></a>
    <a href="{{ route('reports.employee') }}" class="bg-white rounded shadow p-4 hover:shadow-lg transition"><h2 class="text-lg font-semibold text-blue-600">Employee Report</h2><p class="text-gray-500 text-sm">View employee statistics</p></a>
</div>
@endsection
