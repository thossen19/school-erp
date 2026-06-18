<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentPromotion;
use Illuminate\Http\Request;

class StudentPromotionController extends Controller
{
    public function index()
    {
        $promotions = StudentPromotion::with('student')->latest()->paginate(15);
        return view('student-promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('student-promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'from_class_id' => 'required|integer|exists:classes,id',
            'to_class_id' => 'required|integer|exists:classes,id',
        ]);
        StudentPromotion::create($validated);
        return redirect()->route('student-promotions.index')->with('success', 'Created successfully');
    }

    public function show(int $id)
    {
        $promotion = StudentPromotion::with('student')->findOrFail($id);
        return view('student-promotions.show', compact('promotion'));
    }

    public function edit(int $id)
    {
        $promotion = StudentPromotion::findOrFail($id);
        return view('student-promotions.edit', compact('promotion'));
    }

    public function update(Request $request, int $id)
    {
        $promotion = StudentPromotion::findOrFail($id);
        $promotion->update($request->all());
        return redirect()->route('student-promotions.index')->with('success', 'Updated successfully');
    }

    public function destroy(int $id)
    {
        StudentPromotion::findOrFail($id)->delete();
        return redirect()->route('student-promotions.index')->with('success', 'Deleted successfully');
    }
}
