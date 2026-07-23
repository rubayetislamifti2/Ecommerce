<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $products = Product::latest()->cursorPaginate(10);
            $totalProducts = Product::count();
            return view('admin.dashboard',[
                'products'=>$products,
                'totalProducts'=>$totalProducts,]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
