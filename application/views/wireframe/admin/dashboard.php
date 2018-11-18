
<script src="https://unpkg.com/jquery"></script>
<script src="https://unpkg.com/vue"></script>

<div class="container">

    <div class="user_profile">
        <fieldset class="usr_profile">
            <legend>User Profile</legend>
            <h3>Welcome {{ username  }}</h3>
            <ul>
                <li>User Role : {{ role }}</li>
                <li>User Token : {{ token }}</li>
            </ul>
        </fieldset>
        <script>
            usr_profile = new Vue({
                el: ".usr_profile",
                data: {
                    username: "",
                    role    : "",
                    token   : "",
                },
                methods: {
                    getData: function(){
                        fetch('../api/login')
                        .then(response => response.json())
                        .then(api => {
                            this.username = api.profile.username
                            this.role = api.profile.role
                            this.token = api.profile.token
                        })
                    }
                },
                created(){
                    this.getData()
                }
            })
        </script>
    </div>


    <div class="user_manager">
        <fieldset class="usr_mgr">
            <legend>User Manager</legend>
            <h3>All User Profiles</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Token</th>
                        <th>Role</th>
                        <th>Management</th>
                        <th>Token</th>
                        <th>Roles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users">
                        <td>
                            <p @dblclick="editUser($event,user)" v-if="user.edit=='false'">{{ user.username }}</p>
                            <input @keyup.enter="editUser($event,user)" v-if="user.edit=='true'" type="text" v-model="user.username">
                        </td>
                        <td>
                            <p v-if="user.edit=='false'">{{ user.token }}</p>
                        </td>
                        <td>
                            <p v-if="user.edit=='false'">{{ user.role }}</p>
                        </td>
                        <td>
                            <button @click="editUser($event,user)" :title="hovertext.update">Update</button>
                            <button @click="deleteuser(user)" :title="hovertext.delete">Delete</button>
                        </td>
                        <td>
                            <button @click="generateToken(user)" :title="hovertext.genToken">Generate</button>
                            <button @click="revokeToken(user)" :title="hovertext.revoke">Revoke</button>
                        </td>
                        <td>
                            <button @click="escalate(user)" :title="hovertext.escalate">Escalate</button>
                            <button @click="descalate(user)" :title="hovertext.descalate">Descalate</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>
            <h3>Create new user</h3>
            Username : <input type="text" v-model="newUser"><br>
            Password : <input @keyup.enter="createUser()" type="password" v-model="newPassword"><br>
            <button @click="createUser()">Create user</button>
        </fieldset>
        <script>
            usr_profile = new Vue({
                el: ".usr_mgr",
                data: {
                    newUser: "",
                    newPassword: "",
                    users: [],
                    // Hover Text
                    hovertext: {
                        update  : "Update User Data",
                        delete  : "Delete User",
                        genToken: "Generate Token for User",
                        revoke  : "Revoke Token for User",
                        escalate: "Give Admin previlage to this user",
                        descalate: "Remove Admin previlage to this user",
                    }
                },
                methods: {
                    getData: function(){
                        fetch('../api/allUser')
                        .then(response => response.json())
                        .then(api => {
                            this.users = api.data;
                        })
                    },
                    createUser: function(){
                        proxy = this;
                        $.post("../api/register",{username: this.newUser, password: this.newPassword},function(data){
                            console.log(data);
                            proxy.getData();
                        })
                        this.newUser = "";
                        this.newPassword = "";
                    },
                    editUser:function(event,user){
                        if(user.edit==='false'){
                            user.edit='true';
                            event.target.textContent = "Save";
                        }else{
                            $.post("../api/renameUser",{no: user.no, name: user.username},function(data){
                                console.log(data);
                            })
                            user.edit='false';
                            event.target.textContent = "Update";
                        }
                    },
                    deleteuser: function(user){
                        proxy = this;
                        $.post("../api/deleteUser", { username: user.username }, function (data) {
                            console.log(data)
                            proxy.getData();
                        });
                    },
                    generateToken: function(user){
                        proxy = this;
                        $.post("../api/genToken",{username: user.username},function(data){
                            console.log(data)
                            proxy.getData();
                        });
                    },
                    revokeToken: function(user){
                        proxy = this;
                        $.post("../api/revokeToken", { username: user.username }, function (data) {
                            console.log(data)
                            proxy.getData();
                        });
                    },
                    escalate: function(user){
                        proxy = this;
                        $.post("../api/userEscalate", { username: user.username }, function (data) {
                            console.log(data)
                            proxy.getData();
                        });
                    },
                    descalate: function(user){
                        proxy = this;
                        $.post("../api/userDescalate", { username: user.username }, function (data) {
                            console.log(data)
                            proxy.getData();
                        });
                    },
                },
                created(){
                    this.getData()
                }
            })
        </script>
    </div>


</div>
