/**
 * Initialization of jquery datepicker
 * 
 * @author Nikolay Georgiev
 * @version 1.0
 */
jQuery(document).ready(function(){
    
    // Searching for datepicker elements
    jQuery('.neutron-datepicker').each(function(key, value){
        var options = jQuery(this).data('options');
        var el = jQuery('#' + options.id);
        
        var lang = options.lang;
            jQuery.datepicker
            .setDefaults(jQuery.datepicker.regional[(lang == 'en') ? 'en-GB' : lang]);
    
        el.datepicker(options);
    });
    
    // Searching for daterangepicker elements
    jQuery('.neutron-daterangepicker').each(function(key, value){
    	var options = jQuery(this).data('options'); 
    	
    	var firstEl = jQuery('#' + options.id + '_' + options.first_name);
    	var secondEl = jQuery('#' + options.id + '_' + options.second_name);
    	
    	var onSelect = function(selectedDate) { 
            var date = new Date(selectedDate);
            
            if(this.id == options.id + '_' + options.first_name){
                secondEl.datepicker( "option", 'minDate', date );
            } else {
                firstEl.datepicker( "option", 'maxDate', date );
            }
        };
        
        firstEl.datepicker( "option", 'onSelect', onSelect);
        secondEl.datepicker( "option", 'onSelect', onSelect);      
    });
});


