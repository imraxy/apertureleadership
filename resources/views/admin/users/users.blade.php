@extends('admin.layouts.app')

@section('subheader__title', 'Manage Users')

@section('content')			
				
	<div class="m-portlet m-portlet--mobile">
	
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Users
					</h3>
				</div>
			</div>
			
			<div class="m-portlet__head-tools">
				<a href="{{route('admin.create_user')}}" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
				<span>
					<i class="la la-plus"></i>
					<span>Add New User</span>
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
						{{-- <th>Status</th> --}}
						<th>Actions</th>
					</tr>
				</thead>

			</table>
		</div>
		
	</div>
				
@endsection

@push('js')

	<script type="text/javascript">

        $.ajaxSetup({
            
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){
			
            $('#usersdatatable').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 10,
                'info': true,
                'lengthChange': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.users.datatables-users")}}'
                },

                'columns': [
                    { data: 'id', name: 'id', searchable: false},
                    { data: 'user_name', name: 'user_name'},
                    { data: 'email', name: 'email'},
                    //{ data: 'status', name: 'status', searchable: false, orderable: false },
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