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
            <h2>Discount Master</h2>
            <hr>
            <table class="table d-table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="2" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Username</th>
                            <th>Discount %</th>
                        </tr>
                    </thead>
					<tbody id="table_body"></tbody>
			    </table>
    </main>
</div>

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
            url: "<?php echo base_url().'master/getdiscountData/1' ?>",
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

        url = "<?php echo base_url().'master/getdiscountData/'?>"+page;

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

    function updateDiscount(username){

        var discount = parseInt(document.getElementById(username+'_discount').value);

        if (discount < 0)
        {
                alert('Discount cannot be Less Than "0"');
                return false;
        }

        var dataString = {
            'change_username': username,
            'discount': discount,
        }

        $.ajax({
            url: "<?php echo base_url().'master/updateDiscount' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#productlist').modal('hide');
                console.log('Price Updated to : '+discount+' On Username : '+username);
                show = document.getElementById('selectShowRows').value;
                page = document.getElementById('currentpage').innerText;
                max = document.getElementById('currentpagetomax').value;
                change = 2;
                load_table_paged(page, change, max, show);
            },
            error: function(request, status, error) {
                    alert(request.responseText);
            }
        });

        // End
    }

</script>

</body>
</html>