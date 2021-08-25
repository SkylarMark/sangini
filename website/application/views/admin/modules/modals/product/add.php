<!-- Modal -->
<div class="modal fade" id="addproduct" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="addproductform" action="">

            <div class='container-fluid mt-3'>
                <label for="product_id">Product Name</label>
                <input class="form-control" type="text" name="product_name" id="add_product_name" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Add Product</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>