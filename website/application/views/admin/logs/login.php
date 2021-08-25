<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
    <main class="page-content">
        <div class="container-fluid">
            <h2>Login Log</h2>
            <hr>      
            <table class="table d-table table-responsive">
				    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Current Status</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Operating System</th>
                            <th>Role</th>
                        </tr>
                    </thead>
					<tbody id="table_body"></tbody>
			    </table>
            <!-- Build Before This -->
        </div>
    </main>
</div>
</body>
</html>

<script>

    $(document).ready(function() {
		load_table_data();

        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase().trim();
            $("#table_body tr").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
      });

    });

    function load_table_data() {

            var dataString = {
                'show': 10,
            }

            $.ajax({
            url: "<?php echo base_url().'logs/loadlogin/1' ?>",
            type: "POST",
            data: dataString,

            success: function(data) {
                  $('#table_body').html(data);
            },
            error: function(request, status, error) {
                  alert(request.responseText);
            }
      });
    }

    function load_table_paged(page, change, max, show) {
        
        if (change == 0)
            page = page - 1;
        else if (change == 1)
            page = parseInt(page) + parseInt(1);
        
        if (page == 0)
            return false;
        
        if (page > max)
            return false;

        if(show == 0)
            show = document.getElementById('selectShowRows').value;

        console.log(show);

        var dataString = {
            'show': parseInt(show),
        }

        url = "<?php echo base_url().'logs/loadlogin/'?>"+page;

        $.ajax({
        url: url,
        type: "POST",
        data: dataString,

        success: function(data) {
              $('#table_body').html(data);
        },
        error: function(request, status, error) {
              alert(request.responseText);
        }
        });

    }
</script>