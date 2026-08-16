<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\AccessRequest;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccessRequestController extends Controller
{
    public function index(): View
    {
        $requests = AccessRequest::where('status', 'pending')->latest()->get();

        return view('admin.access-requests.index', compact('requests'));
    }

    public function approve(AccessRequest $accessRequest): RedirectResponse
    {
        $clinic = Clinic::create([
            'name' => $accessRequest->clinic_name,
            'slug' => $this->generateUniqueSlug($accessRequest->clinic_name),
            'phone' => $accessRequest->phone,
            'email' => $accessRequest->email,
            'plan' => 'free',
            'status' => 'active',
        ]);

        $password = Str::password(12);

        $admin = User::create([
            'clinic_id' => $clinic->id,
            'name' => $accessRequest->name,
            'email' => $accessRequest->email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Mail::to($admin->email)->send(new WelcomeMail($clinic, $admin, $password));

        $accessRequest->update(['status' => 'approved']);

        return redirect()->route('admin.access-requests.index')->with('success', 'Access request approved and clinic created.');
    }

    public function deny(AccessRequest $accessRequest): RedirectResponse
    {
        $accessRequest->update(['status' => 'denied']);

        return redirect()->route('admin.access-requests.index')->with('success', 'Access request denied.');
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (Clinic::where('slug', $slug)->exists()) {
            $slug = $original.'-'.(++$i);
        }

        return $slug;
    }
}
