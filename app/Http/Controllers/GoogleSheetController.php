<?php

namespace App\Http\Controllers;

use App\Models\GoogleSheet;
use Illuminate\Http\Request;

class GoogleSheetController extends Controller
{
    public function index()
    {
        $spreadsheet = GoogleSheet::latest()->first();
        return view('google_sheets.index', compact('spreadsheet'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'spreadsheet_id' => 'required|string',
        ]);
        $spreadsheet = GoogleSheet::firstOrCreate(
            [],
            ['spreadsheet_id' => $request->spreadsheet_id]
        );
        $spreadsheet->update([
            'spreadsheet_id' => $request->spreadsheet_id,
        ]);
        return redirect()->route('google_sheets.index');
    }
}
