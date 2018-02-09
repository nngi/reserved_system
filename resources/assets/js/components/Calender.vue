<template>
  <div class="calender">
    <div class="calender-year-month">
      <button type="button" v-on:click="prev"><span class="glyphicon glyphicon-chevron-left"></span></button>
      {{year}}年{{month}}月
      <button type="button" v-on:click="next"><span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>
    <table class="table calender-frame">
      <!-- ヘッダ -->
      <tr>
        <th class="calender-header weekday-sun">日</th>
        <th class="calender-header weekday-mon">月</th>
        <th class="calender-header weekday-tue">火</th>
        <th class="calender-header weekday-wed">水</th>
        <th class="calender-header weekday-thu">木</th>
        <th class="calender-header weekday-fri">金</th>
        <th class="calender-header weekday-sat">土</th>
      </tr>
      <tr v-for="row in days.length">
        <td v-for="col in 7" class="calender-day" v-bind:class="{
          'weekday-sun': col == 1,
          'weekday-mon': col == 2,
          'weekday-tue': col == 3,
          'weekday-wed': col == 4,
          'weekday-thu': col == 5,
          'weekday-fri': col == 6,
          'weekday-sat': col == 7,
          'today': days[row - 1][col - 1].date.toLocaleDateString() == today.toLocaleDateString()
        }" @click="selectCalender(days[row - 1][col - 1].date)">
          {{days[row - 1][col - 1].date.getDate()}}
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
  export default {
    props: [
      "date"
    ],
    data() {
      return {
        year: 1990,
        month: 1,
        firstDate: null,
        lastDate: null,
        days: [],
        today: null,  // 選択されている日付
        todayY: null, // 選択されている日付の年
        todayM: null, // 選択されている日付の月
        todayD: null  // 選択されている日付の日
      };
    },
    created() {
      this.today = this.date;
      this.todayY = this.today.getFullYear();
      this.todayM = this.today.getMonth() + 1;
      this.todayD = this.today.getDate();
      this.getDate(this.todayY, this.todayM);
    },
    updated() {
      console.log("Calender update!!");
    },
    methods: {
      // 日付情報を取得
      prev() {
        if (this.month === 1) {
          this.month = 12;
          this.year--;
        } else {
          this.month--;
        }
        this.getDate(this.year, this.month);
        this.$nextTick(function () {
          console.log("update prev", this.year, this.month);
        });
      },
      next() {
        if (this.month === 12) {
          this.month = 1;
          this.year++;
        } else {
          this.month++;
        }
        this.getDate(this.year, this.month);
        this.$nextTick(function () {
          console.log("update next", this.year, this.month);
        });
      },
      // 日付情報を取得
      getDate(y, m) {
        let date = this.date;
        if (isFinite(y) && isFinite(m)) {
          date = new Date(y, m - 1, 1);
        }
        this.year = date.getFullYear();
        this.month = date.getMonth() + 1;
        // その月の初日と最終日を取得
        this.firstDate = new Date(date.setDate(1));
        date.setMonth(date.getMonth() + 1);
        this.lastDate = new Date(date.setDate(0));
        // カレンダーに表示する日付のリスト
        this.days = [];
        let week = [];
        let day = 1;
        // 表示範囲は6週分なので初日の1週間分、前月の日付を入れる
        let youbi = this.firstDate.getDay();
        // 日曜から始まる場合に前月の日付が入らないのを防ぐ
        if (youbi === 0) youbi = 7;
        for (let i = youbi; i > 0; i--) {
          week.push({
            date: this.computeDate(this.year, this.month, 1, -i),
            current: false
          });
        }
        while (week.length < 7) {
          week.push({
            date: new Date(this.year, this.month - 1, day),
            current: true
          });
          day++;
        }
        this.days.push(week);
        // それ以降は順番に入れていく
        week = [];
        let currentFlg = true;
        let tmpDate = new Date(this.year, this.month - 1, day);
        for (let i = day; this.days.length < 6; i++) {
          week.push({
            date: new Date(tmpDate.getFullYear(), tmpDate.getMonth(), i),
            current: currentFlg
          });
          if (week.length >= 7) {
            this.days.push(week);
            week = [];
          }
          // 最終日を過ぎたら翌月分も
          if (i === this.lastDate.getDate()) {
            i = 0;
            currentFlg = false;
            tmpDate = this.computeDate(this.year, this.month, this.lastDate.getDate(), 1);
          }
        }
      },
      // 日付情報を取得
      computeDate(year, month, day, addDays) {
        let dt = new Date(year, month - 1, day);
        let baseSec = dt.getTime();
        let addSec = addDays * 86400000; // 日数 * 1日のミリ秒数
        let targetSec = baseSec + addSec;
        dt.setTime(targetSec);
        return dt;
      },
      // 日付情報を取得
      selectCalender(date) {
        this.today = date;
        this.$emit("selectCalender", date);
      }
    }
  }
</script>

<style>
</style>