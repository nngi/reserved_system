<template>
  <div class="side-menu">
    <table class="table">
      <tr>
        <div class="menu-header">表示単位</div>
        <div class="menu-body">
          <el-radio class="radio" v-model="displayUnitId" label="1" @change="$emit('changeDisplayUnit', '1')">設備</el-radio>
          <el-radio class="radio" v-model="displayUnitId" label="2" @change="$emit('changeDisplayUnit', '2')">ユーザ</el-radio>
        </div>
      </tr>
      <tr><td class="empty-row"></td></tr>
      <tr>
        <calender v-bind:date="new Date()" @selectCalender="$emit('selectCalender', arguments[0])"></calender>
      </tr>
      <tr><td class="empty-row"></td></tr>
      <tr>
        <div class="menu-header">マスタメンテナンス</div>
        <div class="menu-body">
          <div><el-button @click="$emit('openListDialog', 'users')">ユーザー一覧</el-button></div>
          <div><el-button @click="$emit('openListDialog', 'facilities')">設備一覧</el-button></div>
          <div><el-button @click="$emit('openListDialog', 'departments')">部署一覧</el-button></div>
          <div><el-button @click="moveUserCreate">新規登録</el-button></div>
          <div><el-button>休日定義</el-button></div>
        </div>
      </tr>
      <tr><td class="empty-row"></td></tr>
    </table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        displayUnitId: "1"
      };
    },
    methods: {
      moveUserCreate() {
        location.href = 'user/create';
      }
    }
  }
</script>