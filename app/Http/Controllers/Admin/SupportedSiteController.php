<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportedSite;
use App\Services\SitemapGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupportedSiteController extends Controller
{
    public function index()
    {
        return view('admin.sites.index', ['sites' => SupportedSite::orderBy('sort_order')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.sites.form', ['site' => new SupportedSite(['is_active' => true]), 'pageTitle' => 'Add Supported Site']);
    }

    public function store(Request $request)
    {
        SupportedSite::create($this->validated($request));
        app(SitemapGenerator::class)->write();
        return redirect()->route('admin.sites.index')->with('status', 'Supported site added successfully.');
    }

    public function edit(SupportedSite $site)
    {
        return view('admin.sites.form', ['site' => $site, 'pageTitle' => 'Edit Supported Site']);
    }

    public function update(Request $request, SupportedSite $site)
    {
        $site->update($this->validated($request, $site));
        app(SitemapGenerator::class)->write();
        return redirect()->route('admin.sites.index')->with('status', 'Supported site updated successfully.');
    }

    public function destroy(SupportedSite $site)
    {
        $site->delete();
        app(SitemapGenerator::class)->write();
        return redirect()->route('admin.sites.index')->with('status', 'Supported site deleted.');
    }

    private function validated(Request $request, SupportedSite $site = null)
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('name'));
        $request->merge([
            'slug' => $slug,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('supported_sites', 'slug')->ignore($site ? $site->id : null)],
            'domains' => ['required', 'string', 'max:1000'],
            'logo_url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string', 'max:20000'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:180'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
