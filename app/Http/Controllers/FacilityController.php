<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Facility;
use Exception;

class FacilityController extends Controller
{
  /**
   * 新しい設備を保存
   *
   * @param  Request  $request
   * @return Response
   */
  public function insert(Request $request)
  {
    try {
      $facility = new Facility();
      $facility->facility_name = $request->facility_name;
      $facility->facility_type_id = $request->facility_type_id;
      $facility->save();
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 既存の設備を更新
   *
   * @param  Request  $request
   * @return Response
   */
  public function update(Request $request)
  {
    try {
      $facility = Facility::where('facility_id', '=', $request->facility_id);
      $facility->update([
        'facility_name' => $request->facility_name,
        'facility_type_id' => $request->facility_type_id
      ]);
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 設備を返す
   */
  public function selectAll()
  {
    $facilities = DB::table('facilities')
                  ->leftJoin('facility_types', 'facilities.facility_type_id', '=', 'facility_types.facility_type_id')->get();
    return response()->json(['facilities' => $facilities]);
  }
}