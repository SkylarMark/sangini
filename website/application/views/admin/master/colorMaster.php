<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php $this->load->view('admin/modules/modals/color/add') ?>
    <?php $this->load->view('admin/modules/modals/color/update') ?>

</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
        <main class="page-content">
            <div class="container-fluid">
                <!-- Build Here -->
                <h3>Color Master</h3>
                <hr>
                <div class="container-fluid bg-danger rounded text-center pt-2 pb-2" style="font-size: 15px; color: white;">
                    <span style="font-weight:800">Warning !</span> Think Before Doing Anything to this Table
                </div>
                <hr>
                <table class="table d-table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="2" data-toggle="modal" data-target="#addcolor">Add Color<i class="fas fa-plus ml-2"></i></th>
                            <th class="btn-info text-center" colspan="2" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Color ID</th>
                            <th>Color Name</th>
                            <th>Color Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
					<tbody id="table_body"></tbody>
			    </table>
                <!-- Build Before This -->
            </div>
        </main>
    </div>
</body>

<script>

    $(document).ready(function() {
		load_table_data();
    });

    function load_table_data() {
        
            $.ajax({
            url: "<?php echo base_url().'master/getcolorData' ?>",
            type: "POST",

            success: function(data) {
                  $('#table_body').html(data);
            },
            error: function(request, status, error) {
                  alert(request.responseText);
            }
      });
    }

    function updateProduct(id){

        console.log('Updating Color with ID : '+id);
        $('#updatecolor').modal('show'); 
        $('#color_id').attr('value', id);
    }

    function deleteProduct(id) {

            console.log('Deleting Product with ID : '+id);

            var dataString = {
                'table': 'product',
                'type' : 'delete',
                "color_id" : id,
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>master/deletecolorData',
                data: dataString,

                success: function(data){
                    load_table_data();
                    
                },
                error: function(request, status, error) {
                    alert(request.responseText);
                    
                }
            });
    }

    // Update Product
    $("#updatecolorform").submit(function(event){

    event.preventDefault();

    var color_id = document.getElementById('color_id').value;
    var color_name = document.getElementById('update_color_name').value;
    var color_code = document.getElementById('update_color_code').value;

    if ($.trim(color_name) === "" || $.trim(color_code) === "") {
        document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
    }

    var dataString = {
            'color_id': color_id,
            'color_name': color_name,
            'color_code': color_code,
        }

        $.ajax({
            url: "<?php echo base_url().'master/updatecolorData' ?>",
            type: "POST",
            data: dataString,

            success: function(data) {
                $('#updatecolor').modal('hide');
                console.log('Updated Product ID : '+color_id+' and '+color_name+ ' and ' +color_code);
                load_table_data();
                $('#update_color_name').val('');
                $('#update_color_code').val('');

            },

            error: function(request, status, error) {
                    alert(request.responseText);
            }

        });
    });

    // Add Product
    $("#addcolorform").submit(function(event){

    event.preventDefault();

    var color_name = document.getElementById('add_color_name').value;
    var color_code = document.getElementById('add_color_code').value;

    if ($.trim(color_name) === "" || $.trim(color_code) === "") {
        document.getElementById('modalerror_add').innerHTML = 'Please Enter Some Value';
        return false;
    }
    
    var dataString = {
            'color_name': color_name,
            'color_code': color_code,
        }

        $.ajax({
            url: "<?php echo base_url().'master/addcolorData' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#addcolor').modal('hide');
                console.log('Added Color Named : '+ color_name + ' and ' + color_code);
                load_table_data();
                $('#add_color_name').val('');
                $('#add_color_code').val('');

            },
            error: function(request, status, error) {
                    alert(request.responseText);
            }
        });
    });
</script>

</html>