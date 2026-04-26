@extends('admin.layouts.app')

@section('subheader__title', 'Folder Images')

@section('content')			
	
	<div class="row">
		 
		<div class="col-xl-12">	
		 
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Folder Images
							</h3>
						</div>
					</div>
				</div>
				<div class="m-portlet__body">

					<!--begin::Section-->
					<div class="m-section">
						<div class="m-section__content">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>Image</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@php $i = 0; @endphp
									@forelse($folders as $row_folder)
									@php $i++; @endphp
									<tr @if(isset($folder_detail)) @if($folder_detail->id==$row_folder->id) style="background: aliceblue;" @endif @endif>
										<td scope="row">{{$i}}</td>
										<td>
											@if(!empty($row_folder->sessionimage->session_image) && File::exists('application/public/uploads/albums/'.$row_folder->session_image_id.'/sessions/'.$row_folder->sessionimage->session_image))
												
												<img src="{{asset('application/public/uploads/albums/'.$row_folder->session_image_id.'/sessions/'.$row_folder->sessionimage->session_image)}}"  alt="{{$row_folder->sessionimage->title}}"  width="100px" class="rounded" />
												
											
											@elseif(!empty($row_folder->sessionimage->session_image) && File::exists('application/public/uploads/albums/'.$row_folder->session_image_id.'/'.$row_folder->sessionimage->session_image))
												
												<img src="{{asset('application/public/uploads/albums/'.$row_folder->session_image_id.'/'.$row_folder->sessionimage->session_image)}}"  alt="{{$row_folder->sessionimage->title}}" width="100px" class="rounded" />
												
											@endif
											
										</td>
										<td>
										 
											<a href="{{route('admin.chat-conversation', ['group_no' => $group_no ])}}" class="btn btn-secondary">Go to chat</a>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="5">
											<div class="panel-body" style="text-align: center; padding: 80px;">									
												<h3>Image folder is empty!</h3>
												<span style="font-size: 12px;">Items not added in the folder.</span>
											</div>
										</td>	
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>

					<!--end::Section-->

				</div>

				<!--end::Form-->
			</div>

			<!--end::Portlet-->
		</div>
		
		 
	</div>	
								
@endsection

@push('js')
<script src="{{asset('content/js/chat.js')}}"></script>	
@endpush