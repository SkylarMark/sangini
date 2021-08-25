<!-- Modal -->
<div class="modal fade" id="updatecolor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Color Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="updatecolorform">

            <input type="hidden" class="form-control" type="text" name="color_name" id="color_id">
            
            <div class='container-fluid mt-3'>
                <label for="product_id">Color Name</label>
                <input class="form-control" type="text" name="color_name" id="update_color_name" autocomplete="off">
            </div>

            <div class='container-fluid mt-3'>
                <label for="product_id">Color Code</label>
                <input class="form-control" type="text" name="color_name" id="update_color_code" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Update Color</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>