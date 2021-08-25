<!-- Modal -->
<div class="modal fade" id="productlist" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="addproductlist" action="">

        <div class="form-group">
            <label for="sel1">Product Name:</label>
            <select class="form-control" id="name_list">
                <option value="" disabled selected>Select your option</option>
                <?php foreach($product_id as $index => $value)
                { 
                    echo '<option value="'.$product_id[$index].'">'.$product_name[$index].'</option>';
                } 
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="sel1">Product Cloth:</label>
            <select class="form-control" id="cloth_list">
                <option value="" disabled selected>Select your option</option>
                <?php foreach($product_cloth_id as $index => $value)
                { 
                    echo '<option value="'.$product_cloth_id[$index].'">'.$product_cloth_name[$index].'</option>';
                } 
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="sel1">Product Color:</label>
            <select class="form-control" id="color_list">
                <option value="" disabled selected>Select your option</option>
                <?php foreach($product_color_id as $index => $value)
                { 
                    echo '<option value="'.$product_color_id[$index].'">'.$product_color_name[$index].'</option>';
                } 
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="sel1">Product Size:</label>
            <select class="form-control" id="size_list">
                <option value="" disabled selected>Select your option</option>    
                <?php foreach($product_size_id as $index => $value)
                { 
                    echo '<option value="'.$product_size_id[$index].'">'.$product_size_name[$index].'</option>';
                } 
                ?>
            </select>
        </div>

        <div class='form-group mt-3'>
            <label for="size_id">Price</label>
            <input class="form-control" type="text" name="price" id="add_price" autocomplete="off">
        </div>
        
        <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Add Product</button>
        </div>
        <hr>
        <div class="container-fluid mt-2 text-center" id="modalerror_add"></div>

        </form>
      </div>
    </div>
  </div>
</div>