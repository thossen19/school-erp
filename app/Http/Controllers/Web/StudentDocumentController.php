<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentDocument;
use Illuminate\Http\Request;

class StudentDocumentController extends Controller
{
    public function index()
    {
        $documents = StudentDocument::with('student')->latest()->paginate(15);
        return view('student-documents.index', compact('documents'));
    }

    public function create()
    {
        return view('student-documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
        ]);
        StudentDocument::create($validated);
        return redirect()->route('student-documents.index')->with('success', 'Created successfully');
    }

    public function show(int $id)
    {
        $document = StudentDocument::with('student')->findOrFail($id);
        return view('student-documents.show', compact('document'));
    }

    public function edit(int $id)
    {
        $document = StudentDocument::findOrFail($id);
        return view('student-documents.edit', compact('document'));
    }

    public function update(Request $request, int $id)
    {
        $document = StudentDocument::findOrFail($id);
        $document->update($request->all());
        return redirect()->route('student-documents.index')->with('success', 'Updated successfully');
    }

    public function destroy(int $id)
    {
        StudentDocument::findOrFail($id)->delete();
        return redirect()->route('student-documents.index')->with('success', 'Deleted successfully');
    }
}
