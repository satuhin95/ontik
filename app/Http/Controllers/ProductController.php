<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;
class ProductController extends Controller
{
  public function index (Request $request){

    if($request){
    $query = Product::query();
    if ($request->title) {
        $query->where('title', 'like', '%' . $request->input('title') . '%');
    }

    if ($request->category_id) {
      $query->where('category_id',$request->input('category_id'));
    }

    if ($request->subcategory_id) {
        $query->where('subcategory_id',$request->input('subcategory_id'));
      
    }
    if ($request->min_price) {
      $query->where('price', '>=', $request->input('min_price'));
  }

  if ($request->max_price) {
      $query->where('price', '<=', $request->input('max_price'));
  }

    $products = $query->get();
  }else{

     $products = Product::all();
  }
    $categories = Category::all();
    return view('admin.product.index', compact('products','categories'));
  }
  public function create(){
    $categories = Category::all();
    return view('admin.product.create', compact('categories'));
  }

       public function store(Request $request)
       {
           $request->validate([
               'title' => ['required', 'string', 'max:255'],
               'description' => ['required', 'string'],
               'category_id' => ['required'],
               'subcategory_id' => ['required'],
               'price' => ['required','numeric'],
               'thumbnail' =>  ['required', 'mimes:jpeg,png,jpg,svg'],
   
           ]);
   
   
           $product = new Product();
           $product->title = $request->title;
           $product->description = $request->description;
           $product->category_id = $request->category_id;
           $product->subcategory_id = $request->subcategory_id;
           $product->price = $request->price;
           if ($request->thumbnail) {
               //Upload Image
               $file = $request->file('thumbnail');
               $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
               $destinationPath = 'upload/product/';
               $file->move($destinationPath, $filename);
   
               //Thumbs
   
               $img = Image::make($destinationPath . '/' . $filename);
               $img->resize(100, 100);
               $img->save(public_path('upload/product/' . $filename));
               $image = 'upload/product/' . $filename;
               $product->thumbnail = $image;
               
           }
          //  $product->save();
           if ($product->save()) {
               Toastr::success('Product created successfully', 'Product', ["progressBar" => "true"]);
               return response()->json(['message' => 'Product deleted successfully']);
           } else {
               Toastr::error('Something went wrong! Please check and resubmit.', 'Product', ["progressBar" => "true"]);
            
           }
       }
        public function destroy($productId)
        {
          $product = Product::find($productId);
     
            if ($product->delete()) {
              return response()->json(['message' => 'Product deleted successfully']);
            } 
        }

        public function getSubCategory(Request $request) {
          $subcategories = Subcategory::where('category_id', $request->categoryID)
          ->orderBy('title')
          ->get()->toArray();
          
          return response()->json($subcategories);
        }
  }