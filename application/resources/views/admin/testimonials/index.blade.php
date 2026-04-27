@extends('admin.layouts.app')

@section('subheader__title', 'Testimonials')

@section('content')			
				
	<div class="m-portlet m-portlet--mobile">
	
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Testimonials
					</h3>
				</div>
			</div>
			
			<div class="m-portlet__head-tools">
				<a href="{{route('admin.create_testimonial')}}" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
				<span>
					<i class="la la-plus"></i>
					<span>Add New Testimonial</span>
				</span>
				</a>
			</div>
			
		</div>
		
		<div class="m-portlet__body">							
			
			<table id="testimonialsdatatable" class="table table-striped- table-hover table-checkable">
				
				<thead>
					<tr>
						<th>Name</th>
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
			
            var table = $('#testimonialsdatatable').DataTable({
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'pageLength': 10,
                'info': true,
                'lengthChange': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'{{route("admin.testimonials_datatables")}}'
                },

                'columns': [
                    { data: 'name', name: 'name'},
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