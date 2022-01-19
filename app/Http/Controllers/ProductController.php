<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Product;
use Image;
use File;
use Illuminate\Support\Facades\Validator;
use DB;

class ProductController extends Controller
{
    public function index(){
        $subcategories = SubCategory::all();
        return view('product.index',compact('subcategories'));
    }

    public function store(Request $request)
    {

        $validator = validator::make($request->all(), [
            'title'=>'required|max:191',
            'price'=>'required|max:191',
            'description'=>'required|max:191',
            'thumbnail'=>'required|max:191',
            'subcategory_id'=>'required|max:191',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{

            $product = new Product;
            $product->subcategory_id = $request->input('subcategory_id');
            $product->title = $request->input('title');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            if($request->hasFile('thumbnail')){
                $file = $request->file('thumbnail');
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' .$extention;
                $file->move('upload/products/',$filename);
                $product->thumbnail = $filename;
            }

            $product->save();
            return response()->json([
                'status'=>200,
                'message'=>'Product Added Successfully',
            ]);
        }

    }

    public function getProduct(){
        $products = Product::all();
        return response()->json([
            'product'=>$products,
        ]);
    }

    public function destroy($id){
        $product = Product::find($id);
        if($product){
            $path = 'upload/products/'.$product->thumbnail;
            if(File::exists($path))
            {
                File::delete($path);
            }
            $product->delete();
            return response()->json([
                'status'=>200,
                'message'=>'product delete successfully',
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'product not found',
            ]);
        }
    }


    // public function search(Request $request){
    //     if($request->ajax()){

    //         $output = '';
    //         $total_data = '';
    //         $query = $request->get('query');
    //         if($query != '')
    //         {

    //             $data = DB::table('products')
    //                     ->where('title', 'like', '%'.$query.'%')
    //                     ->orwhere('price', 'like', '%'.$query.'%')
    //                     ->orwhere('description', 'like', '%'.$query.'%')->orderBy('id','desc')->get();

    //         }else{
    //             $data = DB::table('products')->orderBy('id','desc')->get();
    //         }
    //         $total_row = $data->count();
    //         if($total_row > 0){
    //             foreach($data as $row)
    //             {
    //                 $output .= '<tr>
    //                     <td>'.$row->title.'</td>
    //                     <td>'.$row->description.'</td>
    //                     <td>'.$row->price.'</td>
    //                     <td>'.$row->subcategory_id.'</td>
    //                     <td>'.$row->thumbnail.'</td>

    //                 </tr>';
    //             }
    //         }else{
    //             $output = '<tr>
    //                 <td align="center" colspan="5">No data found</td>
    //             </tr>';
    //         }
    //         $data = array(
    //             'table_data' =>$output,
    //             'table_data' =>$total_data,
    //         );
    //         echo json_encode($data);


    //     }
    // }

    public function searchProduct(Request $request){
        $inputSearch = $request['inputSearch'];
        $data = DB::table('products')
                        ->where('title', 'like', '%'.$inputSearch.'%')
                        ->orwhere('price', 'like', '%'.$inputSearch.'%')
                        ->orwhere('description', 'like', '%'.$inputSearch.'%')->orderBy('id','desc')->get();

                        return response()->json([
                            'status'=>200,
                            'product'=>$data,
                        ]);
    }




}
