<template>
  <div class="main">
    <div class="container-fluid">
      <div class="row" id="main-body">
        <div class="col-sm-3 main-menu">
          <side-menu
            @changeDisplayUnit="changeDisplayUnit"
            @selectCalender="selectCalender"
            @openListDialog="openListDialog"></side-menu>
        </div>
        <div class="col-sm-9 content main-content">
          <schedule
            :displayUnitId="displayUnitId"
            :setting="setting"
            :date="date"
            :users="users"
            :facilities="facilities"
            :reserves="reserves"
            @openNewReserveDialog="openNewReserveDialog"
            @openReserveDialog="openReserveDialog"></schedule>
        </div>
      </div>
    </div>
    <reserve-dialog v-if="reserve"
      :displayUnitId="displayUnitId"
      :reserveId="reserveInfo.id"
      :facility="reserveInfo.facility"
      :startDate="reserveInfo.startDate"
      :endDate="reserveInfo.endDate"
      :title="reserveInfo.title"
      :detail="reserveInfo.detail"
      :alldayFlg="reserveInfo.alldayFlg"
      :color="reserveInfo.color"
      :setting="setting"
      :departments="departments"
      :mode="reserveInfo.reserveMode"
      :targetUsers="reserveInfo.users"
      :users="users"
      :facilities="facilities"
      @reserve="reserveReflesh"
      @close="closeReserveDialog"></reserve-dialog>
    <list-dialog v-if="list"
      :departments="departments"
      :facilities="facilities"
      :users="users"
      :type="listType"
      @close="closeListDialog"></list-dialog>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        displayUnitId: null,
        reserve: false,
        facilities: [],
        reserves: [],
        departments: [],
        users: [],
        user: null,
        reserveInfo: null,
        date: new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 0, 0, 0),
        reserveElement: null,
        setting: {
          start: "06:00",
          end: "24:00",
          step: "00:30"
        },
        list: false,
        listType: null
      };
    },
    created() {
      let self = this;
      let api = axios.create();
      axios.all([
        api.get('/api/facility/list'),
        api.get('/api/reserve/list'),
        api.get('/api/user'),
        api.get('/api/department/list'),
        api.get('/api/user/list'),
      ]).then(axios.spread((res1, res2, res3, res4, res5) => {
        self.facilities = res1.data.facilities;
        self.reserves = res2.data.reserves;
        self.user = res3.data;
        self.departments = res4.data.departments;
        self.users = res5.data.users;
        self.displayUnitId = "1";
        self.$nextTick(function () {
          console.log("update", self.facilities, self.reserves, self.user);
        });
      }));
    },
    updated() {
      console.log("Main update!!");
    },
    methods: {
      openNewReserveDialog(dataId, startDate, endDate, alldayFlg, element) {
        let facility = null;
        let users = [this.user.user_id];
        if (this.displayUnitId === "1") {
          // 設備情報取得
          for (let i in this.facilities) {
            if (this.facilities[i].facility_id === dataId) {
              facility = this.facilities[i];
              break;
            }
          }
        } else if (this.displayUnitId === "2") {
          // ユーザ情報取得
          users = [dataId];
        }
        // 予約情報設定
        this.reserveInfo = {
          "facility": facility,
          "startDate": startDate,
          "endDate": endDate,
          "users": users,
          "title": "",
          "detail": "",
          "alldayFlg": alldayFlg ? true : false,
          "color": "#8888ff",
          "reserveMode": "new"
        };
        // エレメントを保持
        this.reserveElement = element;
        // ダイアログ起動
        this.reserve = true;
        console.log(this.reserveInfo);
      },
      openReserveDialog(reserveInfo) {
        // 部屋情報取得
        let facility = null;
        for (let i in this.facilities) {
          if (this.facilities[i].facility_id === reserveInfo.facility_id) {
            facility = this.facilities[i];
            break;
          }
        }
        // 予約情報設定
        this.reserveInfo = {
          "id": reserveInfo.reserve_id,
          "facility": facility,
          "startDate": new Date(reserveInfo.start_dt.replace(/\-/gi, "/")),
          "endDate": new Date(reserveInfo.end_dt.replace(/\-/gi, "/")),
          "users": reserveInfo.users,
          "title": reserveInfo.overview,
          "detail": reserveInfo.detail,
          "alldayFlg": reserveInfo.allday_flg,
          "color": reserveInfo.color,
          "reserveMode": "view"
        };
        // ダイアログ起動
        this.reserve = true;
        console.log(this.reserveInfo);
      },
      closeReserveDialog() {
        this.reserve = false;
        // キャンセルされた作りかけの予約情報は削除
        if (this.reserveElement) {
          this.reserveElement.remove();
          this.reserveElement = null;
        }
      },
      openListDialog(type) {
        this.listType = type;
        this.list = true;
      },
      closeListDialog() {
        this.list = false;
      },
      changeDisplayUnit: function (displayUnitId) {
        this.displayUnitId = displayUnitId;
      },
      selectCalender: function (date) {
        this.date = date;
      },
      reserveReflesh: function (reserves) {
        this.reserves = reserves;
      }
    }
  }
</script>

<style>
</style>