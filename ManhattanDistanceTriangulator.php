<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
	var system = 1;
	$('#add').click(function(){
		system++;
		str = '<div class="row" id="system'+system+'"><div class="col-md-3 ml-md-auto"><div class="input-group"><span class="input-group-addon">X</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="X Coordinate" name="x['+system+']" required></div></div><div class="col-md-3"><div class="input-group"><span class="input-group-addon">Y</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Y Coordinate" name="y['+system+']" required></div></div><div class="col-md-3"><div class="input-group mr-md-auto"><span class="input-group-addon">Distance</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Target distance" name="z['+system+']" required></div></div><div class="col-md-2 mr-md-auto"><a href="#" class="btn btn-danger delete" id="delete'+system+'">Delete</a></div><br /><br /></div>';
		$('#systems').append(str);
	});	

	$(document.body).on("click", ".delete", function(){
		$(this).parent().parent().remove();
	});
	
	$(document.body).on('keydown keyup', '.validate', function(){
		$(this).val($(this).val().replace(/[^0-9-]/, ''));
	});
});
</script>
</head>
<body style="background-color:#212529;">
	<div class="container">		
		<div class="card text-center bg-dark text-white">
			<div class="card-body">
			<h4 class="card-title">Triangulator</h4>
			<form method="post">
				<div id="systems">
					<div class="row" id="system1">
						<div class="col-md-3 ml-md-auto">
							<div class="input-group">
							  <span class="input-group-addon">X</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="X Coordinate" name="x[1]" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
							  <span class="input-group-addon">Y</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Y Coordinate" name="y[1]" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
							  <span class="input-group-addon">Distance</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Target distance" name="z[1]" required>
							</div>
						</div>
						<div class="col-md-2 mr-md-auto">
							<a href="#" class="btn btn-success" id="add">Add System</a>
						</div>
						<br />
						<br />
					</div>
				</div>
				<div class="row">
					<div class="col ml-md-auto mr-md-auto">
						<input class="btn btn-primary" id="triganulate" type="submit" name="submit" value="Triangulate">
					</div>
				</div>
			</form>
			</div>
		</div>
		<br/>
		<?php
			function compare_values($v1, $v2)
			{
				if ($v1===$v2)
				{
					return 0;
				}
				if ($v1 > $v2)
				{
					return 1;
				}
				return -1;

			}
			if(isset($_POST['submit']))
			{
				//print "POST<pre>";
				//print_r($_POST);
				//print "</pre>";
				$pingers = array();
				foreach($_POST['x'] as $pinger => $coordinate)
				{
					$pingers[$pinger]['x'] = $_POST['x'][$pinger];
					$pingers[$pinger]['y'] = $_POST['y'][$pinger];
					$pingers[$pinger]['z'] = $_POST['z'][$pinger];
				}
				//print "pingers<pre>";
				//print_r($pingers);
				//print "</pre>";
				$sector_groups = array();
				foreach($pingers as $pinger => $value)
				{
					//print "Pinger".$pinger."<br>";
					$set = 0;
					for($i = 0; $i < $value['z']; $i++)
					{
						$sector_groups[$pinger][$set]['x'] = $value['x'] + $value['z'] - $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] + $i;
						$set++;
						
						$sector_groups[$pinger][$set]['x'] = $value['x'] - $value['z'] + $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] - $i;
						$set++;

						$sector_groups[$pinger][$set]['x'] = $value['x'] - $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] + $value['z'] - $i;
						$set++;
						
						$sector_groups[$pinger][$set]['x'] = $value['x'] + $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] - $value['z'] + $i;
						$set++;
					}
				}
				
				//print "sector_groups<pre>";
				//print_r($sector_groups);
				//print "</pre>";
				
				$possible_sectors = array_pop($sector_groups);
				
				if(count($sector_groups) > 0)
				{
					foreach($sector_groups as $sector)
					{
						$possible_sectors = array_uintersect($possible_sectors, $sector, "compare_values");
					}
				}

				//print "possible_sectors<pre>";
				//print_r($possible_sectors);
				//print "</pre>";
				
				?>
				<div class="col-md-6 mx-auto">
					<table class="table table-inverse table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th class="text-center bg-warning" colspan="3">Possible Systems</th>
							</tr>
							<tr>
								<th>#</th>
								<th>X</th>
								<th>Y</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach($possible_sectors as $sector)
							{
								echo '<tr>';
								echo '<th style="width: 5%">'.$i.'</th>';
								echo '<td style="width: 47.5%">'.$sector['x'].'</td>';
								echo '<td style="width: 47.5%">'.$sector['y'].'</td>';
								echo '</tr>';
								$i++;
							}
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		?>
	</div>
</body>
</html><html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
	var system = 1;
	$('#add').click(function(){
		system++;
		str = '<div class="row" id="system'+system+'"><div class="col-md-3 ml-md-auto"><div class="input-group"><span class="input-group-addon">X</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="X Coordinate" name="x['+system+']" required></div></div><div class="col-md-3"><div class="input-group"><span class="input-group-addon">Y</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Y Coordinate" name="y['+system+']" required></div></div><div class="col-md-3"><div class="input-group mr-md-auto"><span class="input-group-addon">Distance</span><input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Target distance" name="z['+system+']" required></div></div><div class="col-md-2 mr-md-auto"><a href="#" class="btn btn-danger delete" id="delete'+system+'">Delete</a></div><br /><br /></div>';
		$('#systems').append(str);
	});	

	$(document.body).on("click", ".delete", function(){
		$(this).parent().parent().remove();
	});
	
	$(document.body).on('keydown keyup', '.validate', function(){
		$(this).val($(this).val().replace(/[^0-9-]/, ''));
	});
});
</script>
</head>
<body style="background-color:#212529;">
	<div class="container">		
		<div class="card text-center bg-dark text-white">
			<div class="card-body">
			<h4 class="card-title">Triangulator</h4>
			<form method="post">
				<div id="systems">
					<div class="row" id="system1">
						<div class="col-md-3 ml-md-auto">
							<div class="input-group">
							  <span class="input-group-addon">X</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="X Coordinate" name="x[1]" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
							  <span class="input-group-addon">Y</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Y Coordinate" name="y[1]" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="input-group">
							  <span class="input-group-addon">Distance</span>
							  <input type="text" pattern="^[0-9-]+$" class="form-control validate" aria-label="Target distance" name="z[1]" required>
							</div>
						</div>
						<div class="col-md-2 mr-md-auto">
							<a href="#" class="btn btn-success" id="add">Add System</a>
						</div>
						<br />
						<br />
					</div>
				</div>
				<div class="row">
					<div class="col ml-md-auto mr-md-auto">
						<input class="btn btn-primary" id="triganulate" type="submit" name="submit" value="Triangulate">
					</div>
				</div>
			</form>
			</div>
		</div>
		<br/>
		<?php
			function compare_values($v1, $v2)
			{
				if ($v1===$v2)
				{
					return 0;
				}
				if ($v1 > $v2)
				{
					return 1;
				}
				return -1;

			}
			if(isset($_POST['submit']))
			{
				//print "POST<pre>";
				//print_r($_POST);
				//print "</pre>";
				$pingers = array();
				foreach($_POST['x'] as $pinger => $coordinate)
				{
					$pingers[$pinger]['x'] = $_POST['x'][$pinger];
					$pingers[$pinger]['y'] = $_POST['y'][$pinger];
					$pingers[$pinger]['z'] = $_POST['z'][$pinger];
				}
				//print "pingers<pre>";
				//print_r($pingers);
				//print "</pre>";
				$sector_groups = array();
				foreach($pingers as $pinger => $value)
				{
					//print "Pinger".$pinger."<br>";
					$set = 0;
					for($i = 0; $i < $value['z']; $i++)
					{
						$sector_groups[$pinger][$set]['x'] = $value['x'] + $value['z'] - $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] + $i;
						$set++;
						
						$sector_groups[$pinger][$set]['x'] = $value['x'] - $value['z'] + $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] - $i;
						$set++;

						$sector_groups[$pinger][$set]['x'] = $value['x'] - $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] + $value['z'] - $i;
						$set++;
						
						$sector_groups[$pinger][$set]['x'] = $value['x'] + $i;
						$sector_groups[$pinger][$set]['y'] = $value['y'] - $value['z'] + $i;
						$set++;
					}
				}
				
				//print "sector_groups<pre>";
				//print_r($sector_groups);
				//print "</pre>";
				
				$possible_sectors = array_pop($sector_groups);
				
				if(count($sector_groups) > 0)
				{
					foreach($sector_groups as $sector)
					{
						$possible_sectors = array_uintersect($possible_sectors, $sector, "compare_values");
					}
				}

				//print "possible_sectors<pre>";
				//print_r($possible_sectors);
				//print "</pre>";
				
				?>
				<div class="col-md-6 mx-auto">
					<table class="table table-inverse table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th class="text-center bg-warning" colspan="3">Possible Systems</th>
							</tr>
							<tr>
								<th>#</th>
								<th>X</th>
								<th>Y</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach($possible_sectors as $sector)
							{
								echo '<tr>';
								echo '<th style="width: 5%">'.$i.'</th>';
								echo '<td style="width: 47.5%">'.$sector['x'].'</td>';
								echo '<td style="width: 47.5%">'.$sector['y'].'</td>';
								echo '</tr>';
								$i++;
							}
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		?>
	</div>
</body>
</html>
