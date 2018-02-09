<template>
  <el-dialog title="予約詳細画面" :visible.sync="visible" :before-close="onBeforeClose" v-draggable>
    <div id="reserve-dialog">
      <div class="container-fluid">
        <div class="row">
          <div class="detail-midashi col-sm-3">使用設備</div>
          <div class="detail-fixed col-sm-9">
            <el-select v-model="reserveInfo.facility_id" style="width: 100%;" placeholder="使用設備">
              <el-option v-for="facility in facilities" :key="facility.facility_id" :label="facility.facility_name" :value="facility.facility_id">
              </el-option>
            </el-select>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi detail-required col-sm-3">日時*</div>
          <div class="detail-edit col-sm-9">
            <div style="margin-bottom: 10px">
              開始 日付
              <el-date-picker id="start-date" type="date" v-model="reserveInfo.start_dt"></el-date-picker>
              <el-time-select v-model="reserveInfo.start_time" :picker-options="{ start: setting.start, step: setting.step, end: setting.end }" placeholder="開始時刻" v-if="!reserveInfo.allday_flg"></el-time-select>
            </div>
            <div>
              終了 日付
              <el-date-picker id="end-date" type="date" v-model="reserveInfo.end_dt"></el-date-picker>
              <el-time-select v-model="reserveInfo.end_time" :picker-options="{ start: setting.start, step: setting.step, end: setting.end }" placeholder="開始時刻" v-if="!reserveInfo.allday_flg"></el-time-select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi detail-required col-sm-3">会議名/来客名*</div>
          <div class="detail-edit col-sm-9">
            <el-input placeholder="会議名/来客名" v-model="reserveInfo.overview" class="detail-fit"></el-input>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi col-sm-3">詳細説明</div>
          <div class="detail-edit col-sm-9">
            <el-input type="textarea" :rows="4" placeholder="詳細説明" v-model="reserveInfo.detail" class="detail-fit"></el-input>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi col-sm-3">ユーザ</div>
          <div class="detail-fixed col-sm-9">
            <el-select v-model="reserveInfo.users" multiple style="width: 100%;" placeholder="ユーザ">
              <el-option v-for="user in users" :key="user.user_id" :label="user.name" :value="user.user_id">
              </el-option>
            </el-select>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi col-sm-3">全日フラグ</div>
          <div class="detail-edit col-sm-9">
            <el-checkbox v-model="reserveInfo.allday_flg" border size="small"></el-checkbox>
          </div>
        </div>
        <div class="row">
          <div class="detail-midashi col-sm-3">色</div>
          <div class="detail-edit col-sm-9">
            <el-color-picker v-model="reserveInfo.color" size="small"></el-color-picker>
          </div>
        </div>
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button v-if="mode === 'new'" @click="insert">
        <span class="glyphicon glyphicon-ok"></span>登録
      </el-button>
      <el-button v-if="mode === 'view'" @click="update">
        <span class="glyphicon glyphicon-ok"></span>更新
      </el-button>
      <el-button v-if="mode === 'view'" @click="deleteRec">
        <span class="glyphicon glyphicon-trash"></span>削除
      </el-button>
      <el-button @click="$emit('close')">
        <span class="glyphicon glyphicon-remove"></span>閉じる
      </el-button>
    </span>
  </el-dialog>
</template>

<script>
  export default {
    props: [
      "displayUnitId",
      "reserveId",
      "facility",
      "startDate",
      "endDate",
      "startTime",
      "endTime",
      "title",
      "detail",
      "alldayFlg",
      "color",
      "setting",
      "departments",
      "mode",
      "targetUsers",
      "users",
      "facilities"
    ],
    data() {
      return {
        visible: true,
        dragging: false,
        top: 0,
        left: 0,
        x: 0,
        y: 0,
        reserveInfo: {}
      };
    },
    created() {
      let startTime = ('00' + this.startDate.getHours()).slice(-2) + ":" + ('00' + this.startDate.getMinutes()).slice(-2);
      let endTime = ('00' + this.endDate.getHours()).slice(-2) + ":" + ('00' + this.endDate.getMinutes()).slice(-2);
      this.reserveInfo = {
        reserve_id: this.reserveId || null,
        facility_id: this.facility ? this.facility.facility_id : null,
        users: this.targetUsers,
        start_dt: this.startDate,
        end_dt: this.endDate,
        start_time: startTime,
        end_time: endTime,
        overview: this.title,
        detail: this.detail,
        allday_flg: this.alldayFlg ? true : false,
        color: this.color
      };
    },
    mounted() {
      // ダイアログを中央に表示
      let x = ($(window).width() - $('#reserve-dialog').outerWidth()) / 2;
      if (x < 0) x = 0;
      let y = ($(window).height() - $('#reserve-dialog').outerHeight()) / 2;
      if (y < 0) y = 0;
      this.left = x;
      this.top = y;
    },
    updated() {
      console.log("ReserveDialog update!!");
    },
    methods: {
      /*
       * 予約情報作成
       */
      insert: function () {
        let self = this;
        self.reserveInfo.start_dt = self.formatDate(self.reserveInfo.start_dt, self.reserveInfo.start_time);
        self.reserveInfo.end_dt = self.formatDate(self.reserveInfo.end_dt, self.reserveInfo.end_time);
        axios.post('/api/reserve/insert', self.reserveInfo).then(response => {
          if (response.data.code === 0) {
            axios.get('/api/reserve/list').then(response => {
              self.$parent.reserveReflesh(response.data.reserves);
            });
          } else {
            console.log(response.data.message);
          }
        }).catch(error => {
          console.log(error);
        });
        // ダイアログを閉じる
        this.$emit('close');
      },
      /*
       * 予約情報更新
       */
      update: function () {
        let self = this;
        self.reserveInfo.start_dt = self.formatDate(self.reserveInfo.start_dt, self.reserveInfo.start_time);
        self.reserveInfo.end_dt = self.formatDate(self.reserveInfo.end_dt, self.reserveInfo.end_time);
        axios.post('/api/reserve/update', self.reserveInfo).then(response => {
          if (response.data.code === 0) {
            axios.get('/api/reserve/list').then(response => {
              self.$parent.reserveReflesh(response.data.reserves);
            });
          } else {
            console.log(response.data.message);
          }
        }).catch(error => {
          console.log(error);
        });
        // ダイアログを閉じる
        this.$emit('close');
      },
      /*
       * 予約情報削除
       */
      deleteRec: function () {
        let self = this;
        self.$confirm('予約情報を削除してよろしいですか?', '警告', {
          confirmButtonText: 'OK',
          cancelButtonText: 'キャンセル',
          type: 'warning'
        }).then(() => {
          axios.post('/api/reserve/delete', self.reserveInfo).then(response => {
            if (response.data.code === 0) {
              axios.get('/api/reserve/list').then(response => {
                self.$parent.reserveReflesh(response.data.reserves);
              });
            } else {
              console.log(response.data.message);
            }
          }).catch(error => {
            console.log(error);
          }).then(() => {
            // ダイアログを閉じる
            self.$emit('close');
          });
        });
      },
      /*
       * PHPでそのまま変換できる日付フォーマットに変換
       */
      formatDate: function (date, time) {
        let str = 'YYYY-MM-DD hh:mm:ss';
        let mm = String(date.getMonth() + 1);
        let dd = String(date.getDate());
        let hh = String(time.split(":")[0]);
        let mi = String(time.split(":")[1]);
        mm = mm.length === 1 ? "0" + mm : mm;
        dd = dd.length === 1 ? "0" + dd : dd;
        hh = hh.length === 1 ? "0" + hh : hh;
        mi = mi.length === 1 ? "0" + mi : mi;
        str = str.replace(/YYYY/g, date.getFullYear());
        str = str.replace(/MM/g, mm);
        str = str.replace(/DD/g, dd);
        str = str.replace(/hh/g, hh);
        str = str.replace(/mm/g, mi);
        str = str.replace(/ss/g, "00");
        return str;
      },
      /*
       * 最大化
       */
      maximize: function () {
        $("#reserve-dialog").css("top", "0px");
        $("#reserve-dialog").css("left", "0px");
        $("#reserve-dialog").css("width", "100%");
        $("#reserve-dialog").css("height", "100%");
      },
      /*
       * 終了
       */
      onBeforeClose: function () {
        this.$emit('close');
      }
    }
  }
</script>

<style>
</style>