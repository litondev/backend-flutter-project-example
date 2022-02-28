<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Product,
    User
};
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Product::query();        

        if($request->filled("search")){
            $data->where(function($q) use ($request) {
                $q->orWhere("title","like","%".$request->search."%")
                    ->orWhere("price","like","%".$request->search."%")
                    ->orWhere("stock","like","%".$request->search."%");
            });
        }

        $data->orderBy($request->column ?? "id",$request->order ?? "desc");

        $data = $data->paginate($request->per_page ?? 10);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try{
            \DB::beginTransaction();

            auth()->user()->products()->create($request->validated());

            \DB::commit();
            return response()->json([
                "message" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {   
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request,Product $product)
    {
        try{
            \DB::beginTransaction();

            $product->update($request->validated());

            \DB::commit();
            return response()->json([
                "message" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try{
            \DB::beginTransaction();

            $product->delete();

            \DB::commit();
            return response()->json([
                "message" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }
}
