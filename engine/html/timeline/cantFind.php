<?php
$needModal=1;
$onload.="
$('#cantFind').click(function(){
	$('#myModal').modal();
});
$('#btnCant').click(function(){
	if($('#topicMissing').val()==''){
		alert('You need to enter something');
		$('#topicMissing').focus();
		return false;
	}else{
		$('#btnCant').hide();
		$('#modal-content').hide();
		$('#modal-loader').show();
		$.ajax({
			type: 'POST',
			url: '".WEB_URL."act/ajax/cantFind.php',
			data: { missing: $('#topicMissing').val(),what:'$cantFindWhat' },
			success: function(data) {
				$('#modal-loader').hide();
				$('#modal-content').html(data);
				$('#modal-content').show();
			}
		});
	}
});
$('#topicMissing').keypress(function(e){
    if ( e.which == 13 ){
    	$('#btnCant').click();
    	return false;
    }
});


";
?>
<div id="myModal" class="modal hide fade" style="line-height:1.4em;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Can't find a <?php echo $cantFindWhat; ?>?</h3>
  </div>
  <div class="modal-body">
    <p id="modal-content"><input type="text" id="topicMissing" class="input100" placeholder="Please enter the <?php echo $cantFindWhat; ?> that you can't find" /></p>
    <p id="modal-loader" style="display:none;" class="center"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" /></p>
  </div>
  <div class="modal-footer">
    <a class="btn btn-gray" data-dismiss="modal" aria-hidden="true">Close</a>
    <button type="button" id="btnCant" class="btn btn-blue">Submit</button>
  </div>
</div>
<p id="cantFind" class="addEventCantFind">Can't find a <?php echo $cantFindWhat; ?>?</p>