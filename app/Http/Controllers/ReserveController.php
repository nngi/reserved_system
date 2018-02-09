<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reserve;
use App\ReserveUser;
use DateTime;
use Exception;
use DB;

class ReserveController extends Controller
{
  /**
   * 新しい予約を保存
   *
   * @param  Request  $request
   * @return Response
   */
  public function insert(Request $request)
  {
    try {
      $reserve = new Reserve();
      $reserve->facility_id = $request->facility_id;
      $reserve->start_dt = $request->start_dt;
      $reserve->end_dt = $request->end_dt;
      $reserve->overview = $request->overview;
      $reserve->detail = $request->detail;
      $reserve->allday_flg = $request->allday_flg;
      $reserve->color = $request->color;
      $reserve->save();
      // 最後に登録したIDを取得
      $id = DB::getPdo()->lastInsertId();
      // 予約ユーザ情報を登録
      for ($i = 0; $i < count($request->users); $i++) {
        $reserveUser = new ReserveUser();
        $reserveUser->reserve_id = $id;
        $reserveUser->user_id = $request->users[$i];
        $reserveUser->save();
      }
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 既存の予約を更新
   *
   * @param  Request  $request
   * @return Response
   */
  public function update(Request $request)
  {
    try {
      $reserve = Reserve::where('reserve_id', '=', $request->reserve_id);
      $reserve->update([
        'facility_id' => $request->facility_id,
        'start_dt' => $request->start_dt,
        'end_dt' => $request->end_dt,
        'overview' => $request->overview,
        'detail' => $request->detail,
        'allday_flg' => $request->allday_flg,
        'color' => $request->color
      ]);
      // 予約ユーザ情報は一旦全deleteして再insert
      $reserveUsers = ReserveUser::where('reserve_id', '=', $request->reserve_id);
      $reserveUsers->delete();
      for ($i = 0; $i < count($request->users); $i++) {
        $reserveUser = new ReserveUser();
        $reserveUser->reserve_id = $request->reserve_id;
        $reserveUser->user_id = $request->users[$i];
        $reserveUser->save();
      }
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 既存の予約を削除
   *
   * @param  Request  $request
   * @return Response
   */
  public function delete(Request $request)
  {
    try {
      $reserve = Reserve::where('reserve_id', '=', $request->reserve_id);
      $reserve->delete();
      $reserveUsers = ReserveUser::where('reserve_id', '=', $request->reserve_id);
      $reserveUsers->delete();
      return response()->json(['code' => 0, 'message' => '']);
    } catch (Exception $e) {
      return response()->json(['code' => 1, 'message' => $e->getMessage()]);
    }
  }
  /**
   * 予約を返す
   */
  public function selectAll()
  {
    $reserves = Reserve::all();
    // 予約ユーザ情報を取得
    // users: [1, 2, 3]のようにユーザIDの配列を予約情報に付加
    for ($i = 0; $i < count($reserves); $i++) {
      $reserve = $reserves[$i];
      $reserveUsers = ReserveUser::where('reserve_id', '=', $reserve->reserve_id)->get();
      $users = [];
      for ($j = 0; $j < count($reserveUsers); $j++) {
        $reserveUser = $reserveUsers[$j];
        $users[$j] = $reserveUser->user_id;
      }
      $reserve->users = $users;
    }
    return response()->json(['reserves' => $reserves]);
  }
}
