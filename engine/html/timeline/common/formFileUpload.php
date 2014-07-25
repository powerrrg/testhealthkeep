<div class="fileupload fileupload-new" data-provides="fileupload">
  <span class="btn btn-file btn-blue"><span class="fileupload-new">Select file</span>
  <span class="fileupload-exists">Change</span><input type="file" name="file" id="file" /></span>
  <span class="fileupload-preview"></span>
  <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
</div>
<?php
$needFupload=1;
$onload.="$('#file').bind('change', function() {
	if(this.files[0]!=undefined && this.files[0].size>2097152){
		alert('The file cannot have more than 2 MB in size');
		$('.fileupload').fileupload('clear');
  	}
});";
?>