
<script src="https://unpkg.com/jquery"></script>
<script src="https://unpkg.com/vue"></script>

<div class="container">
    <h3 v-if="username != ''">Hello {{ username }}</h3>
    <form action="" method="POST">
        username: <input name="username" v-model="username" type="text"><br>
        password: <input name="password" v-model="password" type="password"><br>
        <input @click.prevent="sendData()" type="submit" value="Login">
        {{ data }}
    </form>
</div>

<script>
    app = new Vue({
        el: ".container",
        data: {
            username : "",
            password : "",
            data     : ""
        },
        methods: {
            sendData: function(){
                console.log("Sending Credential data to API")
                $.post("../api/login",{username: this.username, password: this.password},function(data){
                    this.data = data;
                    if (data === 'Login Success'){
                        document.location.href = "home";
                    }
                })
            }
        }
    })
</script>