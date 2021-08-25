<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php $this->load->view('admin/modules/modals/size/add') ?>
    <?php $this->load->view('admin/modules/modals/size/update') ?>
</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
        <main class="page-content">
            <div class="container-fluid">
                <!-- Build Here -->
                <h3>Size Master</h3>
                <hr>
                <div class="container-fluid bg-danger rounded text-center pt-2 pb-2" style="font-size: 15px; color: white;">
                    <span style="font-weight:800">Warning !</span> Think Before Doing Anything to this Table
                </div>
                <hr>
                <table class="table d-table table-responsive">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="2" data-toggle="modal" data-target="#addsize">Add Size<i class="fas fa-plus ml-2"></i></th>
                            <th class="btn-info text-center" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Size ID</th>
                            <th>Size Code</th>
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
            url: "<?php echo base_url().'master/getsizeData' ?>",
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
        console.log('Updating Size with ID : '+id);
        $('#updatesize').modal('show'); 
        $('#size_id').attr('value', id);
        $('#updateValue').html('Updating Value for ID : '+id);
    }

    function deleteProduct(id) {

            console.log('Deleting Size with ID : '+id);

            var dataString = {
                "size_id" : id,
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>master/deletesizeData',
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
    $("#updatesizeform").submit(function(event){
    event.preventDefault();
    var size_id = document.getElementById('size_id').value;
    var size_code = document.getElementById('update_size_code').value;      
    if (size_code.trim() === "") {
        document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
        }

        var dataString = {
                'size_id': size_id,
                'size_code': size_code,
            }

            $.ajax({
                url: "<?php echo base_url().'master/updatesizeData' ?>",
                type: "POST",
                data: dataString,

                success: function(data) {
                    $('#updatesize').modal('hide');
                    console.log('Updated Product ID : '+size_id+' and '+size_code);
                    load_table_data();
                    $('#update_size_code').val('');

                },

                error: function(request, status, error) {
                        alert(request.responseText);
                }

            });
        });

    // Add Product
    $("#addsizeform").submit(function(event){
    event.preventDefault();
    var size_code = document.getElementById('add_size_code').value;
    if (size_code.trim() === "") {          
        document.getElementById('modalerror_add').innerHTML = 'Please Enter Some Value';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
        }
    
        var dataString = {
            'size_code': size_code,
        }

        $.ajax({
            url: "<?php echo base_url().'master/addsizeData' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#addsize').modal('hide');
                console.log('Added Size Named : '+size_code);
                load_table_data();
                $('#add_size_code').val('');

            },
            error: function(request, status, error) {
                    alert(request.responseText);
            }
        });
    });
</script>

</html>