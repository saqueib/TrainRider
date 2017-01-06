<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{

    /**
     * Check for subscribed
     *
     * InvoiceController constructor.
     */
    public function __construct()
    {
        $this->middleware('subscribed');
    }

    /**
     * Show Invoices
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $invoices = Auth::user()->invoicesIncludingPending();
        } catch ( \Exception $e ) {
            session()->flash('status', $e->getMessage());
        }

        return view('invoice', compact('invoices'));
    }

    /**
     * Download Invoice as PDF
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function download(Request $request, $id)
    {
        return $request->user()->downloadInvoice($id, [
            'vendor'  => 'QCode.in',
            'product' => 'Train Coal',
        ]);
    }
}
