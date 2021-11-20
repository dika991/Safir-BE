<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\PemesananDetails;
use App\Models\Tagihan;
use App\Transformers\AdminTransformer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use JsonResponse;

    public function userDashboard()
    {
        $users = User::all();
        return $this->successWithData("Success Get User.", $users);
    }

    public function adminDashboard()
    {
        $pemesanan = Pemesanan::where('status', '>', 0)->get('id');
        $pemesananDet = PemesananDetails::whereIn('id_pemesanan', $pemesanan)->where('total_jemaah', '>', 0)->sum('total_jemaah');
        $paket = Paket::where('status', "=", "process")->count();
        $pembayaran = Pembayaran::sum('nominal');
        $tagihan = Tagihan::where('status', 1)->count();
        $admins = Admin::all();
        $response = [
            "jemaahAkt" => $pemesananDet,
            "totalPaket" => $paket,
            "totalTransaksi" => $pembayaran,
            "tagihan" => $tagihan
        ];
        return $this->successWithData("Success Get Admin.", $response);
    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;

            return $this->successWithData("Success Login.", $success, 200);
        } else {
            return $this->fail('Email and Password are wrong.');
        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->failValidate($validator->errors(), 422);
        }

        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'admin']);

            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success = $admin;
            switch ($admin->roles) {
                case 0:
                    $success['token'] = $admin->createToken('MyApp', ['admin', 'superAdmin'])->accessToken;
                    break;
                case 1: //Untuk Operasional
                    $success['token'] = $admin->createToken('MyApp', ['admin', 'operationalAdmin'])->accessToken;
                    break;
                case 2: //Untuk inventaris
                    $success['token'] = $admin->createToken('MyApp', ['admin', 'inventarisAdmin'])->accessToken;
                    break;
                case 3: //Untuk accounting
                    $success['token'] = $admin->createToken('MyApp', ['admin', 'accountingAdmin'])->accessToken;
                    break;
            }

            return $this->successWithData("success", $success, 200);
        } else {
            return $this->fail('Email and Password are wrong.');
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->success("Berhasil Keluar!", 200);
    }

    public function listAdmin()
    {
        $admin = Admin::get();

        return $this->successWithData("Berhasil", $admin, 200);
    }

    public function listAdmins()
    {
        $user = auth()->user();
        // $admins = Admin::where('roles', "!=", 0)->paginate();
        $admins = Admin::paginate(10);
        return $this->successWithData(
            "Success!",
            (new AdminTransformer)->paginator($admins),
            200
        );
    }

    public function siapaSaya()
    {
        $user = auth()->user();

        return $this->successWithData("success", $user, 200);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        if (Hash::check($request->old, $user->password)) {
            auth()->user()->update([
                'password' => Hash::make($request->new)
            ]);
            return $this->success("Password berhasil diubah!", 200);
        } else {
            return $this->fail("Password Salah!", 400);
        }
    }

    public function updateUser(Request $request)
    {
        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return $this->success("Data Berhasil diubah!");
    }
}
