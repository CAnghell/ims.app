// FORMS AJAX VALIDATIONS
$('document').ready(function(){ 		 
		 // valid letters only
		 var nameregex = /^[a-zA-Z ]+$/;
		 
		 $.validator.addMethod("validname", function( value, element ) {
		     return this.optional( element ) || nameregex.test( value );
		 }); 
		 
		 // valid numbers only
		 var numberregex = /^[0-9]+$/;
		 $.validator.addMethod("validnumber", function( value, element ) {
		     return this.optional( element ) || numberregex.test( value );
		 }); 
		 
		 // valid price 
		 var priceregex = /^[0-9]*\.?[0-9]*$/;
		$.validator.addMethod("validprice", function( value, element ) {
		     return this.optional( element ) || priceregex.test( value );
		 }); 
		 // valid email pattern
		 var eregex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		 
		 $.validator.addMethod("validemail", function( value, element ) {
		     return this.optional( element ) || eregex.test( value );
		 });
		 
		 // --------- NEW PRODUCT --------
		 $("#newProduct-form").validate({
					
		  rules:{
				prodDate:{
					required: true
				},
				prodSupplier:{
					required: true
				},
				prodName: {
					required: true
				},
				prodInfo: {
					required: true
				},
				prodSize: {
					required: true
				},
				prodQuantity: {
					required: true,
					validnumber: true
				},
				prodAlerQuantity: {
					required: true,
					validnumber: true
				},
				netPrice: {
					required: true,
					validprice: true
				},
				prodPrice: {
					required: true,
					validprice: true
				},
				prodType : {
					required : true
				},
				prodBranch : {
					required : true
				},
		   },
		   messages:{
				prodDate:{
					required: "Please enter a date."
				},
				prodSupplier:{
					required: "Please enter a supplier."
				},
				prodName: {
					required: "Please enter a product."
				},
				prodInfo: {
					required: "Please enter a information."
				},
				prodSize: {
					required: "Please enter a size."
				},
				prodQuantity: {
					required: "Please enter a quantity.",
					validnumber: "Quantity should be numbers only."
				},
				prodAlerQuantity: {
					required: "Please enter a altering quantity.",
					validnumber: "Alterting Quantity should be numbers only."
				},
				netPrice: {
					required: "Please enter a net price.",
					validprice: "Net price should be numbers only."
				},
				prodPrice: {
					required: "Please enter a price.",
					validprice: "Price should be numbers only."
				},
				prodType : {
					required : "Please select a product type."
				},
				prodBranch : {
					required : "Please select a branch."
				},
		   },
		   errorPlacement : function(error, element) {
			  $(element).closest('.form-group').find('.help-block').html(error.html());
		   },
		   highlight : function(element) {
			  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		   },
		   unhighlight: function(element, errorClass, validClass) {
			  $(element).closest('.form-group').removeClass('has-error');
			  $(element).closest('.form-group').find('.help-block').html('');
		   },
				submitHandler: submitNewProduct
		   }); 
		   
		   
		   function submitNewProduct(){
			   
			   $.ajax({
			   		url: 'ajax-newProduct.php',
			   		type: 'POST',
			   		data: $('#newProduct-form').serialize(),
			   		dataType: 'json'
			   })
			   .done(function(data){
			   		
			   		$('#newProd_btn').prop('disabled', true);
			   		$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
			   		
			   		setTimeout(function(){
								   
						if ( data.status==='success' ) {
							
							$('.errorDiv').slideDown('fast', function(){
								$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
								$("#newProduct-form").trigger('reset');
								$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
								$('#newProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
							
									   
					    } else {
									   
						    $('.errorDiv').slideDown('fast', function(){
						      	$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
							  	$("#newProduct-form").trigger('reset');
							  	$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
							  	$('#newProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
						}
								  
					},3000);
			   		
			   })
			   .fail(function(){
			   		$("#newProduct-form").trigger('reset');
			   		alert('An unknown error occoured, Please try again Later...');
			   });
		   }
		   
		 // --------- REFUND --------
		 $("#refund-form").validate({
					
		  rules:{
				refundDate:{
					required: true
				},
				refundBranch:{
					required: true
				},
				refundReceipt: {
					required: true
				},
				refundName: {
					required: true,
					validname: true
				},
				refundProduct: {
					required: true
				},
				refundQuantity: {
					required: true,
					validnumber: true
				},
				refundPrice : {
					required : true,
					validprice: true
				},
				refundReason : {
					required : true
				},
		   },
		   messages:{
				refundDate:{
					required: "Please enter a date."
				},
				refundBranch:{
					required: "Please select a branch."
				},
				refundReceipt: {
					required: "Please enter a receipt no."
				},
				refundName: {
					required: "Please enter a name.",
					validname: "Name should be letters only."
				},
				refundProduct: {
					required: "Please select a product."
				},
				refundQuantity: {
					required: "Please enter a quantity.",
					validnumber: "Quantity should be numbers only."
				},
				refundPrice : {
					required : "Please enter a price.",
					validprice: "Price should be numbers only."
				},
				refundReason : {
					required : "Please eneter a reason for the refund."
				},
		   },
		   errorPlacement : function(error, element) {
			  $(element).closest('.form-group').find('.help-block').html(error.html());
		   },
		   highlight : function(element) {
			  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		   },
		   unhighlight: function(element, errorClass, validClass) {
			  $(element).closest('.form-group').removeClass('has-error');
			  $(element).closest('.form-group').find('.help-block').html('');
		   },
				submitHandler: submitRefundProduct
		   }); 
		   
		   
		   function submitRefundProduct(){
			   
			   $.ajax({
			   		url: 'ajax-refundProduct.php',
			   		type: 'POST',
			   		data: $('#refund-form').serialize(),
			   		dataType: 'json'
			   })
			   .done(function(data){
			   		
			   		$('#refundProd_btn').prop('disabled', true);
			   		$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
			   		
			   		setTimeout(function(){
								   
						if ( data.status==='success' ) {
							
							$('.errorDiv').slideDown('fast', function(){
								$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
								$("#refund-form").trigger('reset');
								$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
								$('#refundProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
							
									   
					    } else {
									   
						    $('.errorDiv').slideDown('fast', function(){
						      	$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
							  	$("#refund-form").trigger('reset');
							  	$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
							  	$('#refundProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
						}
								  
					},3000);
			   		
			   })
			   .fail(function(){
			   		$("#refund-form").trigger('reset');
			   		alert('An unknown error occoured, Please try again Later...');
			   });
		   }
		   
		   
		 // --------- SELLING --------
			// Sale Invoice
			  $("#sellSIProduct-form").validate({
						
			  rules:{
					sellSITrans:{
						required: true
					},
					sellSIDate:{
						required: true
					},
					sellSIBranch:{
						required: true
					},
					sellSIReceipt:{
						required: true
					},
					sellSIName:{
						required: true
					},
					sellSIStatus:{
						required: true
					},
					sellSIProd:{
						required: true
					},
					sellSIPrice:{
						required: true,
						validprice: true
					},
					sellSIQuantity:{
						required: true,
						validnumber: true
					},
					sellSITotal:{
						required: true,
						validprice: true
					},
			   },
			   messages:{
					sellSITrans:{
						required: "Please select a transaction."
					},
					sellSIDate:{
						required: "Please enter a date."
					},
					sellSIBranch:{
						required: "Please select a branch."
					},
					sellSIReceipt:{
						required: "Please enter a receipt no."
					},
					sellSIName:{
						required: "Please select a product."
					},
					sellSIStatus:{
						required: "Please select a status."
					},
					sellSIProd:{
						required: "Please select a product."
					},
					sellSIPrice:{
						required: "Zero price is not valid.",
						validprice: "Price should be numbers only."
					},
					sellSIQuantity:{
						required: "Please enter a quantity.",
						validnumber: "Quantity should be numbers only."
					},
					sellSITotal:{
						required: "Zero total is not valid.",
						validprice: "Total should be numbers only."
					},
			   },
			   errorPlacement : function(error, element) {
				  $(element).closest('.form-group').find('.help-block').html(error.html());
			   },
			   highlight : function(element) {
				  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			   },
			   unhighlight: function(element, errorClass, validClass) {
				  $(element).closest('.form-group').removeClass('has-error');
				  $(element).closest('.form-group').find('.help-block').html('');
			   },
					submitHandler: submitSellSIProduct
			   }); 
			   
			   
			   function submitSellSIProduct(){
				   
				   $.ajax({
						url: 'ajax-sellSIProduct.php',
						type: 'POST',
						data: $('#sellSIProduct-form').serialize(),
						dataType: 'json'
				   })
				   .done(function(data){
						
						$('#sellSIProd_btn').prop('disabled', true);
						$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
						
						setTimeout(function(){
									   
							if ( data.status==='success' ) {
								
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
									$("#sellSIProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellSIProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
								
										   
							} else {
										   
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
									$("#sellSIProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellSIProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
							}
									  
						},3000);
						
				   })
				   .fail(function(){
						$("#sellSIProduct-form").trigger('reset');
						alert('An unknown error occoured, Please try again Later...');
				   });
			   }
			   
			// Canvas Sheet
			  $("#sellCSProduct-form").validate({
				
			  rules:{
					sellCSTrans:{
						required: true
					},
					sellCSDate:{
						required: true
					},
					sellCSBranch:{
						required: true
					},
					sellCSReceipt:{
						required: true
					},
					sellCSName:{
						required: true
					},
					sellCSStatus:{
						required: true
					},
					sellCSProd:{
						required: true
					},
					sellCSPrice:{
						required: true,
						validprice: true
					},
					sellCSQuantity:{
						required: true,
						validnumber: true
					},
					sellCSTotal:{
						required: true,
						validprice: true
					},
			   },
			   messages:{
					sellCSTrans:{
						required: "Please select a transaction."
					},
					sellCSDate:{
						required: "Please enter a date."
					},
					sellCSBranch:{
						required: "Please select a branch."
					},
					sellCSReceipt:{
						required: "Please enter a receipt no."
					},
					sellCSName:{
						required: "Please select a product."
					},
					sellCSStatus:{
						required: "Please select a status."
					},
					sellCSProd:{
						required: "Please select a product."
					},
					sellCSPrice:{
						required: "Zero price is not valid.",
						validprice: "Price should be numbers only."
					},
					sellCSQuantity:{
						required: "Please enter a quantity.",
						validnumber: "Quantity should be numbers only."
					},
					sellCSTotal:{
						required: "Zero total is not valid.",
						validprice: "Total should be numbers only."
					},
			   },
			   errorPlacement : function(error, element) {
				  $(element).closest('.form-group').find('.help-block').html(error.html());
			   },
			   highlight : function(element) {
				  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			   },
			   unhighlight: function(element, errorClass, validClass) {
				  $(element).closest('.form-group').removeClass('has-error');
				  $(element).closest('.form-group').find('.help-block').html('');
			   },
					submitHandler: submitSellCSProduct
			   }); 
			   
			   
			   function submitSellCSProduct(){
				   
				   $.ajax({
						url: 'ajax-sellCSProduct.php',
						type: 'POST',
						data: $('#sellCSProduct-form').serialize(),
						dataType: 'json'
				   })
				   .done(function(data){
						
						$('#sellCSProd_btn').prop('disabled', true);
						$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
						
						setTimeout(function(){
									   
							if ( data.status==='success' ) {
								
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
									$("#sellCSProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellCSProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
								
										   
							} else {
										   
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
									$("#sellCSProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellCSProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
							}
									  
						},3000);
						
				   })
				   .fail(function(){
						$("#sellCSProduct-form").trigger('reset');
						alert('An unknown error occoured, Please try again Later...');
				   });
			   }
		 
		 // Transfer
			  $("#sellTProduct-form").validate({
				
			  rules:{
					sellTTrans:{
						required: true
					},
					sellTDate:{
						required: true
					},
					sellTBranch:{
						required: true
					},
					sellTReceipt:{
						required: true
					},
					sellTNameTo:{
						required: true
					},
					sellTName:{
						required: false
					},
					sellTStatus:{
						required: true
					},
					sellTProd:{
						required: true
					},
					sellTQuantity:{
						required: true,
						validnumber: true
					},
			   },
			   messages:{
					sellTTrans:{
						required: "Please select a transaction."
					},
					sellTDate:{
						required: "Please enter a date."
					},
					sellTBranch:{
						required: "Please select a branch."
					},
					sellTReceipt:{
						required: "Please enter a receipt no."
					},
					sellTNameTo:{
						required: "Please select a transfer."
					},
					sellTName:{
					},
					sellTStatus:{
						required: "Please select a product."
					},
					sellTProd:{
						required: "Zero price is not valid."
					},
					sellTQuantity:{
						required: "Please enter a quantity.",
						validnumber: "Quantity should be numbers only."
					},
			   },
			   errorPlacement : function(error, element) {
				  $(element).closest('.form-group').find('.help-block').html(error.html());
			   },
			   highlight : function(element) {
				  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			   },
			   unhighlight: function(element, errorClass, validClass) {
				  $(element).closest('.form-group').removeClass('has-error');
				  $(element).closest('.form-group').find('.help-block').html('');
			   },
					submitHandler: submitSellTProduct
			   }); 
			   
			   
			   function submitSellTProduct(){
				   
				   $.ajax({
						url: 'ajax-sellTProduct.php',
						type: 'POST',
						data: $('#sellTProduct-form').serialize(),
						dataType: 'json'
				   })
				   .done(function(data){
						
						$('#sellTProd_btn').prop('disabled', true);
						$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
						
						setTimeout(function(){
									   
							if ( data.status==='success' ) {
								
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
									$("#sellTProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellTProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
								
										   
							} else {
										   
								$('.errorDiv').slideDown('fast', function(){
									$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
									$("#sellTProduct-form").trigger('reset');
									$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
									$('#sellTProd_btn').prop('disabled', false);
								}).delay(3000).slideUp('fast');
							}
									  
						},3000);
						
				   })
				   .fail(function(){
						$("#sellTProduct-form").trigger('reset');
						alert('An unknown error occoured, Please try again Later...');
				   });
			   }
			   
			   
		 // --------- UPDATE --------
		// transfering the information to modal
		$(document).on('click', '.product_update', function(){
			var product_id = $(this).attr("id");
			$.ajax({
				 url:"tableFetch.php",
				 method:"POST",
				 data:{product_id:product_id},
				 dataType:"json",
				 success:function(data){
					$('#updateType').val(data.productType);
					$('#updateProd').val(data.productName);
					$('#updateInfo').val(data.productInformation);
					$('#updateSize').val(data.productSize);
					$('#updateCurQuantity').val(data.productQuantity);
					$('#updateAlerQuantity').val(data.productAlerQuantity);
					$('#updatePrice').val(data.productPrice);
					$('#product_id').val(data.productId);
					$('#updateInventory').modal('show');
				 }
			});
		});
		
		$("#updateProduct-form").validate({
					
		  rules:{
				product_id:{
					required: true
				},
				updateType:{
					required: true
				},
				updateProd:{
					required: true
				},
				updateInfo: {
					required: true
				},
				updateSize: {
					required: true
				},
				updateCurQuantity: {
					required: true,
					validnumber: true
				},
				updateAlerQuantity: {
					required: true,
					validnumber: true
				},
				updatePrice: {
					required: true,
					validprice: true
					//cant be lower than the net price
				},
		   },
		   messages:{
			    product_id:{
					required: "Please enter a id."
				},
				updateType:{
					required: "Please select a type."
				},
				updateProd:{
					required: "Please enter a product."
				},
				updateInfo: {
					required: "Please enter a information."
				},
				updateSize: {
					required: "Please enter a size."
				},
				updateCurQuantity: {
					required: "Please enter a current quantity.",
					validnumber: "Current Quantity should be a numbers only."
				},
				updateAlerQuantity: {
					required: "Please enter a quantity.",
					validnumber: "Current Quantity should be a numbers only."
				},
				updatePrice: {
					required: "Please enter a price.",
					validprice: "Price should be a numbers only."
				},
		   },
		   errorPlacement : function(error, element) {
			  $(element).closest('.form-group').find('.help-block').html(error.html());
		   },
		   highlight : function(element) {
			  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		   },
		   unhighlight: function(element, errorClass, validClass) {
			  $(element).closest('.form-group').removeClass('has-error');
			  $(element).closest('.form-group').find('.help-block').html('');
		   },
				submitHandler: submitUpdateProduct
		   }); 
		   
		   
		   function submitUpdateProduct(){
			   
			   $.ajax({
			   		url: 'ajax-updateProduct.php',
			   		type: 'POST',
			   		data: $('#updateProduct-form').serialize(),
			   		dataType: 'json'
			   })
			   .done(function(data){
			   		
			   		$('#updateProd_btn').prop('disabled', true);
			   		$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
			   		
			   		setTimeout(function(){
								   
						if ( data.status==='success' ) {
							
							$('.errorDiv').slideDown('fast', function(){
								$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
								//$("#updateProduct-form").trigger('reset');
								$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
								$('#updateProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
							
									   
					    } else {
									   
						    $('.errorDiv').slideDown('fast', function(){
						      	$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
								//$("#updateProduct-form").trigger('reset');
							  	$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
							  	$('#updateProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
						}
								  
					},3000);
			   		
			   })
			   .fail(function(){
			   		//$("#updateProduct-form").trigger('reset');
			   		alert('An unknown error occoured, Please try again Later...');
			   });
		   }

		// --------- RESTOCK --------
		// transfering the information to modal
		$(document).on('click', '.product_restock', function(){
			var restock_id = $(this).attr("id");
			$.ajax({
				 url:"tableFetch.php",
				 method:"POST",
				 data:{restock_id:restock_id},
				 dataType:"json",
				 success:function(data){
					$('#restockType').val(data.productType);
					$('#restockProd').val(data.productName);
					$('#restockInfo').val(data.productInformation);
					$('#restockSize').val(data.productSize);
					$('#restock_id').val(data.productId);
					$('#restockInventory').modal('show');
				 }
			});
		});
		
		$("#restockProduct-form").validate({
					
		  rules:{
				restock_id:{
					required: true
				},
				restockDate:{
					required: true
				},
				restockSupplier:{
					required: true
				},
				restockType:{
					required: true
				},
				restockProd:{
					required: true
				},
				restockInfo:{
					required: true
				},
				restockSize:{
					required: true
				},
				restockAddQuantity: {
					required: true,
					validnumber: true
				},
				restockNetPrice: {
					required: true,
					validprice: true
				},
		   },
		   messages:{
			    restock_id:{
					required: "Please enter a id."
				},
				restockDate: {
					required: "Please enter the date."
				},
				restockSupplier: {
					required: "Please enter a supplier."
				},
				restockType:{
					required: "Please select a type."
				},
				restockProd:{
					required: "Please enter a supplier."
				},
				restockInfo:{
					required: "Please enter a information."
				},
				restockSize:{
					required: "Please enter a size."
				},
				restockAddQuantity: {
					required: "Please enter a current quantity.",
					validnumber: "Additional Quantity should be a numbers only."
				},
				restockNetPrice: {
					required: "Please enter a quantity.",
					validprice: "Net price should be a numbers only."
				},
		   },
		   errorPlacement : function(error, element) {
			  $(element).closest('.form-group').find('.help-block').html(error.html());
		   },
		   highlight : function(element) {
			  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		   },
		   unhighlight: function(element, errorClass, validClass) {
			  $(element).closest('.form-group').removeClass('has-error');
			  $(element).closest('.form-group').find('.help-block').html('');
		   },
				submitHandler: submitRestockProduct
		   }); 
		   
		   
		   function submitRestockProduct(){
			   
			   $.ajax({
			   		url: 'ajax-restockProduct.php',
			   		type: 'POST',
			   		data: $('#restockProduct-form').serialize(),
			   		dataType: 'json'
			   })
			   .done(function(data){
			   		
			   		$('#restockProd_btn').prop('disabled', true);
			   		$('input[type=text],input[type=email],input[type=password]').prop('disabled', true);
			   		
			   		setTimeout(function(){
								   
						if ( data.status==='success' ) {
							
							$('.errorDiv').slideDown('fast', function(){
								$('.errorDiv').html('<div class="alert alert-success">'+data.message+'</div>');
								//$("#restockProduct-form").trigger('reset');
								$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
								$('#restockProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
							
									   
					    } else {
									   
						    $('.errorDiv').slideDown('fast', function(){
						      	$('.errorDiv').html('<div class="alert alert-danger">'+data.message+'</div>');
							  	//$("#restockProduct-form").trigger('reset');
							  	$('input[type=text],input[type=email],input[type=password]').prop('disabled', false);
							  	$('#restockProd_btn').prop('disabled', false);
							}).delay(3000).slideUp('fast');
						}
								  
					},3000);
			   		
			   })
			   .fail(function(){
			   		//$("#restockProduct-form").trigger('reset');
			   		alert('An unknown error occoured, Please try again Later...');
			   });
		   }

});
