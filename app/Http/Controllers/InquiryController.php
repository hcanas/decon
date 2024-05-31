<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendInquiryRequest;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function __invoke(SendInquiryRequest $request)
    {
        Mail::raw($request->validated('message'), function ($message) use ($request) {
            $message->to('ecprimecorp@gmail.com')
                ->from($request->validated('email'))
                ->subject('Website Inquiry');
        });

        return back();
    }
}
