<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php $this->load->view('import'); ?>
</head>

<style>
    html body{
        height: 100vh;
        width: 100vw;
        font-family: 'Montserrat';
        transition: 1s;
    }

    img {
        width: 75%;
    }
    h6{
        transition: 1s;
        font-weight: lighter;
        font-size: 18px;
        transition: 1s;
    }

    #login{
        color: #FF3DC5;
    }

    div{
        transition: 1s;
    }

    button{
        transition: 1s;
    }

</style>

<body>
    <div id="login" class="pt-5">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">

                        <form id="login-form" class="form text-center" action="" method="POST">

                            <img class="text-center mb-5" src="<?php echo base_url() ?>assets/Logo1080.png">
                            <h3 class="text-center mb-3">Login to Continue</h3>

                            <div class="form-group text-left">
                                <label class="text-left" for="username">Username:</label><br>
                                <input type="text" name="userlogin" id="username" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group text-left">
                                <label class="text-left" for="password">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="fluid-container bg-danger rounded">
                                <h6 class="text-center" style="color: white"><?php echo $this->session->flashdata('error_msg'); ?></h6>
                            </div>
                            <div class="form-group text-center mt-5 ml-5 mr-5 ">
                                <button class="btn btn-block" type="submit" style="background-color: #FF3DC5; color: white">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script>
    $('h6').on('change', function() {
        $("h6").addClass("pt-5 pb-5");
    });
    
    $(function () {
        $('form').on('submit', function (e) {
            
            e.preventDefault();
            
            $.ajax({
            type: 'post',
            url: '<?php echo base_url() ?>login/checkLogin',
            data: $('form').serialize(),
            
                success: function(data) {
                    $("h6").html(data);

                    if (data == 'Logged In Successfully')
                        location.href = "<?php echo base_url()?>dashboard";
                },

            });
        });
    });
</script>

</html>