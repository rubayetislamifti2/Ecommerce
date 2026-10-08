<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->cursorPaginate(10);
        return view('product.index', compact('products'));
    }

    public function userShow(Product $product)
    {
        try {
            return view('product.user_show', ['product' => $product]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $stripe = new StripeClient(config('stripe.stripe.secret'));
            DB::beginTransaction();
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'images'=>'required|array',
                'images.*.product_id'=>'required|exists:products,id',
                'images.*.image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()->withErrors($validatedData->errors())->withInput();
            }
            $data = $validatedData->validated();
            $data['sku'] = 'SKU-'.strtoupper(Str::random(4)).'-'.time();
            $data['slug'] = Str::slug($data['name']);
            $product = Product::create($data);

            $imageUrls = [];
            foreach ($data['images'] as $image) {
                $image = $this->uploadFile($image, 'products/');

                $product->images()->create([
                    'image'=> $image,
                ]);

                $imageUrls[] = asset($image);
            }

            $stripeProduct = $stripe->products->create([
                'name' => $data['name'],
                'images' => array_slice($imageUrls, 0, 8),
                'default_price_data'=>[
                    'currency'=> 'bdt',
                    'unit_amount' => $data['price'] * 100,
                ]
            ]);

            $product->update([
                'stripe_product_id' => $stripeProduct->id,
                'stripe_price_id'=> $stripeProduct->default_price
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Product created successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('product.show', ['product'=>$product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
