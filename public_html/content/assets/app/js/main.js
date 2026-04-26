	
	/**
	 * 
	 * Show image preview before upload
	 */
	loadFile = function(event, id) {
		var output = document.getElementById(id);
		output.src = URL.createObjectURL(event.target.files[0]);
	};



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

	$(document).on('click', '.deletealbumgalleryImage', function () {

		var $this = $(this);
    	var image = $this.data('val');
		var ablum_id = $this.data('id');
		var ablum_album = $this.data('album');
		
		var _root = $('#root').attr('data-root');
        
        $.ajax({
            type : 'POST',
            dataType: 'json',
            cache: false,
            url: _root + '/admin/albums/ajax_delete_gallery_album',
            data: {image: image, ablum_id: ablum_id},
            beforeSend:function(){
            	// Show image container          
			   //$(".progressBar-loading-new").show();
			   //$(".hSEy_rippleContainer_"+ablum_album).html('<button type="button" class="btn btn-danger button-component" style="padding: .3rem 1rem; max-height: 70px;"><svg xmlns="http://www.w3.org/2000/svg" class="loader-svg-holder" width="24px" height="24px"><circle cx="12px" cy="12px" r="9px" class="circle"></circle></svg></button>');
			   $(".hSEy_rippleContainer_"+ablum_album).html('<a href="javascript:;" class="rippleContainer btn btn-danger m-btn m-btn--icon m-btn--icon-only m-loader m-loader--center m-loader--center" style="margin-top: 6px;"></a>');
			},
            success : function(resp)
            {
                console.log(resp);

                if(resp.error) {
					
					toastrMessages(resp.error, 4);
					$(".hSEy_rippleContainer_"+ablum_album).html('<a href="javascript:;" data-album="'+ablum_album+'" data-id="'+image+'" data-val="'+ablum_id+'" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only deletealbumgalleryImage" style="margin-top: 6px;"><i class="la la-trash-o"></i></a>');
                    return false;
                }

                $(".ablum_"+ablum_album).html('');
                
                toastrMessages(resp.message, 1);
                
            },
            
		   	complete:function(data){
		    	// Hide image container
		    	$(".progressBar-loading-new").hide();
		   	}   
        });
	});


	$(document).ready(function() { 

		//Delete album gallery image
	    $(".deletealbumgalleryImages").click(function(){

	    	var $this = $(this);
	    	var image = $this.data('val');
			var ablum_id = $this.data('id');
			var ablum_album = $this.data('album');
			
			var _root = $('#root').attr('data-root');
	        
	        $.ajax({
	            type : 'POST',
	            dataType: 'json',
	            cache: false,
	            url: _root + '/admin/albums/ajax_delete_gallery_album',
	            data: {image: image, ablum_id: ablum_id},
	            beforeSend:function(){
	            	// Show image container          
				   //$(".progressBar-loading-new").show();
				   $(".hSEy_rippleContainer_"+ablum_album).html('<button type="button" class="btn btn-danger button-component" style="padding: .3rem 1rem; max-height: 70px;"><svg xmlns="http://www.w3.org/2000/svg" class="loader-svg-holder" width="24px" height="24px"><circle cx="12px" cy="12px" r="9px" class="circle"></circle></svg></button>');
				},
	            success : function(resp)
	            {
	                console.log(resp);

	                if(resp.error) {
						
						toastrMessages(resp.error, 4);
						$(".hSEy_rippleContainer_"+ablum_album).html('<a href="javascript:;" data-album="{{$i}}" data-id="{{$album->id}}" data-val="{{$image}}" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only deletealbumgalleryImage" style="margin-top: 6px;"><i class="la la-trash-o"></i></a>');
	                    return false;
	                }

	                //$(".ablum_"+ablum_album).html('');
	                
	                toastrMessages(resp.message, 1);
	                
	            },
	            
			   	complete:function(data){
			    	// Hide image container
			    	$(".progressBar-loading-new").hide();
			   	}   
	        });

	    });
	});

	//Delete album gallery image
	function deletealbumgalleryImage(image) {
		
		

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