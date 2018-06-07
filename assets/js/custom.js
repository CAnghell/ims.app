/* index background */
$('.carousel').carousel();

/* Look for selectpicker */
$(document).ready(function(){
	$('.selectpicker').selectpicker();
});

/* Home icon */
$('.dash-downIcon').click( function(){
    $(this).find('i').toggleClass('fa-arrow-circle-down').toggleClass('fa-arrow-circle-up');
});

/* Home View more or less */
$('.dash-downIcon').click(function(){
	var $this = $(this).find('p');
   $this.toggleClass('toggle-more');
    if ($this.hasClass('toggle-more')){
        $this.text('View More');         
    } else {
		$this.text('View Less');
    }
});

/* Onload hide Account type*/
function onSelectAcctType(){
	var selectedAcctType = document.getElementById('selectedAcctType').value;
	if(selectedAcctType == 'Admin' || selectedAcctType == 'Encoder'){
		document.getElementById('acct_branch').style.display = "none";
	}else{
		document.getElementById('acct_branch').style.display = "block";
	}
}

/* Account Type */
function selectAcctType(select){
	if(select.value == 'Admin'){
		document.getElementById('acct_branch').style.display = "none";
	}else if(select.value == 'Encoder'){
		document.getElementById('acct_branch').style.display = "none";
	}else{
		document.getElementById('acct_branch').style.display = "block";
	}
}

/* Select of Transfer */
function otherTrans(select){
	if(select.value == 'Other - '){
		document.getElementById('hidden_trans').style.display = "block";
	}else{
		document.getElementById('hidden_trans').style.display = "none";
	}
}

/* Navigator of Branch Adding CSS */
function navFrame(elem) {
		var a = document.getElementsByTagName('a')
		for (i = 0; i < a.length; i++) {
			a[i].classList.remove('frame-active')
		}
		elem.classList.add('frame-active');
	}

/* Div hide/show base on the clicked infor in the navigator */
 var stock = ["stock1", "stock2", "stock3"];
    var visibleStockId = null;
    function divVisibility(stockID) {
      if(visibleStockId === stockID) {
        visibleStockId = null;
      } else {
        visibleStockId = stockID;
      }
      hideNonVisibleStock();
    }
    function hideNonVisibleStock() {
      var i, stockID, div;
      for(i = 0; i < stock.length; i++) {
        stockID = stock[i];
        div = document.getElementById(stockID);
        if(visibleStockId === stockID) {
          div.style.display = "block";
        } else {
          div.style.display = "none";
        }
      }
    }

/* Navigator of Inventory Adding CSS */
function navFrameSelected() { 
    var typeLinks = document.getElementById("invent_type").getElementsByTagName("a"),
        i=0, len=typeLinks.length,
        full_path = location.href.split('#')[0]; //Ignore hashes?

    // Loop through each link.
    for(; i<len; i++) {
        if(typeLinks[i].href.split("#")[0] == full_path) {
            typeLinks[i].className += " frame-active";
        }
    }
}

/* Inventory Sell Modal radio-buttons */
$('#radioBtn a').on('click', function(){
    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#'+tog).prop('value', sel);
    
    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
	
	if(sel=="Sales_Invoice"){
		$(".box").not(".Sales_Invoice").hide();
		$(".Sales_Invoice").show();
		$(".Canvas_Sheet").hide();
		$(".Transfer").hide();
	}
	if(sel=="Canvas_Sheet"){
		$(".box").not(".Canvas_Sheet").hide();
		$(".Canvas_Sheet").show();
		$(".Sales_Invoice").hide();
		$(".Transfer").hide();
	}
	if(sel=="Transfer"){
		$(".box").not(".Transfer").hide();
		$(".Transfer").show();
		$(".Sales_Invoice").hide();
		$(".Canvas_Sheet").hide();
	}
})

/* Selling Calculation for sales invoice */
$('.sellQuantity').on('keyup',function(){
     $('.sellTotal').val( $('.sellQuantity').val() * $('.sellPrice').val());
});

$('.sellPrice').on('keyup',function(){
	 $('.sellTotal').val( $('.sellQuantity').val() * $('.sellPrice').val());
});

/* ----------- PRICE DECIMAL ---------------*/
$('#netPrice').on('change',function(){
	 var netPrice = document.getElementById('netPrice').value;
	 document.getElementById('netPrice').value = parseFloat(netPrice).toFixed(2);
});

$('#prodPrice').on('change',function(){
	 var prodPrice = document.getElementById('prodPrice').value;
	 document.getElementById('prodPrice').value = parseFloat(prodPrice).toFixed(2);
});

$('#refundPrice').on('change',function(){
	 var refundPrice = document.getElementById('refundPrice').value;
	 document.getElementById('refundPrice').value = parseFloat(refundPrice).toFixed(2);
});

$('#updatePrice').on('change',function(){
	 var updatePrice = document.getElementById('updatePrice').value;
	 document.getElementById('updatePrice').value = parseFloat(updatePrice).toFixed(2);
});

$('#restockNetPrice').on('change',function(){
	 var restockNetPrice = document.getElementById('restockNetPrice').value;
	 document.getElementById('restockNetPrice').value = parseFloat(restockNetPrice).toFixed(2);
});

$('#sellSIPrice').on('change',function(){
	 var sellSIPrice = document.getElementById('sellSIPrice').value;
	 document.getElementById('sellSIPrice').value = parseFloat(sellSIPrice).toFixed(2);
});

$('#sellCSPrice').on('change',function(){
	 var sellCSPrice = document.getElementById('sellCSPrice').value;
	 document.getElementById('sellCSPrice').value = parseFloat(sellCSPrice).toFixed(2);
});


/* ------------- SEARCHING -------------- */
  $(document).ready(function(){  
	/* Home Page */ 
	  $('#tomaySearch').keyup(function(){  
			tomaySearch_table($(this).val());  
	   });  
	   function tomaySearch_table(value){  
			$('#tomay_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  	
	   
	   $('#naguilianSearch').keyup(function(){  
			naguilianSearch_table($(this).val());  
	   });  
	   function naguilianSearch_table(value){  
			$('#naguilian_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  	
	   
	   $('#homeDailySearch').keyup(function(){  
			homeDailySearch_table($(this).val());  
	   });  
	   function homeDailySearch_table(value){  
			$('#homeDaily_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
	/* Branch Page */ 
	   $('#prodOutSearch').keyup(function(){  
			prodOutSearch_table($(this).val());  
	   });  
	   function prodOutSearch_table(value){  
			$('#prodOut_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  	
	
	   $('#prodInSearch').keyup(function(){  
			prodInSearch_table($(this).val());  
	   });  
	   function prodInSearch_table(value){  
			$('#prodIn_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
	   
	  $('#refundSearch').keyup(function(){  
			refundSearch_table($(this).val());  
	   });  
	   function refundSearch_table(value){  
			$('#refund_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
	   

	/* Inventory Page */ 
	   $('#inventProductSearch').keyup(function(){  
			inventProdSearch_table($(this).val());  
	   });  
	   function inventProdSearch_table(value){  
			$('#invent_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
 
	   $('#lowProductSearch').keyup(function(){  
			lowProdSearch_table($(this).val());  
	   });  
	   function lowProdSearch_table(value){  
			$('#lowProduct_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
	   
	   
	/* Account Page */ 
	   $('#accountSearch').keyup(function(){  
			accountSearch_table($(this).val());  
	   });  
	   function accountSearch_table(value){  
			$('#account_table tbody tr').each(function(){  
				 var found = 'false';  
				 $(this).each(function(){  
					  if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {  
						   found = 'true';  
					  }  
				 });  
				 if(found == 'true') {  
					  $(this).show();  
				 } else {  
					  $(this).hide();  
				 }  
			});  
	   }  
 });  
 