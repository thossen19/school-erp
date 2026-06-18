<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomPageController extends Controller
{
    const SECTION_TYPES = [
        'hero'        => 'Hero / Banner',
        'text'        => 'Text / Content',
        'image_text'  => 'Image + Text',
        'features'    => 'Features / Icons',
        'cards'       => 'Cards Grid',
        'cta'         => 'Call To Action',
        'gallery'     => 'Gallery',
        'faq'         => 'FAQ Accordion',
        'contact'     => 'Contact Form',
        'partners'    => 'Partners / Logos',
        'pricing'     => 'Pricing Table',
        'team'        => 'Team Members',
        'testimonials' => 'Testimonials',
        'stats'       => 'Statistics Counters',
        'video'       => 'Video Embed',
        'newsletter'  => 'Newsletter Signup',
        'image'       => 'Single Image',
        'divider'     => 'Divider / Spacer',
        'html'        => 'Custom HTML',
        'application_form' => 'Application Form',
    ];

    public function index()
    {
        $pages = DB::table('custom_pages')
            ->where('school_id', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('custom-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('custom-pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_pages,slug',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['school_id'] = 1;
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        DB::table('custom_pages')->insert($validated);

        $id = DB::getPdo()->lastInsertId();

        return redirect()->route('custom-pages.builder', $id)
            ->with('success', 'Page created. Now add sections.');
    }

    public function edit(int $id)
    {
        $page = DB::table('custom_pages')->where('id', $id)->where('school_id', 1)->firstOrFail();
        return view('custom-pages.edit', compact('page'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:custom_pages,slug,' . $id,
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        DB::table('custom_pages')->where('id', $id)->where('school_id', 1)->update($validated);

        return redirect()->route('custom-pages.index')->with('success', 'Page updated');
    }

    public function builder(int $id)
    {
        $page = DB::table('custom_pages')->where('id', $id)->where('school_id', 1)->firstOrFail();
        $sections = DB::table('custom_page_sections')
            ->where('custom_page_id', $id)
            ->orderBy('order')
            ->get();

        $sectionTypes = self::SECTION_TYPES;

        return view('custom-pages.builder', compact('page', 'sections', 'sectionTypes'));
    }

    public function show(string $slug)
    {
        $page = DB::table('custom_pages')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('school_id', 1)
            ->firstOrFail();

        $sections = DB::table('custom_page_sections')
            ->where('custom_page_id', $page->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $sectionTypes = self::SECTION_TYPES;

        return view('custom-pages.show', compact('page', 'sections', 'sectionTypes'));
    }

    public function builderStoreSection(Request $request, int $id)
    {
        $page = DB::table('custom_pages')->where('id', $id)->where('school_id', 1)->firstOrFail();

        $validated = $request->validate([
            'section_type' => 'required|in:' . implode(',', array_keys(self::SECTION_TYPES)),
        ]);

        $maxOrder = DB::table('custom_page_sections')
            ->where('custom_page_id', $id)
            ->max('order');

        $defaultContent = $this->getDefaultContent($validated['section_type']);

        DB::table('custom_page_sections')->insert([
            'custom_page_id' => $id,
            'section_type' => $validated['section_type'],
            'content' => json_encode($defaultContent),
            'order' => ($maxOrder ?? -1) + 1,
            'is_active' => true,
        ]);

        return redirect()->route('custom-pages.builder', $id)
            ->with('success', 'Section added. Click the gear icon to edit content.');
    }

    public function builderUpdateSection(Request $request, int $id, int $sectionId)
    {
        $validated = $request->validate([
            'content' => 'required|json',
        ]);

        DB::table('custom_page_sections')
            ->where('id', $sectionId)
            ->where('custom_page_id', $id)
            ->update(['content' => $validated['content']]);

        return response()->json(['success' => true]);
    }

    public function builderReorder(Request $request, int $id)
    {
        $validated = $request->validate([
            'section_ids' => 'required|array',
            'section_ids.*' => 'integer',
        ]);

        foreach ($validated['section_ids'] as $order => $sectionId) {
            DB::table('custom_page_sections')
                ->where('id', $sectionId)
                ->where('custom_page_id', $id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function builderToggleSection(int $id, int $sectionId)
    {
        $section = DB::table('custom_page_sections')
            ->where('id', $sectionId)
            ->where('custom_page_id', $id)
            ->firstOrFail();

        DB::table('custom_page_sections')
            ->where('id', $sectionId)
            ->update(['is_active' => !$section->is_active]);

        return redirect()->route('custom-pages.builder', $id)
            ->with('success', 'Section visibility toggled');
    }

    public function builderDeleteSection(int $id, int $sectionId)
    {
        DB::table('custom_page_sections')
            ->where('id', $sectionId)
            ->where('custom_page_id', $id)
            ->delete();

        return redirect()->route('custom-pages.builder', $id)
            ->with('success', 'Section removed');
    }

    public function destroy(int $id)
    {
        DB::table('custom_pages')->where('id', $id)->where('school_id', 1)->delete();

        return redirect()->route('custom-pages.index')->with('success', 'Page deleted');
    }

    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('custom-pages', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path),
        ]);
    }

    private function getDefaultContent(string $sectionType): array
    {
        $defaults = [
            'hero' => [
                'heading' => 'Your Heading',
                'subheading' => 'Your subheading text here',
                'button_text' => 'Learn More',
                'button_url' => '#',
                'background_color' => '#1a1a2e',
            ],
            'text' => [
                'title' => 'Content Section',
                'content' => '<p>Your content here...</p>',
            ],
            'image_text' => [
                'title' => 'Image + Text',
                'content' => '<p>Your content here...</p>',
                'image_url' => '',
                'image_position' => 'left',
                'image_size' => 'full_width',
            ],
            'features' => [
                'title' => 'Features',
                'items' => [
                    ['icon' => 'fa-star', 'title' => 'Feature 1', 'description' => 'Description here'],
                    ['icon' => 'fa-heart', 'title' => 'Feature 2', 'description' => 'Description here'],
                    ['icon' => 'fa-bolt', 'title' => 'Feature 3', 'description' => 'Description here'],
                ],
            ],
            'cards' => [
                'title' => 'Cards',
                'columns' => 3,
                'cards' => [
                    ['title' => 'Card 1', 'content' => 'Content here'],
                    ['title' => 'Card 2', 'content' => 'Content here'],
                    ['title' => 'Card 3', 'content' => 'Content here'],
                ],
            ],
            'cta' => [
                'heading' => 'Call To Action',
                'description' => 'Description here',
                'button_text' => 'Get Started',
                'button_url' => '#',
                'background_color' => '#BF5700',
            ],
            'gallery' => [
                'title' => 'Gallery',
                'images' => [],
            ],
            'faq' => [
                'title' => 'FAQ',
                'items' => [
                    ['question' => 'Question 1?', 'answer' => 'Answer here'],
                    ['question' => 'Question 2?', 'answer' => 'Answer here'],
                ],
            ],
            'contact' => [
                'title' => 'Contact Us',
                'email' => 'email@school.com',
                'phone' => '+1234567890',
                'address' => 'Your address',
            ],
            'partners' => [
                'title' => 'Our Partners',
                'logos' => [],
            ],
            'pricing' => [
                'title' => 'Pricing',
                'plans' => [
                    ['name' => 'Basic', 'price' => '$9', 'features' => 'Feature 1, Feature 2'],
                    ['name' => 'Pro', 'price' => '$29', 'features' => 'Feature 1, Feature 2, Feature 3'],
                ],
            ],
            'team' => [
                'title' => 'Our Team',
                'members' => [
                    ['name' => 'John Doe', 'designation' => 'CEO', 'image_url' => ''],
                    ['name' => 'Jane Doe', 'designation' => 'Designer', 'image_url' => ''],
                ],
            ],
            'testimonials' => [
                'title' => 'Testimonials',
                'items' => [
                    ['name' => 'Client', 'text' => 'Great experience!', 'rating' => 5],
                ],
            ],
            'stats' => [
                'title' => 'Statistics',
                'items' => [
                    ['label' => 'Students', 'value' => '5000+'],
                    ['label' => 'Teachers', 'value' => '200+'],
                ],
            ],
            'video' => [
                'title' => 'Video',
                'url' => 'https://www.youtube.com/watch?v=',
            ],
            'image' => [
                'image_url' => '',
                'alt_text' => '',
                'caption' => '',
            ],
            'newsletter' => [
                'title' => 'Newsletter',
                'placeholder' => 'Your email',
                'button_text' => 'Subscribe',
            ],
            'divider' => [
                'style' => 'solid',
                'color' => '#e5e5e5',
                'height' => '1px',
            ],
            'html' => [
                'html' => '<div class="text-center"><h2>Custom HTML</h2><p>Write your own HTML here.</p></div>',
            ],
            'application_form' => [
                'title' => 'Apply Now',
                'description' => 'Submit your application for admission',
                'button_text' => 'Submit Application',
            ],
        ];

        return $defaults[$sectionType] ?? ['content' => ''];
    }
}
