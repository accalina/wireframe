
<script src="https://unpkg.com/jquery"></script>
<script src="https://unpkg.com/vue"></script>

<div class="container">
    <form action="../api/register" method="POST">
        username: <input name="username" v-model="username" type="text"><br>
        password: <input name="password" v-model="password" type="password"><br>
        re-enter: <input type="password" v-model="password2"><br>
        <input v-if="password === password2 && password !=''" @click.prevent="sendData()" type="submit" value="Login">
        {{ data }}
    </form>
</div>

<script>
    app = new Vue({
        el: ".container",
        data: {
            username : "",
            password : "",
            password2 : "",
            data     : ""
        },
        methods: {
            sendData: function(){
                console.log("Sending Credential data to API")
                $.post("../api/register",{username: this.username, password: this.password},function(data){
                    document.location.href = "login";
                })
            }
        }
    })
</script>