<template>
  <el-dialog :title="title" :visible.sync="visible" :before-close="onBeforeClose" v-draggable>
    <div>
      <el-table :data="records" style="width: 100%" height="300" border>
        <el-table-column v-for="col in cols"
          :key="col.name"
          :prop="col.name"
          :label="col.exName"
          :width="col.width"
          :resizable="true"
          :sortable="true">
        </el-table-column>
      </el-table>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close')"><span class="glyphicon glyphicon-remove"></span>閉じる</el-button>
    </span>
  </el-dialog>
</template>

<script>
  const TABLE_DEFINITIONS = {
    departments: {
      name: "部署",
      columns: [{
        name: "department_id",
        exName: "部署ID",
        width: 180
      }, {
        name: "department_name",
        exName: "部署名"
      }]
    },
    users: {
      name: "ユーザ",
      columns: [{
        name: "account_id",
        exName: "ユーザID"
      }, {
        name: "name",
        exName: "ユーザ名"
      }, {
        name: "email",
        exName: "メールアドレス"
      }, {
        name: "phone",
        exName: "電話番号"
      }, {
        name: "department_name",
        exName: "所属部署"
      }]
    },
    facilities: {
      name: "設備",
      columns: [{
        name: "facility_id",
        exName: "設備ID",
        width: 180
      }, {
        name: "facility_name",
        exName: "設備名"
      }, {
        name: "facility_type_name",
        exName: "設備種別"
      }]
    }
  };
  export default {
    props: [
      "departments",
      "facilities",
      "users",
      "type"
    ],
    data() {
      return {
        title: "",
        cols: [],
        records: [],
        visible: true
      };
    },
    created() {
      this.cols = TABLE_DEFINITIONS[this.type].columns;
      this.title = TABLE_DEFINITIONS[this.type].name;
      this.records = this[this.type];
      console.log("created ListDialog!");
    },
    mounted() {
      console.log("mounted ListDialog!");
    },
    updated() {
      console.log("updated ListDialog!");
    },
    methods: {
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