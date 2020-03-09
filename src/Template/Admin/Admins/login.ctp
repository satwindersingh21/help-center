<style type="text/css">
    input[type=text], input[type=password]{
        width:60%;
        padding:7px 10px;
        margin: 8px 0;
        display:inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;

    }

    button{
        background-color:#4CAF50;
        width: 40%;
        padding: 9px 5px;
        margin:5px 0;
        cursor:pointer;
        border:none;
        color:#ffffff;

    }

    button:hover{
        opacity:0.8;
    }

    #un,#ps{
        font-family:’Lato’, sans-serif;
        color: gray;
    }


</style>
<div id="container" style="margin: 200px;">
    <div class="left" style="width: 800px;">
    <?= $this->Form->create(null, ['url'=>['controller'=>'Admins','action'=>'login'], 'method'=>'post']) ?>

        <h2>Admin Login</h2>

        <label for="uname" id="un">Email:</label>
        <input type="text" name="email" placeholder="Email" id="uname"><br/><br/>
        <label for="password" id="ps">Password:</label>
        <input type="password" name="password" placeholder="Enter Password" id="upass"><br/><br/>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" value="Login"  id="submit">Login</button>
<br />
<br />
 <?= $this->Form->end() ?>
    </div>
    <div class="right" style=" float: left;  margin-top:50px">
        <img src="<?= SITE_URL; ?>img/unnamed.png" style="width: 300px;">
    </div>

</div>
