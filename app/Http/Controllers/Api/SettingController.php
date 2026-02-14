<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Get site settings.
     */
    public function index()
    {
        $settings = Setting::first();
        
        if (!$settings) {
            return response()->json([
                'logo' => '',
                'favicon' => '',
                'site_name' => ['en' => '', 'ar' => ''],
                'site_description' => ['en' => '', 'ar' => ''],
                'site_keywords' => ['en' => '', 'ar' => ''],
                'contact_email' => '',
                'contact_phone' => '',
                'whatsapp_number' => '',
                'currency' => 'SAR',
                'free_shipping_threshold' => 0,
                'default_shipping_cost' => 0,
                'social_facebook' => '',
                'social_twitter' => '',
                'social_instagram' => '',
                'social_linkedin' => '',
                'social_snapchat' => '',
                'social_youtube' => '',
            ]);
        }

        return response()->json($settings);
    }

    /**
     * Update site settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name.en' => 'required|string|max:255',
            'site_name.ar' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'default_shipping_cost' => 'nullable|numeric|min:0',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        $settings = Setting::firstOrCreate(['id' => 1]);
        
        $data = $request->except(['logo', 'favicon']);
        $data['default_shipping_cost'] = floatval($request->input('default_shipping_cost') ?: 0);
        $data['free_shipping_threshold'] = floatval($request->input('free_shipping_threshold') ?: 0);

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $settings->logo));
            }
            $path = $request->file('logo')->store('site', 'public');
            $data['logo'] = $path;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $settings->favicon));
            }
            $path = $request->file('favicon')->store('site', 'public');
            $data['favicon'] = $path;
        }

        $settings->update($data);

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $settings
        ]);
    }
}
