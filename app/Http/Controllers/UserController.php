<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\User;
use Exception;

class UserController extends Controller
{
  /**
   * ユーザーを返す
   */
  public function selectAll()
  {
    $users = DB::table('users')
             ->leftJoin('departments', 'users.department_id', '=', 'departments.department_id')->get();
    return response()->json(['users' => $users]);
  }
}
