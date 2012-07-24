$(document).ready( function() {
   
   $('#name_pref').hide(); $('#name_input').hide(); $('#name_hint').hide();
   $('#loc_label').hide(); $('#loc_input').hide(); $('#loc_hint').hide();
   
   $('#req_location').bind( 'change', function(evt) {       
       if(evt.target.value==1) {
           $('#loc_label').hide();
           $('#loc_input').hide();
           $('#loc_hint').hide();
       } else {
           $('#loc_label').show().css('display','inline');
           $('#loc_input').show().css('display','inline');
           $('#loc_hint').show().css('display','inline');
       }
      });
      
   $('#req_name').bind( 'change', function(evt) {
         if(evt.target.value==1) {
             $('#name_pref').hide();
             $('#name_input').hide();
             $('#name_hint').hide();       
         } else {
             $('#name_pref').show().css('display','inline');
             $('#name_input').show().css('display','inline');
             $('#name_hint').show().css('dispaly', 'inline');
         }

    });
    
});
