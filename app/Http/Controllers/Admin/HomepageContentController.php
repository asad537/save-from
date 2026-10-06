<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use Illuminate\Http\Request;

class HomepageContentController extends Controller
{
    public function edit()
    {
        $homepage = HomepageContent::firstOrCreate([], [
            'heading' => 'Online Media Downloader for Public Links',
            'content' => '<p>Add your homepage SEO content here.</p>',
            'is_active' => true,
        ]);

        return view('admin.homepage-content', compact('homepage'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'heading' => ['required', 'string', 'max:190'],
            'intro' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:180'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        HomepageContent::firstOrCreate([])->update($data);

        return redirect()->route('admin.homepage.edit')->with('status', 'Homepage SEO content updated successfully.');
    }
}
