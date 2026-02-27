<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FaqController extends Controller
{
        use AuthorizesRequests;

    public function index()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('faqs.index', compact('faqs'));
    }

    public function adminIndex()
    {
        
        $faqs = Faq::orderBy('order')->paginate(15);
        
        return view('faqs.admin', compact('faqs'));
    }

    public function create()
    {
        $this->authorize('manageFaqs');
        
        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manageFaqs');

        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Faq::create($request->all());

        return redirect()->route('faqs.admin')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $this->authorize('manageFaqs');
        
        return view('faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $this->authorize('manageFaqs');

        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $faq->update($request->all());

        return redirect()->route('faqs.admin')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $this->authorize('manageFaqs');

        $faq->delete();

        return redirect()->route('faqs.admin')
            ->with('success', 'FAQ deleted successfully.');
    }
}