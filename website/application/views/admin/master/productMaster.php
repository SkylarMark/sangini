<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php $this->load->view('admin/modules/modals/product/add') ?>
    <?php $this->load->view('admin/modules/modals/product/update') ?>
</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
        <main class="page-content">
            <div class="container-fluid">
                <!-- Build Here -->
                <h3>Product Master</h3>
                <hr>
                <div class="container-fluid bg-danger rounded text-center pt-2 pb-2" style="font-size: 15px; color: white;">
                    <span style="font-weight:800">Warning !</span> Think Before Doing Anything to this Table
                </div>
                <hr>
                <table class="table d-table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="2" data-toggle="modal" data-target="#addproduct">Add Product<i class="fas fa-plus ml-2"></i></th>
                            <th class="btn-info text-center" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
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
            url: "<?php echo base_url().'master/getproductData' ?>",
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

        console.log('Updating Product with ID : '+id);
        $('#updateproduct').modal('show'); 
        $('#product_id').attr('value', id);
        
    }

    function deleteProduct(id) {

            console.log('Deleting Product with ID : '+id);

            var dataString = {
                "product_id" : id,
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>master/deleteData',
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
    $("#updateproductform").submit(function(event){

    event.preventDefault();

    var product_id = document.getElementById('product_id').value;
    var product_name = document.getElementById('update_product_name').value;

    if (product_name.trim() === "") {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
    }

    var dataString = {
            'product_id': product_id,
            'product_name': product_name,
        }

        $.ajax({
            url: "<?php echo base_url().'master/updateData' ?>",
            type: "POST",
            data: dataString,

            success: function(data) {
                $('#updateproduct').modal('hide');
                console.log('Updated Product ID : '+product_id+' and '+product_name);
                load_table_data();
                $('#update_product_name').val('');
                

            },

            error: function(request, status, error) {
                    alert(request.responseText);
            }

        });
    });

    // Add Product
    $("#addproductform").submit(function(event){

    event.preventDefault();
    var product_name = document.getElementById('add_product_name').value;

    var dataString = {
            'product_name': product_name,
        }

        if (product_name.trim() === "") {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
            return false;
        }

        $.ajax({
            url: "<?php echo base_url().'master/addData' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#addproduct').modal('hide');
                console.log('Added Product Named : '+product_name);
                load_table_data();
                $('#add_product_name').val('');

            },
            error: function(request, status, error) {
                    alert(request.responseText);
            }
        });
    });
</script>

</html>