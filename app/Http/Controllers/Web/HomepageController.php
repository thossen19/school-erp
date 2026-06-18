<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    protected function getSettings(string $section): array
    {
        $rows = DB::table('homepage_settings')->where('section', $section)->get();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row->key] = $row->value;
        }
        return $settings;
    }

    protected function saveSettings(string $section, array $data): void
    {
        foreach ($data as $key => $value) {
            DB::table('homepage_settings')->updateOrInsert(
                ['section' => $section, 'key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value, 'updated_at' => now()]
            );
        }
    }

    public function hero()
    {
        $settings = $this->getSettings('hero');
        return view('homepage.hero', compact('settings'));
    }

    public function heroUpdate(Request $request)
    {
        $this->saveSettings('hero', $request->except('_token', '_method'));
        return redirect()->route('homepage.hero')->with('success', 'Hero section updated');
    }

    public function navigation()
    {
        $settings = $this->getSettings('navigation');
        return view('homepage.navigation', compact('settings'));
    }

    public function navigationUpdate(Request $request)
    {
        $this->saveSettings('navigation', $request->except('_token', '_method'));
        return redirect()->route('homepage.navigation')->with('success', 'Navigation settings updated');
    }

    public function about()
    {
        $settings = $this->getSettings('about');
        return view('homepage.about', compact('settings'));
    }

    public function aboutUpdate(Request $request)
    {
        $this->saveSettings('about', $request->except('_token', '_method'));
        return redirect()->route('homepage.about')->with('success', 'About section updated');
    }

    public function services()
    {
        $settings = $this->getSettings('services');
        return view('homepage.services', compact('settings'));
    }

    public function servicesUpdate(Request $request)
    {
        $this->saveSettings('services', $request->except('_token', '_method'));
        return redirect()->route('homepage.services')->with('success', 'Services section updated');
    }

    public function features()
    {
        $settings = $this->getSettings('features');
        return view('homepage.features', compact('settings'));
    }

    public function featuresUpdate(Request $request)
    {
        $this->saveSettings('features', $request->except('_token', '_method'));
        return redirect()->route('homepage.features')->with('success', 'Features section updated');
    }

    public function products()
    {
        $settings = $this->getSettings('products');
        return view('homepage.products', compact('settings'));
    }

    public function productsUpdate(Request $request)
    {
        $this->saveSettings('products', $request->except('_token', '_method'));
        return redirect()->route('homepage.products')->with('success', 'Products section updated');
    }

    public function portfolio()
    {
        $settings = $this->getSettings('portfolio');
        return view('homepage.portfolio', compact('settings'));
    }

    public function portfolioUpdate(Request $request)
    {
        $this->saveSettings('portfolio', $request->except('_token', '_method'));
        return redirect()->route('homepage.portfolio')->with('success', 'Portfolio section updated');
    }

    public function testimonials()
    {
        $settings = $this->getSettings('testimonials');
        return view('homepage.testimonials', compact('settings'));
    }

    public function testimonialsUpdate(Request $request)
    {
        $this->saveSettings('testimonials', $request->except('_token', '_method'));
        return redirect()->route('homepage.testimonials')->with('success', 'Testimonials section updated');
    }

    public function team()
    {
        $settings = $this->getSettings('team');
        return view('homepage.team', compact('settings'));
    }

    public function teamUpdate(Request $request)
    {
        $this->saveSettings('team', $request->except('_token', '_method'));
        return redirect()->route('homepage.team')->with('success', 'Team section updated');
    }

    public function statistics()
    {
        $settings = $this->getSettings('statistics');
        return view('homepage.statistics', compact('settings'));
    }

    public function statisticsUpdate(Request $request)
    {
        $this->saveSettings('statistics', $request->except('_token', '_method'));
        return redirect()->route('homepage.statistics')->with('success', 'Statistics section updated');
    }

    public function video()
    {
        $settings = $this->getSettings('video');
        return view('homepage.video', compact('settings'));
    }

    public function videoUpdate(Request $request)
    {
        $this->saveSettings('video', $request->except('_token', '_method'));
        return redirect()->route('homepage.video')->with('success', 'Video section updated');
    }

    public function faq()
    {
        $settings = $this->getSettings('faq');
        return view('homepage.faq', compact('settings'));
    }

    public function faqUpdate(Request $request)
    {
        $this->saveSettings('faq', $request->except('_token', '_method'));
        return redirect()->route('homepage.faq')->with('success', 'FAQ section updated');
    }

    public function pricing()
    {
        $settings = $this->getSettings('pricing');
        return view('homepage.pricing', compact('settings'));
    }

    public function pricingUpdate(Request $request)
    {
        $this->saveSettings('pricing', $request->except('_token', '_method'));
        return redirect()->route('homepage.pricing')->with('success', 'Pricing plans updated');
    }

    public function blog()
    {
        $settings = $this->getSettings('blog');
        return view('homepage.blog', compact('settings'));
    }

    public function blogUpdate(Request $request)
    {
        $this->saveSettings('blog', $request->except('_token', '_method'));
        return redirect()->route('homepage.blog')->with('success', 'Blog section updated');
    }

    public function cta()
    {
        $settings = $this->getSettings('cta');
        return view('homepage.cta', compact('settings'));
    }

    public function ctaUpdate(Request $request)
    {
        $this->saveSettings('cta', $request->except('_token', '_method'));
        return redirect()->route('homepage.cta')->with('success', 'CTA section updated');
    }

    public function newsletter()
    {
        $settings = $this->getSettings('newsletter');
        return view('homepage.newsletter', compact('settings'));
    }

    public function newsletterUpdate(Request $request)
    {
        $this->saveSettings('newsletter', $request->except('_token', '_method'));
        return redirect()->route('homepage.newsletter')->with('success', 'Newsletter section updated');
    }

    public function partners()
    {
        $settings = $this->getSettings('partners');
        return view('homepage.partners', compact('settings'));
    }

    public function partnersUpdate(Request $request)
    {
        $this->saveSettings('partners', $request->except('_token', '_method'));
        return redirect()->route('homepage.partners')->with('success', 'Partners section updated');
    }

    public function gallery()
    {
        $settings = $this->getSettings('gallery');
        return view('homepage.gallery', compact('settings'));
    }

    public function galleryUpdate(Request $request)
    {
        $this->saveSettings('gallery', $request->except('_token', '_method'));
        return redirect()->route('homepage.gallery')->with('success', 'Gallery section updated');
    }

    public function contact()
    {
        $settings = $this->getSettings('contact');
        return view('homepage.contact', compact('settings'));
    }

    public function contactUpdate(Request $request)
    {
        $this->saveSettings('contact', $request->except('_token', '_method'));
        return redirect()->route('homepage.contact')->with('success', 'Contact section updated');
    }

    public function socialMedia()
    {
        $settings = $this->getSettings('social_media');
        return view('homepage.social-media', compact('settings'));
    }

    public function socialMediaUpdate(Request $request)
    {
        $this->saveSettings('social_media', $request->except('_token', '_method'));
        return redirect()->route('homepage.social-media')->with('success', 'Social media settings updated');
    }

    public function footerWidgets()
    {
        $settings = $this->getSettings('footer_widgets');
        return view('homepage.footer-widgets', compact('settings'));
    }

    public function footerWidgetsUpdate(Request $request)
    {
        $this->saveSettings('footer_widgets', $request->except('_token', '_method'));
        return redirect()->route('homepage.footer-widgets')->with('success', 'Footer settings updated');
    }

    public function theme()
    {
        $settings = $this->getSettings('theme');
        return view('homepage.theme', compact('settings'));
    }

    public function themeUpdate(Request $request)
    {
        $this->saveSettings('theme', $request->except('_token', '_method'));
        return redirect()->route('homepage.theme')->with('success', 'Theme settings updated');
    }

    public function seo()
    {
        $settings = $this->getSettings('seo');
        return view('homepage.seo', compact('settings'));
    }

    public function seoUpdate(Request $request)
    {
        $this->saveSettings('seo', $request->except('_token', '_method'));
        return redirect()->route('homepage.seo')->with('success', 'SEO settings updated');
    }

    public function sectionManager()
    {
        $settings = $this->getSettings('section_manager');
        return view('homepage.section-manager', compact('settings'));
    }

    public function sectionManagerUpdate(Request $request)
    {
        $this->saveSettings('section_manager', $request->except('_token', '_method'));
        return redirect()->route('homepage.section-manager')->with('success', 'Section manager updated');
    }
}
