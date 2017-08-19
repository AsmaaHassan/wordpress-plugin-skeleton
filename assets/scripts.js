jQuery(document).ready(function() {
    // say hello
    console.log( "wp-plugin-skeleton is installed, version 1.0.0" );
    
    
    // submitting the form dynamically
    jQuery(function () {
        // fetch ui components
        var sayHelloForm = jQuery('#sayHelloForm');
        var sayHelloFormSubmit = jQuery('#sayHelloFormSubmit');
        var alert = jQuery('.alert');
        var sayHello = jQuery('#sayHello');
        // form submit event
        sayHelloForm.on('submit', function(e) {
            e.preventDefault();
            jQuery.ajax({
                url: customAjaxScript.ajaxUrl,
                type: 'POST',
                //dataType: 'html',
                data: {
                    action: 'sayHello',
                    //query_vars: sayHello.query_vars,
                    startDate: jQuery('#startDate').val(),
                    endDate: jQuery('#endDate').val()
                    },
                beforeSend: function() {
                    alert.fadeOut();
                    alert.removeClass('alert-danger').removeClass('alert-success').addClass('alert-info');
                    alert.html('<i class="fa fa-spinner fa-spin"></i> Retrieving data').fadeIn();
                    sayHelloFormSubmit.html('<i class="fa fa-spinner fa-spin"></i> Submit');
                },
                success: function(data) {
                    if(data == "error")
                    {
                        sayHello.fadeOut();
                        alert.removeClass('alert-info').removeClass('alert-success').addClass('alert-danger');
                        alert.html("error!").fadeIn();
                    }
                    else {
                        alert.removeClass('alert-info').removeClass('alert-danger').addClass('alert-success');
                        alert.html("success!").fadeIn();
                        
                        sayHello.html(data).fadeIn();
                        //console.log(data);
                    }
                    sayHelloForm.trigger('reset'); // reset form
                    sayHelloFormSubmit.html('Submit');
                },
                fail: function() {
                    sayHello.fadeOut();
                    alert.removeClass('alert-info').removeClass('alert-success').addClass('alert-danger');
                    alert.html("Failed to retieve data!").fadeIn();
                },
                error: function(e) {
                    console.log(e);
                }
          });
        });
        
        
      });
});


function printDiv(divName) {
    /* print div content by it's name */
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
