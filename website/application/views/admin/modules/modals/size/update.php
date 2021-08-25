<!-- Modal -->
<div class="modal fade" id="updatesize" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Size Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="updatesizeform">

            <input type="hidden" class="form-control" type="text" name="size_name" id="size_id">
            
            <div class='container-fluid mt-3'>
                <label for="size_id">Size Code</label>
                <input class="form-control" type="text" name="size_name" id="update_size_code" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Update Size</button>
            </div>
            <hr>
            <div class="text-footer text-center">
              <h6 class="container-fluid mt-2" id="modalerror_update"></h6>
              <span id="updateValue"></span>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>