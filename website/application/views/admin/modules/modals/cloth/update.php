<!-- Modal -->
<div class="modal fade" id="updatecloth" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Cloth Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="updateclothform">

            <input type="hidden" class="form-control" type="text" name="cloth_name" id="cloth_id">
            
            <div class='container-fluid mt-3'>
                <label for="product_id">Cloth Name</label>
                <input class="form-control" type="text" name="cloth_name" id="update_cloth_name" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Update Cloth</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>