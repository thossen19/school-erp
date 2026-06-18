@extends("layouts.app")

@section("title", "Edit Employee")

@section("content")
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Edit Employee</h1>
    <a href="{{ route("employees.index") }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Back</a>
</div>
<div class="bg-white rounded shadow p-4">
    <form method="POST" action="{{ route("employees.update", $item->id ?? 1) }}" class="space-y-4 max-w-lg">
        @csrf @method("PUT")
        <p class="text-gray-500">Edit form fields go here.</p>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection
