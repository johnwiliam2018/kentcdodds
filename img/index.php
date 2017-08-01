<?php
$page_title = "Home";
$page_slug = "home";
require ('library/admin_application_top.php');

require ('views/header.php');
?>

<!-- Chart.js -->
<script src="assets/plugins/Chart.min.js"></script>
<!-- bootstrap-progressbar -->
<script src="assets/plugins/bootstrap/js/bootstrap-progressbar.min.js"></script>

<!-- Dropzone.css -->
<link href="assets/plugins/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Form Upload </h3>
      </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Dropzone multiple file uploader</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>Drag multiple files to the box below for multi upload or click to select files. This is for demonstration purposes only, the files are not uploaded to any server.</p>
            <form action="image_upload.php" class="dropzone" enctype="multipart/form-data" id="frmImageUpload"></form>
            <br />
            <br />
            <br />
            <br />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<!-- Dropzone.js -->
<script src="assets/plugins/dropzone/dist/min/dropzone.min.js"></script>

<script>
$(function() {
Dropzone.options.frmImageUpload = {
  paramName: "image", // The name that will be used to transfer the file
  maxFilesize: 50, // MB
  acceptedFiles: "image/jpeg,image/png,image/jpg,image/gif",
  /*accept: function(file, done) {
    console.log(file);
    if (file.status == "error") {
      done("Failed upload.");
    } else if (file.status == "success") {
      var result = JSON.parse(file.xhr.response);
      
      if (result.status == 'error') {
        done(result.message);
      }
      
      done();
    }
  }*/
};
});
</script>

<?php
require ('views/footer.php');
