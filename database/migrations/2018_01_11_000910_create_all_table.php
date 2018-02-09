<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * date  : 2017/01/04
 * author: t.nagai
 * desc  : DB変更履歴をとるようにする
 *         それに伴いmigrationを全テーブル一括で行うよう変更
 *         下記修正を実施
 *         ・ユーザテーブルをsoftDeletes対応
 *         ・ユーザテーブルのidをuser_idに変更（統一性のため）
 *         ・予約情報テーブルの設備IDをnullableに変更
 *         ・1予約に対してnユーザを実現するため予約ユーザテーブル追加、予約情報テーブルのユーザIDを削除
 *         ・以前からDB定義書には存在していたユーザテーブルの企業ID、権限フラグ追加
 *         ・予約情報テーブルに色追加
 */
class CreateAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // パスワード再発行用テーブル
        // ※システムテーブルなのでいじらないこと
        Schema::dropIfExists('password_resets');
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        // ユーザテーブル
        Schema::dropIfExists('users');
        Schema::create('users', function(Blueprint $table) {
            // id→user_idに変更
            //$table->increments('id');
            $table->increments('user_id');
            $table->string('account_id')->unique();
            // 企業ID追加
            $table->integer('company_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            // 権限フラグ追加
            $table->integer('right_flg');
            $table->integer('department_id');
            $table->string('phone');
            $table->rememberToken();
            // softDeletes追加
            $table->softDeletes();
            $table->timestamps();
        });
        // 部署テーブル
        Schema::dropIfExists('departments');
        Schema::create('departments', function(Blueprint $table) {
            $table->increments('department_id');
            $table->string('department_name');
            $table->softDeletes();
            $table->timestamps();
        });
        // 予約情報テーブル
        Schema::dropIfExists('reserves');
        Schema::create('reserves', function(Blueprint $table) {
            $table->increments('reserve_id');
            // 設備情報無しでも登録できるようにする
            //$table->integer('facility_id');
            $table->integer('facility_id')->nullable();
            // ユーザ情報は別に管理
            //$table->integer('user_id');
            $table->dateTime('start_dt');
            $table->dateTime('end_dt');
            $table->string('overview');
            $table->text('detail')->nullable();
            // 全日フラグ追加
            $table->boolean('allday_flg');
            // 色追加
            $table->string('color');
            $table->softDeletes();
            $table->timestamps();
        });
        // 予約ユーザテーブル
        Schema::dropIfExists('reserve_users');
        Schema::create('reserve_users', function(Blueprint $table) {
            $table->integer('reserve_id');
            $table->integer('user_id');
            $table->softDeletes();
            $table->timestamps();
            $table->primary(['reserve_id', 'user_id']);
        });
        // 契約情報テーブル
        Schema::dropIfExists('agreements');
        Schema::create('agreements', function(Blueprint $table) {
            $table->increments('agreement_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 企業情報テーブル
        Schema::dropIfExists('companies');
        Schema::create('companies', function(Blueprint $table) {
            $table->increments('company_id');
            $table->integer('agreement_id');
            $table->string('company_name');
            $table->string('zip_code');
            $table->string('prefecture_code');
            $table->string('city');
            $table->string('street_address');
            $table->string('tel');
            $table->softDeletes();
            $table->timestamps();
        });
        // 設備テーブル
        Schema::dropIfExists('facilities');
        Schema::create('facilities', function(Blueprint $table) {
            $table->increments('facility_id');
            $table->string('facility_name');
            $table->integer('facility_type_id');
            $table->softDeletes();
            $table->timestamps();
        });
        // 設備種別テーブル
        Schema::dropIfExists('facility_types');
        Schema::create('facility_types', function(Blueprint $table) {
            $table->increments('facility_type_id');
            $table->string('facility_type_name');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('reserves');
        Schema::dropIfExists('reserve_users');
        Schema::dropIfExists('agreements');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('facility_types');
    }
}
