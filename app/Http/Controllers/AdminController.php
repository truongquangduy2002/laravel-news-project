<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Image;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    } // End Method 

    public function AdminProfile()
    {

        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    } // End Method

    public function AdminProfileStore(Request $request)
    {

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->user_name = $request->user_name;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function AdminChangePassword()
    {

        return view('admin.admin_change_password');
    } // End Method

    public function AdminUpdatePassword(Request $request)
    {
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with('error', "Old Password Doesn't Macth");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }

    public function AdminLogout(Request $request)
    {

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'info'

        );

        return redirect('/admin/logout/page')->with($notification);
    } // End Method 

    public function AdminLogin()
    {

        return view('admin.admin_login');
    } // End Method 

    public function AdminLogoutPage()
    {
        return view('admin.admin_logout');
    } // End Method 

    public function AllAdmin()
    {

        $alladminuser = User::where('role', 'admin')->latest()->get();
        return view('backend.admin.admin_all', compact('alladminuser'));
    } // End Method 


    public function AddAdmin()
    {
        return view('backend.admin.admin_add');
    } // End Method 

    public function StoreAdmin(Request $request)
    {

        $user = new User();
        $user->user_name = $request->user_name;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'inactive';
        $user->save();

        $notification = array(
            'message' => 'New Admin User Created Successfully',
            'alert-type' => 'success'

        );
        return redirect()->route('all.admin')->with($notification);
    } // End Method 


    public function EditAdmin($id)
    {

        $adminuser = User::findOrFail($id);
        return view('backend.admin.admin_edit', compact('adminuser'));
    } // End Method 



    public function UpdateAdmin(Request $request)
    {

        $admin_id = $request->id;

        $user = User::findOrFail($admin_id);
        $user->user_name = $request->user_name;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 'admin';
        $user->status = 'inactive';
        $user->save();

        $notification = array(
            'message' => 'Admin User Updated Successfully',
            'alert-type' => 'success'

        );
        return redirect()->route('all.admin')->with($notification);
    } // End Method 


    public function DeleteAdmin($id)
    {

        User::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    } // End Mehtod 


    public function InactiveAdminUser($id)
    {

        User::findOrFail($id)->update(['status' => 'inactive']);

        $notification = array(
            'message' => 'Admin User Inactive',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    } // End Mehtod 


    public function ActiveAdminUser($id)
    {

        User::findOrFail($id)->update(['status' => 'active']);

        $notification = array(
            'message' => 'Admin User Active',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    } // End Mehtod 

}
