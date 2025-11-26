<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocietySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        $settings = SocietySetting::getSettings($colonyId);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        
        $validated = $request->validate([
            'society_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'registration_number' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
            'bank_branch' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'payment_gateway_config' => 'nullable|array',
            'sms_config' => 'nullable|array',
            'email_config' => 'nullable|array',
            'notification_settings' => 'nullable|array',
        ]);

        $settings = SocietySetting::getSettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $settings->update($validated);

        return redirect(panel_route('settings.index'))
            ->with('success', 'Settings updated successfully.');
    }
}
