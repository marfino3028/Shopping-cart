<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>

	<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</head>
<body>

	<div class="container py-4">
		<table id="table_id">
			<tbody class="row">
            <?php	
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
			?>
				<tr class="col-lg-3 col-md-4 col-sm-12">
					<td>
					
				        <div class="card shadow">
			        		<img src="/{{ $row->path }}" alt="{{ $row->path }}" class="card-img-top">
				        	<div class="card-body">
				        		card {{ $i }}
				        	</div>
				        </div>

					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
	</div>
	
<script>
	$(document).ready( function () {
	    $('#table_id').DataTable();
	});
</script>
</body>
</html>