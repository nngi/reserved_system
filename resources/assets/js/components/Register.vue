<template>
  <div class="register">
    <div>
      <div>ユーザー名</div>
      <el-input placeholder="ユーザー名" v-model="name"></el-input>
    </div>
    <div>
      <div>メールアドレス</div>
      <el-input placeholder="メールアドレス" v-model="email"></el-input>
    </div>
    <div>
      <div>パスワード</div>
      <el-input placeholder="パスワード" type="password" v-model="password"></el-input>
    </div>
    <div>
      <div>パスワード（確認）</div>
      <el-input placeholder="パスワード" type="password" v-model="passwordConfirm"></el-input>
    </div>
    <div>
      <el-button @click="register">新規登録</el-button>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        name: "",
        email: "",
        password: "",
        passwordConfirm: "",
      };
    },
    updated() {
      console.log("Register update!!");
    },
    methods: {
      register() {
        let self = this;
        let param = {
          name: this.name,
          email: this.email,
          password: this.password
        };
        axios.post('/api/register', param).then(response => {
          console.log(response);
        }).catch(error => {
          console.log(error);
        });
      }
    }
  }
</script>