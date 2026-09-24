<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $data  = $request->validate(['email' => ['required', 'email:rfc', 'max:255']]);
        $email = strtolower(trim($data['email']));

        $sub     = NewsletterSubscriber::firstOrNew(['email' => $email]);
        $already = $sub->exists && $sub->is_active;

        $sub->fill(['is_active' => true, 'unsubscribed_at' => null, 'ip_address' => $request->ip()])->save();

        return back()->with('success', $already
            ? 'You are already subscribed — thank you!'
            : 'Thanks for subscribing! You will get the latest tech updates in your inbox.');
    }
}
