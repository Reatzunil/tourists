<div class="container-fluid">
    <form id="book-form" enctype="multipart/form-data"> <!-- Add enctype="multipart/form-data" for file upload -->
        <div class="form-group">
            <input name="package_id" id="package_id" type="hidden" value="<?php echo $_GET['package_id'] ?>" >
            <label for="start-date">Start Date:</label>
           <input type="date" class="form-control" required name="start-date">
           <label for="end-date">End Date:</label>
           <input type="date" class="form-control" required name="end-date">
        </div>
        <div class="form-group"> <!-- Add a new form group for file upload -->
            <label for="photo">Upload ID for verification</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            <button type="button" class="btn btn-primary mt-3" id="upload-photo">Upload Photo</button>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#book-form').submit(function(e){
            e.preventDefault();
            if (!$('#photo').val()) {
                alert_toast("Please upload a photo", 'warning');
                return;
            }
            if (new Date($('#start-date').val()) > new Date($('#end-date').val())) {
                alert_toast("End date should be later than start date", 'warning');
                return;
            }
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=book_tour",
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                error: function(err){
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        alert_toast("Book Request Successfully sent.");
                        $('.modal').modal('hide');
                }else{
                    console.log(resp);
                    alert_toast("An error occurred", 'error');
                }
                end_loader();
            }
        });
    });

        // Function to handle photo upload
        $('#upload-photo').click(function(){
            var formData = new FormData();
            var fileInput = $('#photo')[0].files[0];
            formData.append('photo', fileInput);

            $.ajax({
                url: _base_url_ + "upload_photo.php", // Change the URL to your photo upload endpoint
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    // Handle success
                    console.log("Photo uploaded successfully");
                    alert_toast("Photo uploaded successfully", 'success');
                },
                error: function(err){
                    // Handle error
                    console.log(err);
                    alert_toast("An error occurred while uploading photo", 'error');
                }
            });
        });
    });
</script>