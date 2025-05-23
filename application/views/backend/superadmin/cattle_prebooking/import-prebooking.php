<p class="mb-0 text-danger"><b>Note :</b></p>
<p class="mb-0">1. Upload csv file only.</p>
<p class="mb-0">1. <b>Doctor name </b>must match the names in our system exactly.</p>
<p class="mb-0">2. <b>Agent (Dawanwala) name </b>must match the names in our system exactly.</p>
<p class="mb-0">3. All <b>rows</b> must be filled correctly in csv.</p>
<p class="mb-0">4. CSV rows must be below <b>500</b>.</p>
<p class="mb-0">5. Make sure to <b>donwload</b> the uploading report given by <b>system</b>.</p>

<hr>
<a id="" href="<?php echo base_url("/uploads/prebooking-csv-response/sample-prebooking-format.csv?time=").time(); ?>" download="sample-prebooking-format.csv">Download Sample File</a>
<hr>

<form id="register3" action="<?php echo route('cattle_prebooking/register3'); ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12 mb-1">
            <b>Upload prebooking csv</b>
        </div>
        <div class="col-md-4 mb-2">
            <input class="form-control" type="file" name="csv_file" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-block btn-success">
                Upload</button>
        </div>
    </div>
</form>

<script>
$("#register3").validate({}); // Jquery form validation initialization
$("#register3").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, resHandler);
});

function resHandler(res){
    var url = res.downloadurl; //"https://cattle.deonarmcgm.com/uploads/prebooking-csv-response/2024-06-04@1857PM@tag_no.txt";
    var link = document.createElement('a');
    link.href = url;
    link.download = res.downloadtxtname;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    filter();
}
</script>