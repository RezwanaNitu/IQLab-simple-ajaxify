<?php require_once 'core/init.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>jQuery Insert & append in table</title>
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	</head>
	<body>
		
		<div class="container" style="margin-top: 3em;">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					
					<!-- Form Container -->
					<div class="well">
						
						<legend>Add new User</legend>

						<form action="insert.php" method="POST" id="form_add_user" class="form-inline" role="form" autocomplete="off">
						
							<div class="form-group">
								<input type="text" name="name" id="name" class="form-control input-sm" placeholder="Full name">
							</div>

							<div class="form-group">
								<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email">
							</div>
						
							<button type="submit" id="btn_add_user" class="btn btn-sm btn-default">Add User</button>
						</form>
					</div>
					
					<!-- Table Container -->
					
					<legend>Data Table</legend>
					
					<?php

						$SQL_query 	=	"SELECT * FROM customers";
						$customers	=	$db->query($SQL_query);

					?>

					<?php if($customers->num_rows): ?>
						
						<table class="table table-bordered table-hover" id="data_table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($customer = $customers->fetch_object()): ?>
									<tr>
										<td><?php echo $customer->name; ?></td>
										<td><?php echo $customer->email; ?></td>
										<td>
											<a href="#" class="btn btn-sm btn-default" data-toggle="modal-remote" data-id="<?php echo $customer->id; ?>">Add more data</a>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>

					<?php else: ?>

						<div class="alert alert-warning">You have no data in customers table.</div>
				
					<?php endif; ?>

				</div>
			</div>
		</div>

		<!-- Ajax Modal -->
		<div class="modal fade" id="modal-ajax"></div>
		<!-- End Ajax Modal -->

		<!-- Import jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript">
			$(function(){

				var form = $('#form_add_user');

				form.submit(function(event) {
					
					event.preventDefault();
					
					var form_validation = true;

					var name_val = $('#name').val();
					var email_val = $('#email').val();

					if(form_validation == true){

						var form_action	= form.attr('action');
						var form_method	= form.attr('method');

						$.ajax({
							url: form_action,
							type: form_method,
							data: { name: name_val, email: email_val },
							dataType: 'json',
							success: function(response){
								if(response.status == 'success'){

									form[0].reset();

									var table_row_data = response.results;
									var template_table = '<tr>'+
															'<td>'+ table_row_data.name +'</td>'+
															'<td>'+ table_row_data.email +'</td>'+
															'<td><a href="#" class="btn btn-sm btn-default" data-toggle="modal-remote" data-id="'+ table_row_data.id +'">Add more data</a></td>'+
														'</tr>';

									$('#data_table').find('tbody').append(template_table);
								
								}else{
									alert(response.error);
								}
							},
							error: function(xhr, status, error) {
								alert(xhr.responseText);
							}
						});

					}else{

						alert('Hey Stupid your form validation is false :P');

					}
					
				});

				// When click on table button
				$('#data_table').delegate('[data-toggle="modal-remote"]', 'click', function(event) {
					
					event.preventDefault();
					event.stopPropagation();

					var customer_id = $(this).data('id');

					var template_modal = '<div class="modal-dialog">'+
										'<div class="modal-content">'+
											'<div class="modal-header">'+
												'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
												'<h4 class="modal-title">Form Name</h4>'+
											'</div>'+
											'<form action="insert_another.php" method="post" id="another_form">'+
												'<div class="modal-body">'+
													
													'<div class="form-group">'+
														'<label for="customer_id" class="control-label">Customer Id</label>'+
														'<input type="text" name="customer_id" id="customer_id" value="'+ customer_id +'" class="form-control">'+
													'</div>'+
													'<div class="form-group">'+
														'<label for="another_field" class="control-label">Another Field</label>'+
														'<input type="text" name="another_field" id="another_field" placeholder="Another field" class="form-control">'+
													'</div>'+
													
												'</div>'+
												'<div class="modal-footer">'+
													'<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>'+
													'<button type="submit" class="btn btn-sm btn-primary">Save changes</button>'+
												'</div>'+
											'</form>'+
										'</div>'+
									'</div>';


					$('#modal-ajax').html(template_modal);
					$('#modal-ajax').modal('show');

				});


				// Another form processor
				$('#modal-ajax').delegate('#another_form', 'submit', function(event) {
					event.preventDefault();
					
					var another_form = $(this);
					var another_form_action = another_form.attr('action');
					var another_form_method = another_form.attr('method');

					$.ajax({
						url: another_form_action,
						type: another_form_method,
						data: another_form.serialize(),
						dataType: 'json',
						success: function(another_response){
							if(another_response.status == 'success'){

								form[0].reset();

								var template_form_saved = '<div class="modal-dialog">'+
																'<div class="modal-content">'+
																	'<div class="modal-header">'+
																		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
																	'</div>'+
																	
																	'<div class="modal-body">'+
																		
																		'<h4>Your data has been saved successfully</h4>'+
																		
																	'</div>'+
																	'<div class="modal-footer">'+
																		'<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>'+
																	'</div>'+
																'</div>'+
															'</div>';

								$('#modal-ajax').html(template_form_saved);
								$('#modal-ajax').modal('show');

								// Set a timeout to hide the modal again
								setTimeout(function(){
									$('#modal-ajax').modal('hide');
									$('#modal-ajax').empty();
								}, 2000);
							
							}else{
								alert(response.error);
							}
						},
						error: function(xhr, status, error) {
							alert(xhr.responseText);
						}
					});

				});

			});
		</script>
	</body>
</html>

