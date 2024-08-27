<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetProfilePasswordController extends Controller
{
    public function resetProfilePassword(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'email' => 'required|email',
            'cpassword' => 'required',
            'newpassword' => 'required|min:8',
            'retypepassword' => 'required|min:8',
        ],);

        $email = $request->email;
        $cpassword = $request->cpassword;
        $newpassword = $request->newpassword;
        $retypepassword = $request->retypepassword;
        // Find the user by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Check if the current password matches the stored password
            if (Hash::check($cpassword, $user->password)) {
                // Check if new password and retype password match
                // Here, $request->newpassword is validated for confirmation already, so no need to check it again

                // Hash and update the new password
                if ($newpassword == $retypepassword) {
                    $user->password = Hash::make($newpassword);
                    $user->save();
                    return response()->json(['success' => 'Password updated successfully.']);
                } else {
                    return response()->json(['error' => 'Password mismatch.']);
                }

                
            } else {
                return response()->json(['error' => 'Current password is invalid.']);
            }
        } else {
            return response()->json(['error' => 'No user found with this email.']);
        }
    }

}
