<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\StudyMaterial;
use Illuminate\Http\Request;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $materials = StudyMaterial::with('class', 'subject', 'uploader')->orderBy('created_at', 'desc')->paginate(15);
        return view('academics.study-materials', compact('materials'));
    }

    public function create()
    {
        return view('academics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string',
        ]);
        $validated['uploaded_by'] = $request->user()->id;
        $validated['file_path'] = $request->file('file')->store('study-materials');
        $validated['file_type'] = $request->file('file')->getClientOriginalExtension();
        $validated['file_size'] = $request->file('file')->getSize();
        StudyMaterial::create($validated);
        return redirect()->route('study-materials.index')->with('success', 'Material uploaded');
    }

    public function show(int $id)
    {
        $material = StudyMaterial::with('class', 'subject', 'uploader')->findOrFail($id);
        return view('academics.show', compact('material'));
    }

    public function edit(int $id)
    {
        $material = StudyMaterial::findOrFail($id);
        return view('academics.edit', compact('material'));
    }

    public function update(Request $request, int $id)
    {
        $material = StudyMaterial::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);
        $material->update($validated);
        return redirect()->route('study-materials.index')->with('success', 'Material updated');
    }

    public function destroy(int $id)
    {
        StudyMaterial::findOrFail($id)->delete();
        return redirect()->route('study-materials.index')->with('success', 'Material deleted');
    }
}
