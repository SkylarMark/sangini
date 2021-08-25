<!-- Modal -->
<div class="modal fade" id="addcolor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Color</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" method="post" id="addcolorform" action="">

            <div class='container-fluid mt-3'>
                <label for="color_id">Color Name</label>
                <input class="form-control" type="text" name="color_name" id="add_color_name" autocomplete="off">
            </div>

            <div class='container-fluid mt-3'>
                <label for="color_id">Color Code</label>
                <input class="form-control" type="text" name="color_code" id="add_color_code" autocomplete="off">
            </div>

            <div class='container-fluid mt-5'>
                <button type="submit" class="btn btn-success btn-sm btn-block">Add Color</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>