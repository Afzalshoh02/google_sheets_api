<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    protected $client;
    protected $sheetService;
    protected $spreadsheetId;

    public function index()
    {
        $products = Product::paginate(50);

        return view('products.index', compact('products'));
    }

    public function create()
    {

        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Allowed,Prohibited',
            'price' => 'required|numeric',
        ]);

        Product::create($request->all());
        Artisan::call('schedule:run');
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Allowed,Prohibited',
            'price' => 'required|numeric',
        ]);
        $product->update($request->all());
        Artisan::call('schedule:run');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Artisan::call('schedule:run');
        return redirect()->route('products.index');
    }

    public function generateRecords()
    {
        for ($i = 0; $i < 1000; $i++) {
            $status = rand(0, 1) == 0 ? 'Allowed' : 'Prohibited';
            Product::create([
                'name' => 'Product ' . ($i+1).' '. Carbon::now()->toString(),
                'status' => $status,
                'price' => rand(100, 1000) / 10
            ]);
        }
        Artisan::call('schedule:run');
        return redirect()->route('products.index');
    }

    public function truncateRecords()
    {
        Product::truncate();
        Artisan::call('schedule:run');
        return redirect()->route('products.index');
    }
}

