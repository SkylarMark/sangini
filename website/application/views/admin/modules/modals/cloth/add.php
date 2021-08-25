<!-- Modal -->
<div class="modal fade" id="addcloth" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Cloth</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="addclothform" action="">

            <div class='container-fluid mt-3'>
                <label for="cloth_id">Cloth Name</label>
                <input class="form-control" type="text" name="cloth_name" id="add_cloth_name" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Add Cloth</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>