<?php
$default_date = mktime(0, 0, 0, date('n') - 1, 1, date('Y'));
$dm = date('n', $default_date);
$dy = date('Y', $default_date);
$m = array('','January','February','March','April','May','June','July','August','September','October','November','December');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Google Analytics for PureResponse</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-1.7.1.min.js"></script>
</head>

<body>
<h1>Google Analytics for PureResponse</h1>

<form id="form" method="post" action="do.php">
	<fieldset>
		<legend>Date</legend>
		<div>
			<label>Month
				<select name="month" id="month">
				<?php
				for ($i = 1; $i <= 12; $i++) {
					?>
					<option value="<?php echo $i ?>" <?php echo ($i == $dm ? 'selected="selected"' : '') ?>><?php echo $m[$i] ?></option>
					<?php
				}
				?>
				</select>
			</label>
		</div>
		<div>
			<label>Year
				<select name="year" id="year">
				<?php
				for ($i = 2010; $i <= $dy; $i++) {
					?>
					<option value="<?php echo $i ?>" <?php echo ($i == $dy ? 'selected="selected"' : '') ?>><?php echo $i ?></option>
					<?php
				}
				?>
				</select>
			</label>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Import statistics</legend>
		<div>
			<ul>
				<li><label><input type="checkbox" name="imp_stat" value="access_time">Access Time <span class="msg"></span></label></li>
				<li><label><input type="checkbox" name="imp_stat" value="browser_os">Browser / OS<span class="msg"></span></label></li>
				<li><label><input type="checkbox" name="imp_stat" value="countries">Countries<span class="msg"></span></label></li>
				<li><label><input type="checkbox" name="imp_stat" value="help_page">Help Pages<span class="msg"></span></label> (available only from February 2012)</li>
				<li><label><input type="checkbox" name="imp_stat" value="screen_res">Screen Resolution<span class="msg"></span></label></li>
			</ul>
		</div>
		<div>
			<input type="button" name="import" id="import" value="import">
		</div>
	</fieldset>
	<fieldset>
		<legend>Create Report</legend>
		<div>
			<ul>
				<li><label><input type="radio" name="report" value="access_time"> Access Time</label></li>
				<li><label><input type="radio" name="report" value="browser">Browser</label></li>
				<li><label><input type="radio" name="report" value="os">OS</label></li>
				<li><label><input type="radio" name="report" value="countries">Countries</label></li>
				<li><label><input type="radio" name="report" value="help_page">Help Pages</label> (available only from February 2012)</li>
				<li><label><input type="radio" name="report" value="screen_res">Screen Resolution</label></li>
			</ul>
		</div>
		<div>
			<input type="submit" name="create" id="create" value="create">
		</div>
	</fieldset>
</form>

<script type="text/javascript">
$(document).ready(function() {
	var $form = $('#form'),
		$import = $('#import'),
		$create = $('#create'),
		_page = 'do.php',
		_loadingC = 'loading',
		_errorC = 'error',
		_successC = 'success';
	
	//$form.submit(function(e) {
	//	e.preventDefault();
	//})
	
	// Import statistics
	$import.click(function() {
		$('input[name="imp_stat"]:checked').each(function() {
			var $this = $(this),
				$msg = $this.siblings('.msg');
			$msg.removeClass(_successC + ' ' + _errorC).addClass(_loadingC).html('Loading...');
			$.ajax({
				type: 'POST',
				url: _page,
				dataType: 'html',
				success: function(data) {
					if (data == '1') {
						$msg.removeClass(_loadingC).addClass(_successC).html('OK');
					}
					else {
						$msg.removeClass(_loadingC).addClass(_errorC).html(data);
					}
				},
				error: function() {
					$msg.removeClass(_loadingC).addClass(_errorC).html('An error occured trying to fetch the data');
				},
				data: 'import=' + $this.val() +
					  '&year=' + $('#year').val() +
					  '&month=' + $('#month').val()
			});
		});
	})
	
	//$create.click(function() {
	//	
	//})
});
</script>
</body>
</html>