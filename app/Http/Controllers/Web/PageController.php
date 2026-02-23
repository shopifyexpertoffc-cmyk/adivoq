<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class PageController extends Controller
{


    public function invoiceTemplates()
    {
        return view('web.pages.invoice-templates');
    }

    public function about()
    {
        return view('web.pages.about');
    }

    public function careers()
    {
        return view('web.pages.careers');
    }

    public function contact()
    {
        return view('web.pages.contact');
    }

    public function pressKit()
    {
        return view('web.pages.press-kit');
    }

    public function privacy()
    {
        return view('web.pages.privacy-policy');
    }

    public function terms()
    {
        return view('web.pages.terms-of-service');
    }

    public function refund()
    {
        return view('web.pages.refund-policy');
    }
}