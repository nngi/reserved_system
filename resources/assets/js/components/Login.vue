<template>
  <div class="login">
    <div>
      <div>ユーザー名</div>
      <el-input placeholder="ユーザー名" v-model="user_name"></el-input>
    </div>
    <div>
      <div>パスワード</div>
      <el-input placeholder="パスワード" type="password" v-model="password"></el-input>
    </div>
    <div>
      <el-button @click="login">ログイン</el-button>
    </div>
    <div><el-button @click="forget" type="text">パスワードを忘れた場合</el-button></div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        user_name: "",
        password: "",
      };
    },
    updated() {
      console.log("Login update!!");
    },
    methods: {
      login() {
        console.log(this.user_name, this.password);
      },
      forget() {
        console.log(this.user_name, this.password);
      }
    }
  }
</script>