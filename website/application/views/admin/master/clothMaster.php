<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php $this->load->view('admin/modules/modals/cloth/add') ?>
    <?php $this->load->view('admin/modules/modals/cloth/update') ?>
</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
        <main class="page-content">
            <div class="container-fluid">
                <!-- Build Here -->
                <h3>Cloth Master</h3>
                <hr>
                <div class="container-fluid bg-danger rounded text-center pt-2 pb-2" style="font-size: 15px; color: white;">
                    <span style="font-weight:800">Warning !</span> Think Before Doing Anything to this Table
                </div>
                <hr>
                <table class="table d-table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="2" data-toggle="modal" data-target="#addcloth">Add Cloth<i class="fas fa-plus ml-2"></i></th>
                            <th class="btn-info text-center" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Cloth ID</th>
                            <th>Cloth Name</th>
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
            url: "<?php echo base_url().'master/getclothData' ?>",
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

        console.log('Updating Cloth with ID : '+id);

        $('#updatecloth').modal('show'); 
        $('#cloth_id').attr('value', id);
    }

    function deleteProduct(id) {

            console.log('Deleting Cloth with ID : '+id);

            var dataString = {
                "cloth_id" : id,
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>master/deleteclothData',
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
    $("#updateclothform").submit(function(event){

    event.preventDefault();

    var cloth_id = document.getElementById('cloth_id').value;
    var cloth_name = document.getElementById('update_cloth_name').value;

    if ($.trim(cloth_name) === "") {
        document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
    }

    var dataString = {
            'cloth_id': cloth_id,
            'cloth_name': cloth_name,
        }

        $.ajax({
            url: "<?php echo base_url().'master/updateclothData' ?>",
            type: "POST",
            data: dataString,

            success: function(data) {
                $('#updatecloth').modal('hide');
                console.log('Updated Product ID : '+cloth_id+' and '+cloth_name);
                load_table_data();
                $('#update_cloth_name').val('');

            },

            error: function(request, status, error) {
                    alert(request.responseText);
            }

        });
    });

    // Add Product
    $("#addclothform").submit(function(event){

        event.preventDefault();

        var cloth_name = document.getElementById('add_cloth_name').value;

    if ($.trim(cloth_name) === "") {
        document.getElementById('modalerror_add').innerHTML = 'Please Enter Some Value';
        return false;
    }

    var dataString = {
            'cloth_name': cloth_name,
        }

        $.ajax({
            url: "<?php echo base_url().'master/addclothData' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#addcloth').modal('hide');
                console.log('Added Product Named : '+cloth_name);
                load_table_data();
                $('#add_cloth_name').val('');

            },
            error: function(request, status, error) {
                    alert(request.responseText);
            }
        });
    });
</script>

</html>