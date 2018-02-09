<template>
  <div class="schedule">
    <div id="table-schedule-wrapper">
      <div id="table-fixed-header-wrapper">
        <table id="table-header">
          <tr>
            <td v-for="displayUnit in displayUnits">
              <div class="schedule-header">{{displayUnit.name}}</div>
            </td>
          </tr>
        </table>
      </div>
      <div id="table-fixed-column-wrapper">
        <table id="table-time">
          <tr>
            <td class="td-time-allday" :style="{ height: alldayHeight + 'px' }">
              <div class="tg-time-allday">全日</div>
            </td>
          </tr>
          <tr>
            <td class="td-time">
              <div class="tg-time" v-for="n in (endHour - startHour)" v-bind:style="{ height: hourHeight + 'px' }">{{(startHour + (n - 1)) + ":00"}}</div>
            </td>
          </tr>
        </table>
      </div>
      <div id="table-body-wrapper" v-on:scroll="scrollFn">
        <table id="table-schedule">
          <tr>
            <td class="td-col-allday" :style="{ height: alldayHeight + 'px' }" v-for="displayUnit in displayUnits">
              <div class="tg-col-allday" v-bind:data-id="displayUnit.id" @mouseup="clickAllday">
                <div v-for="reserveInfo in getReserveInfoAllday(displayUnit.id)"
                class="reserved-time-allday"
                v-bind:style="{ zIndex: reserveInfo.order, backgroundColor: changeRgba(reserveInfo.color) }" @click="viewReserve(reserveInfo)">{{reserveInfo.overview}}</div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="td-col" v-for="displayUnit in displayUnits" v-bind:style="colSetting">
              <div class="tg-col" v-bind:data-id="displayUnit.id" v-bind:style="borderSetting" v-on:mousedown="dragstart" v-on:mouseup="dragend" v-on:mousemove="dragover">
                <div v-for="reserveInfo in getReserveInfo(displayUnit.id)"
                class="reserved-time"
                v-bind:class="{ start: reserveInfo.startFlg, end: reserveInfo.endFlg }"
                v-bind:style="{ top: reserveInfo.top + 'px', height: reserveInfo.height + 'px', left: reserveInfo.left, width: reserveInfo.width, zIndex: reserveInfo.order, backgroundColor: changeRgba(reserveInfo.color) }" @click="viewReserve(reserveInfo)">{{reserveInfo.overview}}</div>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    /*
     * パラメータ
     */
    props: [
      "date",
      "users",
      "facilities",
      "reserves",
      "setting",
      "displayUnitId"
    ],
    /*
     * インスタンス変数
     */
    data() {
      return {
        dragging: false,
        dragStartEvt: null,
        dragEndEvt: null,
        dragElement: null,
        lastTime: new Date().getTime(),
        unitMinutes: 30,
        hourHeight: 60,
        unitHeight: 30,
        borderSetting: null,
        colSetting: null,
        separate: null,
        startHour: null,
        endHour: null,
        reserveHash: {},
        alldayHeight: 50,
        displayUnits: []
      };
    },
    /*
     * 作成後
     */
    created() {
      this.startHour = parseInt(this.setting.start.split(":")[0]);
      this.endHour = parseInt(this.setting.end.split(":")[0]);
      this.separate = 60 / parseInt(this.setting.step.split(":")[1]);
      this.unitMinutes = 60 / this.separate;
      this.hourHeight = 60;
      this.unitHeight = this.hourHeight / this.separate;
      // ボーダーの設定
      this.borderSetting = {
        backgroundPositionY: `${this.hourHeight - 1}px, ${this.unitHeight - 1}px`,
        backgroundSize: `100% ${this.hourHeight}px, 100% ${this.unitHeight}px`
      };
      // 行の高さ
      this.colSetting = {
        height: `${(this.hourHeight * (this.endHour - this.startHour))}px`
      };
    },
    updated() {
      console.log("Schedule update!!");
    },
    watch: {
      /**
       * 表示単位を監視
       * 表示単位毎に予約情報を表示
       */
      displayUnitId: function (newDisplayUnitId) {
        const self = this;
        self.displayUnits = [];
        if (newDisplayUnitId === "1") {
          // 表示単位：設備
          $.each(self.facilities, function (idx, facility) {
            self.displayUnits.push({
              id: facility.facility_id,
              name: facility.facility_name
            });
          });
        } else if (newDisplayUnitId === "2") {
          // 表示単位：ユーザ
          $.each(self.users, function (idx, user) {
            self.displayUnits.push({
              id: user.user_id,
              name: user.name
            });
          });
        }
        self.irekaeReserves(self.reserves);
      },
      /**
       * 予約情報を監視
       * 予約情報に重複情報を付与してreserveHashで管理
       */
      reserves: function (newReserves) {
        this.irekaeReserves(newReserves);
      }
    },
    methods: {
      /*
       * 予約情報を入れ替え
       */
      irekaeReserves (reserves) {
        const self = this;
        // 全日行の高さ初期化
        self.alldayHeight = 50;
        // 予約情報を表示単位別に分ける
        self.reserveHash = {};
        $.each(reserves, function (i, reserve) {
          if (self.displayUnitId === "1") {
            if (!self.reserveHash[reserve.facility_id]) {
              self.reserveHash[reserve.facility_id] = [];
            }
            reserve.start = new Date(reserve.start_dt.replace(/\-/gi, "/"));
            reserve.end = new Date(reserve.end_dt.replace(/\-/gi, "/"));
            self.reserveHash[reserve.facility_id].push(reserve);
          } else if (self.displayUnitId === "2") {
            $.each(reserve.users, function (j, userId) {
              if (!self.reserveHash[userId]) {
                self.reserveHash[userId] = [];
              }
              reserve.start = new Date(reserve.start_dt.replace(/\-/gi, "/"));
              reserve.end = new Date(reserve.end_dt.replace(/\-/gi, "/"));
              self.reserveHash[userId].push(Object.assign({}, reserve));
            });
          }
        });
        // 設備毎に開始時間で昇順ソートして重複情報を設定
        $.each(self.reserveHash, function (key, array) {
          let filterArray = array.filter(function (a) { return !a.allday_flg });
          filterArray.sort(function (a, b) { return a.start_dt > b.start_dt ? 1 : -1 });
          // 行数設定
          let result = [];
          let max = 0;
          let cols = {};
          for (let i = self.startHour; i <= self.endHour; i++) {
            for (let j = 0; j < self.separate; j++) {
              let count = 0;
              let dt = new Date(self.date.getFullYear(), self.date.getMonth(), self.date.getDate(), i, (self.unitMinutes * j), 0);
              $.each(filterArray, function (j, rec) {
                if (rec.start <= dt && rec.end > dt) {
                  count++;
                }
              });
              cols[dt.toLocaleTimeString()] = "-";
              if (max < count) {
                max = count;
              }
            }
          }
          // 空の状態にする
          for (let i = 0; i < max; i++) {
            result.push($.extend({}, cols));
          }
          // 加算用
          let addMinutes = function (date, minutes) {
            return new Date(date.getTime() + minutes * 60000);
          }
          // とてもわかりづらいが、
          // ここで重複する予約の状態を見ている
          $.each(filterArray, function (i, rec) {
            for (let i = 0; i < max; i++) {
              if (result[i][rec.start.toLocaleTimeString()] === "-") {
                let row = result[i];
                for (let time = rec.start; time < rec.end; time = addMinutes(time, self.unitMinutes)) {
                  result[i][time.toLocaleTimeString()] = "ok";
                }
                rec.order = i;
                rec.overlap = max;
                break;
              }
            }
          });
        });
      },
      /*
       * ドラッグ開始
       */
      dragstart(e) {
        if (e.target.className.match(/tg-col/gi)) {
          this.dragging = true;
          this.dragStartEvt = e;
          // 相対座標から時間を求める
          let rect = e.target.getBoundingClientRect();
          let startY = e.clientY - rect.top;
          startY = this.unitHeight * Math.floor(startY / this.unitHeight);
          this.dragElement = $("<div>", {
            class: "reserved-time"
          });
          this.dragElement.attr("date", new Date());
          this.dragElement.css("top", `${startY}px`);
          this.dragElement.css("height", `${this.unitHeight}px`);
          this.dragElement.appendTo(e.target);
        }
      },
      /*
       * ドラッグ終了
       */
      dragend(e) {
        const self = this;
        if (self.dragging) {
          self.dragging = false;
          self.dragEndEvt = e;
          // 予約情報のエレメントの設定
          let yArray = self.changeReserveRect(e);
          // 開始時刻と終了時刻のpxから時間を求める
          let startY = yArray[0];
          let endY = yArray[1];
          let startH = Math.floor(self.startHour + (startY / self.hourHeight));
          let startM = 60 * ((startY / self.hourHeight) % 1);
          let endH = Math.floor(self.startHour + (endY / self.hourHeight));
          let endM = 60 * ((endY / self.hourHeight) % 1);
          let startDate = new Date(self.date.getFullYear(), self.date.getMonth(), self.date.getDate(), startH, startM, 0);
          let endDate = new Date(self.date.getFullYear(), self.date.getMonth(), self.date.getDate(), endH, endM, 0);
          let dataId = $(self.dragStartEvt.target).attr("data-id");
          self.reserve(dataId, startDate, endDate);
        }
      },
      /*
       * ドラッグ中
       */
      dragover(e) {
        const self = this;
        if (self.dragging) {
          this.throttleFunc(function () {
            self.changeReserveRect(e);
          }, 100);
        }
      },
      /*
       * 全日クリック
       */
      clickAllday(e) {
        const self = this;
        let startDate = new Date(self.date.getFullYear(), self.date.getMonth(), self.date.getDate(), 0, 0, 0);
        let endDate = new Date(self.date.getFullYear(), self.date.getMonth(), self.date.getDate(), 24, 0, 0);
        let dataId = $(e.target).attr("data-id");
        this.reserve(dataId, startDate, endDate, true);
      },
      /*
       * 予約情報のエレメントの設定
       */
      changeReserveRect(e) {
        const self = this;
        // 相対座標から時間を求める
        let target = self.dragStartEvt.target;
        let rect = target.getBoundingClientRect();
        let startY = self.dragStartEvt.clientY - rect.top;
        let endY = e.clientY - rect.top;
        if (startY > endY) {
          let tmpY = startY;
          startY = endY;
          endY = tmpY;
        }
        // セルの高さに位置を調整
        startY = self.unitHeight * Math.floor(startY / self.unitHeight);
        endY = self.unitHeight * Math.ceil(endY / self.unitHeight);
        self.dragElement.css("top", `${startY}px`);
        self.dragElement.css("height", `${endY - startY}px`);
        return [startY, endY];
      },
      /*
       * 何度も呼ばれてしまうのを防ぐためスロットル処理
       */
      throttleFunc(func, interval) {
        // 最後に実行した時間から間引きたい時間経過していたら実行
        if ((this.lastTime + interval) <= new Date().getTime()) {
          this.lastTime = new Date().getTime();
          func.apply();
        }
      },
      /*
       * 日付比較処理
       */
      equalDate(date1, date2) {
        return (
          date1.getFullYear() === date2.getFullYear() &&
          date1.getMonth() === date2.getMonth() &&
          date1.getDate() === date2.getDate() &&
          date1.getHours() === date2.getHours() &&
          date1.getMinutes() === date2.getMinutes() &&
          date1.getSeconds() === date2.getSeconds()
        );
      },
      /*
       * 部屋IDに対応する予約情報に、DOM表示に必要な要素を加えて返す
       */
      getReserveInfo(unitId) {
        const self = this;
        let reserves = [];
        let today = this.date;
        let tomorrow = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
        let startHour = new Date(today.getFullYear(), today.getMonth(), today.getDate(), this.startHour);
        let endHour = new Date(today.getFullYear(), today.getMonth(), today.getDate(), this.endHour);
        $.each(this.reserveHash[unitId], function (idx, reserve) {
          reserve.startFlg = true;
          reserve.endFlg = true;
          // 全日フラグがONのデータは除外
          if (reserve.allday_flg) {
            return true;
          }
          // 部屋と日付が該当するもののみ
          if (today <= reserve.end && reserve.start < tomorrow) {
            // 開始時間からtop座標取得
            let h = reserve.start.getHours();
            let m = reserve.start.getMinutes();
            if (reserve.start < startHour) {
              // 表示開始時刻より早ければ0
              reserve.top = 0;
            } else {
              // 開始時刻からtop座標を算出
              reserve.top = ((h - self.startHour) * self.hourHeight) + (self.hourHeight * (m / self.unitMinutes) / self.separate);
            }
            // 表示領域をオーバーしてる場合は上書き
            if (startHour > reserve.start) {
              reserve.start = startHour;
              reserve.startFlg = false;
            }
            if (endHour < reserve.end) {
              reserve.end = endHour;
              reserve.endFlg = false;
            }
            // 開始～終了までの分を取得して高さを求める
            let time = (reserve.end - reserve.start) / 60000;
            reserve.height = self.unitHeight * (time / self.unitMinutes);
            reserve.left = `${100 * (reserve.order / (reserve.overlap))}%`;
            if (reserve.overlap > 0) {
              if (reserve.overlap !== reserve.order + 1) {
                reserve.width = `calc(${Math.floor(100 * (1 / (reserve.overlap)))}% + 10px)`;
              } else {
                reserve.width = `calc(${Math.floor(100 * (1 / (reserve.overlap)))}% - 10px)`;
              }
            } else {
              reserve.width = `calc(100% - 10px)`;
            }
            reserves.push(reserve);
          }
        });
        return reserves;
      },
      /*
       * 部屋IDに対応する予約情報に、DOM表示に必要な要素を加えて返す
       */
      getReserveInfoAllday(unitId) {
        const self = this;
        let reserves = [];
        let today = this.date;
        let tomorrow = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
        $.each(this.reserveHash[unitId], function (idx, reserve) {
          // 全日フラグがOFFのデータは除外
          if (!reserve.allday_flg) {
            return true;
          }
          // 部屋と日付が該当するもののみ
          if (today <= reserve.end && reserve.start < tomorrow) {
            reserves.push(reserve);
          }
        });
        let maxHeight = (reserves.length + 1) * 20;
        if (maxHeight > self.alldayHeight) {
          self.alldayHeight = maxHeight;
        }
        return reserves;
      },
      /*
       * 予約処理
       */
      reserve(dataId, start, end, alldayFlg) {
        // 親コンポーネント（App）に予約ダイアログの起動イベントを渡す
        this.$emit("openNewReserveDialog", parseInt(dataId, 10), start, end, alldayFlg, this.dragElement);
        // 初期化する
        this.dragElement = null;
        this.dragStartEvt = null;
        this.dragEndEvt = null;
      },
      /*
       * 予約処理（既存データ）
       */
      viewReserve(reserveInfo) {
        // 親コンポーネント（App）に予約ダイアログの起動イベントを渡す
        this.$emit("openReserveDialog", reserveInfo);
      },
      scrollFn(e) {
        $('#table-fixed-column-wrapper').scrollTop($('#table-body-wrapper').scrollTop());
        $('#table-fixed-header-wrapper').scrollLeft($('#table-body-wrapper').scrollLeft());
      },
      changeRgba(color) {
        let r, g, b;
        r = parseInt(color.substr(1, 2), 16);
        g = parseInt(color.substr(3, 2), 16);
        b = parseInt(color.substr(5, 2), 16);
        return `rgba(${r},${g},${b}, 0.5)`;
      }
    }
  }
</script>

<style>
</style>