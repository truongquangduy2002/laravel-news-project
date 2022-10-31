<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function AllCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    } // End Method

    public function AddCategory()
    {
        return view('backend.category.category_add');
    } // End Method

    public function StoreCategory(Request $request)
    {

        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '_', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'Category Store Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('all.category')->with($notification);
    } // End Method

    public function EditCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    } // End Method

    public function UpdateCategory(Request $request)
    {
        $cate_id = $request->id;
        Category::findOrFail($cate_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '_', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'Category Update Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function DeleteCategory($id)
    {
        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Delete Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    } // End Method
}
