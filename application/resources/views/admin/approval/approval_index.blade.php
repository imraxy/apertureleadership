@extends('admin.layouts.app')

@section('subheader__title', 'Manage Access Code')

@section('content')			
				
	<div class="m-portlet m-portlet--mobile">
	
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Access Code
					</h3>
				</div>
			</div>
			
			<div class="m-portlet__head-tools">
				<a href="{{route('admin.approval.select_user')}}" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
				<span>
					<i class="la la-plus"></i>
					<span>Assign Access Code</span>
				</span>
				</a>
			</div>
			
		</div>
		
		<div class="m-portlet__body">							
			
			<table id="usersdatatable" class="table table-striped- table-hover table-checkable">
				
				<thead>
					<tr>
						<th width="5%">ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Code</th>
						<th>Assign Time in Minute</th>
						<th>Start Time</th>
						<th>Expiry Time</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>

			</table>
		</div>
		
	</div>
 @endsection

@push('js')
	<script type="text/javascript">

       

        $(document).ready(function(){
			 $.ajaxSetup({
            
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $('#usersdatatable').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 10,
                'info': true,
                'lengthChange': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.approval.datatables-approval")}}'
                },

                'columns': [
                    { data: 'id', name: 'id', searchable: false},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'code', name: 'code',orderable: false },
					{ data: 'time', name: 'time',orderable: false },
					{ data: 'start_time', name: 'start_time',orderable: false },
					{ data: 'expiry_time', name: 'expiry_time',orderable: false },
					{ data: 'status', name: 'status'},
                    { data: 'action', name: 'action', searchable: false, orderable: false },
                ],
				
                language: {
                    emptyTable: "No data available",
                    lengthMenu: "Show _MENU_ entries.",
					searchPlaceholder: "Search by email,user name"	
                },
            });
        });
		
    </script>
@endpush