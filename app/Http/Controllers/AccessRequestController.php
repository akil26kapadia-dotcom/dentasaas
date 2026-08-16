<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessRequestRequest;
use App\Mail\AccessRequestMail;
use App\Models\AccessRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AccessRequestController extends Controller
{
    public function create(): View
    {
        return view('public.request-access');
    }

    public function store(AccessRequestRequest $request): RedirectResponse
    {
        $accessRequest = AccessRequest::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        Mail::to('admin@dentasaas.com')->send(new AccessRequestMail($accessRequest));

        return redirect()->route('request-access')
            ->with('success', 'We will contact you within 24 hours.');
    }
}
