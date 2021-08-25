<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        html body{
            transition: 1s !important;
        }
    </style>
</head>
<body>
    <?php $this->load->view('admin/modules/menu') ?>
        <main class="page-content">
            <div class="container-fluid">
                <!-- Build Here -->
                <h3>Product List Master</h3>
                <hr>
                <div class="container-fluid bg-danger rounded text-center pt-2 pb-2" style="font-size: 15px; color: white;">
                    <span style="font-weight:800">Warning !</span> Think Before Doing Anything to this Table
                </div>
                <hr>
                <table class="table d-table table-responsive pagination" data-pagecount="2">
                    <thead class="thead-light">
                        <tr>
                            <th class="btn-info text-center" colspan="3" data-toggle="modal" data-target="#productlist">Add Product<i class="fas fa-plus ml-2"></i></th>
                            <th class="btn-info text-center" colspan="3" onclick="load_table_data()">Reload Table<i class="fas fa-sync-alt ml-2"></i></th>
                            <th>
                                <form class="form-inline my-2 my-lg-0">
                                    <input name="searchbar" class="form-control mr-sm-2" onclick="load_table_paged(1, 2, 1, 9999);" type="search" placeholder="Search" aria-label="Search" id="search" autocomplete="off">
                                </form>
                            </th>
                        </tr>
					</thead>
				    <thead class="thead-dark">
                        <tr>
                            <th>Product List ID</th>
                            <th>Product Name</th>
                            <th>Product Cloth</th>
                            <th>Product Color</th>
                            <th>Product Size</th>
                            <th>Price</th>
                            <!-- Quantity From Database -->
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody id="table_body"></tbody>
			    </table>
                <!-- Build Before This -->
            </div>
        </main>
    </div>
</body>

<?php $this->load->view('admin/modules/modals/productlist') ?>

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
            url: "<?php echo base_url().'commerce/loadtable/1' ?>",
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

        url = "<?php echo base_url().'commerce/loadtable/'?>"+page;

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


    function updatePrice(id){

        var price = parseInt(document.getElementById(id+'_price').value);

        if (price < 0)
        {
                alert('Stock cannot be Less Than "0"');
                return false;
        }

        var dataString = {
            'id': id,
            'price': price,
        }

        $.ajax({
            url: "<?php echo base_url().'commerce/updatePrice' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#productlist').modal('hide');
                console.log('Price Updated to : '+price+' On ID : '+id);
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

    function updateStock(id){

        var cstock = parseInt(document.getElementById(id+'_cstock').innerText);
        var stock = parseInt(document.getElementById(id+'_stock').value);

        if (stock < 0)
        {
            if (stock + cstock < 0)
            {
                alert('Stock cannot be Less Than "0"');
                return false;
            }
        }


        var dataString = {
            'id': id,
            'stock': stock,
        }

        console.log(dataString);

        $.ajax({
            url: "<?php echo base_url().'commerce/updatestock' ?>",
            type: "POST",
            data: dataString,    
            success: function(data) {
                $('#productlist').modal('hide');
                console.log('Price Updated to : '+stock+' On ID : '+id);
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

    // Add Product
    $("#addproductlist").submit(function(event){

    event.preventDefault();

    var name = document.getElementById('name_list').value;
    var cloth = document.getElementById('cloth_list').value;
    var color = document.getElementById('color_list').value;
    var size = document.getElementById('size_list').value;
    var price = document.getElementById('add_price').value;
    

    if ((name.trim() === "") || (cloth.trim() === "") || (color.trim() === "") || (size.trim() === "") || (price.trim() === "") ) {          
        document.getElementById('modalerror_add').innerHTML = 'Some Entries are Missing';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 document.getElementById('modalerror_update').innerHTML = 'Please Enter Some Value';
        return false;
    }
    
    var dataString = {
        'type': 'add',
        'product_id': name,
        'cloth_id': cloth,
        'color_id': color,
        'size_id': size,
        'price': price,
    }

    $.ajax({
        url: "<?php echo base_url().'commerce/addproduct' ?>",
        type: "POST",
        data: dataString,
        dataType: 'json',  
            success: function(data) 
                {
                
                console.log('Product Name : '+name + ', Product Cloth : '+cloth+', Product Color :'+color+', Product Size : '+size+' with Price of : '+price+' Rs');
                if (data['error'])
                {
                    document.getElementById('modalerror_add').innerHTML = data['data']; 
                }
                else
                {
                    $('#productlist').modal('hide');
                    $('#add_price').val('');
                }
                show = document.getElementById('selectShowRows').value;
                page = document.getElementById('currentpage').innerText;
                max = document.getElementById('currentpagetomax').value;
                change = 2;
                load_table_paged(page, change, max, show);
                },
            error: function(request, status, error) 
                {
                        alert(request.responseText);
                }
        });
    });

</script>

</html>