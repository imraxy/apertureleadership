	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	function toastrMessages(message, type) {

		toastr.options = {
			  "closeButton": false,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-top-right",
			  "preventDuplicates": false,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "2000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};

			if(type==1) {
				toastr.success(message);	
			}else if(type==2) {
				toastr.info(message);
			}else if(type==3) {
				toastr.warning(message);
			}else if(type==4) {
				toastr.error(message);
			}
			
	}


	//Delete album gallery image
	function deletealbumgalleryImage(image) {
		
		var _root = $('#root').attr('data-root');

		//onclick="return confirm('are you sure you want to delete?')";
		
		console.log(image);

		$.ajax({
			type : 'POST',
	        dataType: 'json',
	        cache: false,
	        url: _root + '/admin/ajax_delete_gallery_album',
	        data: { image: image},
			success: function(resp) {
				
				if(resp.error) {
					
                    toastrMessages(resp.error, 4);

                    return false;
                }

                toastrMessages(resp.message, 1);
            }
		});
    };