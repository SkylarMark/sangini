<!-- Modal -->
<div class="modal fade" id="addsize" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Size</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="addsizeform" action="">

            <div class='container-fluid mt-3'>
                <label for="size_id">Size Code</label>
                <input class="form-control" type="text" name="size_code" id="add_size_code" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Add Size</button>
            </div>
            <hr>
            <div class="container-fluid mt-2 text-center" id="modalerror_add"></div>

        </form>
      </div>
    </div>
  </div>
</div>