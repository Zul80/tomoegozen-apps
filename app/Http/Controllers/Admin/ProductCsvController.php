<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductCsvService;
use Illuminate\Http\Request;

class ProductCsvController extends Controller
{
    public function __construct(private readonly ProductCsvService $csvService)
    {
    }

    public function export()
    {
        return $this->csvService->stream();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $count = $this->csvService->import($request->file('file'));

        return back()->with('status', "Imported {$count} products from CSV.");
    }
}

