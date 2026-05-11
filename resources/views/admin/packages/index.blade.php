@extends('admin.layouts.app')

@section('subheader__title', 'Packages')

@section('content')			
				
	<div class="m-portlet m-portlet--mobile">
	
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Packages
					</h3>
				</div>
			</div>
			
			<div class="m-portlet__head-tools">
				<a href="{{route('admin.create_packages')}}" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
				<span>
					<i class="la la-plus"></i>
					<span>Add Package</span>
				</span>
				</a>
			</div>
			
		</div>
		
		<div class="m-portlet__body">							
			
			<table id="packagesdatatable" class="table table-striped- table-hover table-checkable">
				
				<thead>
					<tr>
						<th>Title</th>
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
			
            $('#packagesdatatable').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 10,
                'info': true,
                'lengthChange': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.packages.datatables-packages")}}'
                },

                'columns': [
                    { data: 'title', name: 'title'},
                    { data: 'action', name: 'action', searchable: false, orderable: false},
                ],
				
                language: {
                    emptyTable: "No data available",
                    lengthMenu: "Show _MENU_ entries.",	
                },
            });
        });
		
    </script>
	
@endpush