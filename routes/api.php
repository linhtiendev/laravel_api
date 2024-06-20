<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// tạo user (CREATE A USER)
Route::post('/users/create', function (Request $request) {
    $data = $request->all();
    if (!User::where('email', '=', $data['email'])->exists()) {
        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data["password"])
        ]);
        if (empty($user->id)) {
            return [
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured" // đã xảy ra lỗi không mong muốn
                ]
            ];
        } else {
            return [
                "success" => true,
                "response" => [
                    "user" => $user
                ]
            ];
        }
    } else {
        return [
            "success" => false,
            "response" => [
                "error" => "The user already exists"
            ]
        ];
    }
});

// Lấy tất cả user (GET ALL USER)
Route::get('/users/all', function () {
    $users = User::all();

    if (empty($users)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No user found"
            ]
        ];
    }
    return [
        "success" => true,
        "response" => [
            "users" => $users
        ]
    ];
});

// Lấy một user (GET A SINGLE USER)
Route::get('/users/{id}', function (Request $request, $id) {
    $user = User::find($id);

    if (empty($user)) {
        return [
            "success" => false,
            "response" => [
                "error" => "No user found"
            ]
        ];
    }
    return [
        "success" => true,
        "response" => [
            "user" => $user
        ]
    ];
});

// xóa một user (DELETE A SINGLE USER)
Route::delete('/users/delete/{id}', function (Request $request, $id) {
    $user = User::find($id);

    if (empty($user)) {
        $success = false;
        $response = ["error" => "User could not be deleted."];
    } else {
        $success = $user->delete();
        $response = ["success" => "User deleted!"];
    }
    return [
        "success" => $success,
        "response" => $response
    ];
});

// cập nhật user (UPDATE USER)
Route::put('/users/update/{id}', function (Request $request, $id) {
    $data = $request->all(); // lấy tất cả dữ liệu từ yêu cầu và lưu vào biến $data.

    $user = user::find($id);

    // duyệt qua tất cả các cặp key-value trong $data và cập nhật các thuộc tính tương ứng của đối tượng $user.
    foreach ($data as $key => $value) {
        $user->{$key} = $value;
    }

    // Lưu user đã cập nhật vào cơ sở dữ liệu
    $result = $user->save();

    return ["success" => $result, "response" => ["user" => $user]];
});
