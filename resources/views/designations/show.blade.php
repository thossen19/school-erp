@extends("layouts.app")

@section("title", "Show Designation")

@section("content")
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Designation Details</h1>
    <div class="space-x-2">
        @if(isset($item) && isset($item->id))
            <a href="{{ route("designations.edit", $item->id) }}" class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">Edit</a>
        @endif
        <a href="{{ route("designations.index") }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
    </div>
</div>
<div class="bg-white rounded shadow p-4">
    <p class="text-gray-500">Details view for Designation.</p>
</div>
@endsection
