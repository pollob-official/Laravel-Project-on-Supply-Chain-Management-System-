<?php

namespace App\Http\Controllers; // শুধু এইটুকু থাকবে

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SystemConfigController extends Controller
{
    public function index()
    {
        $setting = DB::table('settings')->first();
        // আপনার পাথ অনুযায়ী: resources/views/admin/settings/index.blade.php
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = [
            'site_name'       => $request->site_name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'currency_symbol' => $request->currency_symbol,
            'address'         => $request->address,
            'footer_copy'     => $request->footer_copy,
            'updated_at'      => now(),
        ];

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $filename);
            $data['logo'] = $filename;
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $filename);
            $data['favicon'] = $filename;
        }

        DB::table('settings')->where('id', 1)->update($data);

        return back()->with('success', 'Settings updated!');
    }
}
