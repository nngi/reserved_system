<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Exception;

class DepartmentController extends Controller
{
  /**
   * 新しい所属課を保存
   *
   * @param  Request  $request
   * @return Response
   */
  public function insert(Request $request)
  {
    try {
      $department = new Department();
      $department->department_name = $request->department_name;
      $department->save();
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 既存の所属課を更新
   *
   * @param  Request  $request
   * @return Response
   */
  public function update(Request $request)
  {
    try {
      $department = Department::where('department_id', '=', $request->department_id);
      $department->update([
        'department_name' => $request->department_name
      ]);
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 所属課を返す
   */
  public function selectAll()
  {
    $departments = Department::all();
    return response()->json(['departments' => $departments]);
  }
}
