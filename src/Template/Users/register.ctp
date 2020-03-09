

<div style="margin: 200px;">
    <?= $this->Form->create(null, ['url'=>['controller'=>'Users','action'=>'register'], 'method'=>'post', 'id'=>'registerForm']) ?>
        <h2>Registration Form</h2>

        <label for="r1" id="fn">First Name :</label>
        <input type="text" name="first_name" class="form-control" value=""><br/>

        &nbsp;<label for="r2" >Last Name :</label>
        <input type="text" name="last_name" class="form-control" value=""><br/>
        &nbsp;&nbsp;&nbsp;<label for="r5">Email :</label>
        <input type="text" name="email" class="form-control" value=""><br/>

        &nbsp;&nbsp;&nbsp;<label for="r4" >Password :</label>
        <input type="password" name="password" class="form-control" value=""><br/>

        &nbsp;&nbsp;&nbsp;<label for="r4" >Conform Password :</label>
        <input type="password" name="cpassword" value="" class="form-control"><br/>

        <button type="submit" value="Submit" id="button">Register</button>
        <a href="login.html">Back to Home</a>

    </form>
</div>
<script>
    $(function () {
        $('#registerForm').validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                },
                cpassword: {
                    required: true,
                }
            },
            messages: {
                first_name: {
                    required: "Please enter first name",
                },
                last_name: {
                    required: "Please enter last name",
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email",
                },
                password: {
                    required: "Please enter password",
                },
                cpassword: {
                    required: "Please confirm password",
                }
            }

        });
    });
</script>

