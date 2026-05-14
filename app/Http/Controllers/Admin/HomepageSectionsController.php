<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class HomepageSectionsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('homepage_section_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homepageSections = HomepageSection::with(['category', 'product'])->orderBy('sort_order')->latest()->get();

        return view('admin.homepageSections.index', compact('homepageSections'));
    }

    public function create()
    {
        abort_if(Gate::denies('homepage_section_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$categories, $products] = $this->formData();

        return view('admin.homepageSections.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('homepage_section_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('homepage-sections', 'public');
        }

        HomepageSection::create($data);

        return redirect()
            ->route('admin.homepage-sections.index')
            ->with('message', 'Homepage section created successfully.');
    }

    public function show(HomepageSection $homepageSection)
    {
        abort_if(Gate::denies('homepage_section_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homepageSection->load(['category', 'product']);

        return view('admin.homepageSections.show', compact('homepageSection'));
    }

    public function edit(HomepageSection $homepageSection)
    {
        abort_if(Gate::denies('homepage_section_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$categories, $products] = $this->formData();

        return view('admin.homepageSections.edit', compact('homepageSection', 'categories', 'products'));
    }

    public function update(Request $request, HomepageSection $homepageSection)
    {
        abort_if(Gate::denies('homepage_section_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $this->validated($request);

        if ($request->has('remove_image') && $homepageSection->image) {
            Storage::disk('public')->delete($homepageSection->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($homepageSection->image) {
                Storage::disk('public')->delete($homepageSection->image);
            }

            $data['image'] = $request->file('image')->store('homepage-sections', 'public');
        }

        $homepageSection->update($data);

        return redirect()
            ->route('admin.homepage-sections.index')
            ->with('message', 'Homepage section updated successfully.');
    }

    public function destroy(HomepageSection $homepageSection)
    {
        abort_if(Gate::denies('homepage_section_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($homepageSection->image) {
            Storage::disk('public')->delete($homepageSection->image);
        }

        $homepageSection->delete();

        return back()->with('message', 'Homepage section deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('homepage_section_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        HomepageSection::whereIn('id', request('ids', []))->get()->each(function (HomepageSection $section) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }

            $section->delete();
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(HomepageSection::TYPES))],
            'audience' => ['required', Rule::in(array_keys(HomepageSection::AUDIENCES))],
            'placement' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:80'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['nullable', 'boolean'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        return [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'type' => $request->type,
            'audience' => $request->audience,
            'placement' => $request->placement ?: 'home',
            'link_url' => $request->link_url,
            'cta_text' => $request->cta_text,
            'category_id' => $request->category_id,
            'product_id' => $request->product_id,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->has('status') ? 1 : 0,
        ];
    }

    private function formData(): array
    {
        $categories = Category::where('status', 1)->orderBy('name')->pluck('name', 'id')->prepend('No Category', '');
        $products = Product::where('status', 1)->orderBy('name')->pluck('name', 'id')->prepend('No Product', '');

        return [$categories, $products];
    }
}
