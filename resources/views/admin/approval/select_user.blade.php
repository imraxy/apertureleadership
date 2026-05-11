@extends('admin.layouts.app')

@section('subheader__title', 'Assign Access Code')
	
@section('content')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
	<script type="text/javascript" src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Select User for Access
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
			 
				<span class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air bulk">
					 <span>Bluk Assign Access Code</span>
				</span>
				 
			</div>
		</div>
 
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					 <table id="usersdatatable" class="table table-striped- table-hover table-checkable">
					
					<thead>
						<tr>
							<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
							<th width="5%">ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>status</th>
							<th>Actions</th>
						</tr>
					</thead>
					  <tbody>
							@foreach($users as $user)
								<tr>
									<th>{{$user->id}}</th>
									<th>{{$user->id}}</th>
									<td>{{$user->name}}</td>
									<td>{{$user->email}}</td>
									@if($user->approval_code=="")
										 <td style="color:blue">Not assign any code</td>
									@elseif(strtotime($user->approval_code_end_time) > strtotime(date('d-m-Y H:i:s')))
										  <td style="color:green">Access code active</td>
									@else
										  <td  style="color:red">Access code expire</td>
									@endif
									<td><a href="{{route('admin.approval.assign_code').'?user='.$user->id}}" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
									<span>
										 
										<span>Edit Access Code</span>
									</span>
									</a>
									</td>
								</tr>
							@endforeach
					  </tbody>
				</table>
					 
				</div>
			</div>
		 

		<!--end::Form-->
	</div>
	<script>

			var table = $('#usersdatatable').DataTable({
             "lengthMenu": [10, 25, 50, 75, 100],
            dom: 'lBfrtip',
            "ordering": false,
            buttons: ['csvHtml5'],
           
             'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(
                        data).html() + '">';
                }
                } 
            ] 
        });
		$('#example-select-all').on('click', function () {
            // Get all rows with search applied
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#example tbody').on('change', 'input[type="checkbox"]', function () {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#example-select-all').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked && ('indeterminate' in el)) {
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
		});
		$(".bulk").click(function(){
					var ids = [];
					table.$('input[type="checkbox"]').each(function () {
                        if ($(this).prop("checked") == true) {
                            ids.push($(this).val());
                        }
                    });
                    if (ids.length > 0) {
						 var users= ids.toString();
						  location.replace("{{url('admin/approval/assign_code_multipel')}}?user="+ids+"");
						 
					}else{
						alert("Please select at least one user!!");
					}
		});		
</script>
@endsection