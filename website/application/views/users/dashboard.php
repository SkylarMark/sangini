<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php $this->load->view('import'); ?>
</head>
<body>
    Welcome to User Dashboard. This Service Hasent Benn Enabled Yet. Please Contact Administrator if this is a problem.
    <button class="btn btn-danger" onclick="logout();">Logout</button>
</body>

<script>
    function logout() {
        location.href = "<?php echo base_url()?>login/logout";
    }
</script>
</html>