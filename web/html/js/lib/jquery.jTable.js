(function( $ ){

  var root;

  var methods = {
    init : function( options ) {

      //Add content
      //Add drop down triangles to headers
      $(this).find('table thead tr').children().each(function() {
        $(this).html($(this).text() + '  <img src="../html/images/forward_enabled.png" />');
      });

      //Hack quote issues
      $("table.edit_table tbody tr td").each(function(i, k)
      {
        $(k).text($(k).text().replace(/\\|"/g, ""))
      });
      
      //Add edit buttons
      if( !$('.new_row_active').length ){
        $(this).find('table').addClass('edit_table').before('<div class="edit_controls"><button type="button" class="new_row_active">New Row</button><button type="button" class="edit_row_not">Edit Row</button><button type="button" class="delete_row_not">Delete Row</button></div><div class="save_controls"><button type="button" class="jTable_save">Save</button></div>');

        //Deal with adding a new row
        if($('.new_row_active')) {
          $('.new_row_active').unbind().click(function(){
            //Create lightbox DOM
            //Drop Veil
            $(document).find('body').append('<div class="jTable_lightbox_veil"></div><div class="jTable_lightbox_body round_10"></div>');
            //Lightbox header
            tmp = '<span><h2>Create New Entry:</h2></span><br/><div class="jTable_lightbox_body_table_wrap"><table><thead><tr>';
            //Populate table header
            $(root).find('thead tr').children().each(function(index) {
              tmp +='<td><h4>' + $(root).find('thead tr').children().eq(index).text() + ': </h4></td>';
            });
            //End table header
            tmp += '</tr></thead><tbody><tr>';
            //Populate value input
            $(root).find('tbody tr').eq(0).children().each(function(index) {
              tmp += '<td><input type="text" value=" " /></td>';
            });

            //End table
            tmp += '</tr></tbody>';
            //Add buttons
            tmp += '</table></div><div class="jTable_lightbox_buttons"><button id="jTable_ok_row">Ok</button><button id="jTable_cancel_row">Cancel</button></div>';
            //Populate lightbox
            $('.jTable_lightbox_body').append(tmp);
            //Lightbox handelers
            $('#jTable_cancel_row').click(function() {
              $('.jTable_lightbox_veil').remove();
              $('.jTable_lightbox_body').remove();
            });

            $('#jTable_ok_row').click(function(){
              //Create new row DOM
              new_row = '<tr>';
              //Populate new row
              $('.jTable_lightbox_body table tbody tr').children().each(function(index){
                new_row += '<td>' + $(this).children().val() + '</td>';
              });
              //End new row
              new_row += '</tr>';
              //Insert new row at the top of the table
              if( $(root).find('.jTable_selected').length ) {
                $(root).find('.jTable_selected').before(new_row);
                //Add new row before selected row
              } else {
                $(root).find('tbody').children().eq(0).before(new_row);
              }
              //Break down lightbox
              $('.jTable_lightbox_veil').remove();
              $('.jTable_lightbox_body').remove();
              //Update
              $(root).jTable('update');

            });

          });
        }
      }

      //When you click the save button at the top right of the table
      //Save functionality
      $('.jTable_save').click(function() {
        //Build an object to save the table
        save_table = new Array();
        save_table.header = new Array();
        save_table.body = new Array();

        //this should go through each table header and add it to the save_table.header array
        $(root).find('table thead tr').children().each( function(index) {
          save_table.header[index] = $(this).text();
        });

        $(root).find('table tbody tr').each(function(indexI){

          cur_row = new Array();

          $(this).children().each(function(indexJ) {
            cur_row[indexJ] = $(this).text();
          });

          save_table.body[indexI] = cur_row;

        });
        
        save_table.eid = Number($('#ExperimentID').text());
        save_table.sid = Number($('#SessionID').text());

        $.ajax({
          type: "POST",
          url: "../../ses-update.php",
          data: { t_eid : save_table.eid, t_sid : save_table.sid , t_head : save_table.header, t_data : save_table.body },
          success: function(data, status) {
            alert("Changes saved.");
          }
        });

      });

      //Update
      $(root).jTable('update');
    },
 update : function( content ) {

   //unset CSS
   $('.jTable_header').removeClass('jTable_header');
   $('.row_even').removeClass('row_even');
   $('.row_odd').removeClass('row_odd');

   //No multibinds
   $(root).find('tbody').children().each(function(){
     $(this).unbind('click');
   });

   //Set handeler for selected
   $(root).find('tbody tr').click( function( e ) {

     //No multibinds
     $('.edit_controls').children().unbind();

     //Deal with adding a new row
     if($('.new_row_active')) {
       $('.new_row_active').unbind().click(function(){
         //Create lightbox DOM
         //Drop Veil
         $(document).find('body').append('<div class="jTable_lightbox_veil"></div><div class="jTable_lightbox_body round_10"></div>');
         //Lightbox header
         tmp = '<span><h2>Create New Entry:</h2></span><br/><div class="jTable_lightbox_body_table_wrap"><table><thead><tr>';
         //Populate table header
         $(root).find('thead tr').children().each(function(index) {
           tmp +='<td><h4>' + $(root).find('thead tr').children().eq(index).text() + ': </h4></td>';
         });
         //End table header
         tmp += '</tr></thead><tbody><tr>';
         //Populate value input
         $(root).find('tbody tr').eq(0).children().each(function(index) {
           tmp += '<td><input type="text" value=" " /></td>';
         });

         //End table
         tmp += '</tr></tbody>';
         //Add buttons
         tmp += '</table></div><div class="jTable_lightbox_buttons"><button id="jTable_ok_row">Ok</button><button id="jTable_cancel_row">Cancel</button></div>';
         //Populate lightbox
         $('.jTable_lightbox_body').append(tmp);
         //Lightbox handelers
         $('#jTable_cancel_row').click(function() {
           $('.jTable_lightbox_veil').remove();
           $('.jTable_lightbox_body').remove();
         });

         $('#jTable_ok_row').click(function(){
           //Create new row DOM
           new_row = '<tr>';
           //Populate new row
           $('.jTable_lightbox_body table tbody tr').children().each(function(index){
             new_row += '<td>' + $(this).children().val() + '</td>';
           });
           //End new row
           new_row += '</tr>';
           //Insert new row at the top of the table
           if( $(root).find('.jTable_selected').length ) {
             $(root).find('.jTable_selected').before(new_row);
             //Add new row before selected row
           } else {
             $(root).find('tbody').children().eq(0).before(new_row);
           }
           //Break down lightbox
           $('.jTable_lightbox_veil').remove();
           $('.jTable_lightbox_body').remove();

           //Update
           $(root).jTable('update');

         });

       });
     }

     //If you click the same row
     if ( $(this).hasClass('jTable_selected') ) {

       //Toggle selected
       $(this).removeClass('jTable_selected');

       if($('.delete_row_active')) {
         //Deactivate delete
         $('.delete_row_active').removeClass('delete_row_active').addClass('delete_row_not');
       }
       if($('edit_row_active')){
         //Deactivate edit
         $('.edit_row_active').removeClass('edit_row_active').addClass('edit_row_not');
       }

       $(root).jTable('update');

       //Or if you choose a different row
     } else {

       //Only one selected at a time
       $('.jTable_selected').removeClass('jTable_selected');
       $(this).addClass('jTable_selected');

       if($('.delete_row_not')) {

         //Activate Delete
         $('.delete_row_not').removeClass('delete_row_not').addClass('delete_row_active');

         //Delete button handeler
         $('.delete_row_active').click(function() {
           //Remove selected row
           $('.jTable_selected').remove();
           //Deactivate delete
           $('.delete_row_active').removeClass('delete_row_active').addClass('delete_row_not');
           //Deactivate edit
           $('.edit_row_active').removeClass('edit_row_active').addClass('edit_row_not');
           //Update
           $(root).jTable('update');
         });
       }

       //Update edit button
       if($('.edit_row_not')) {
         //Activate edit
         $('.edit_row_not').removeClass('edit_row_not').addClass('edit_row_active');
         //Deal with editing an existing row
         $('.edit_row_active').click(function() {
           //Create lightbox DOM
           //Drop Veil
           $(document).find('body').append('<div class="jTable_lightbox_veil"></div><div class="jTable_lightbox_body round_10"></div>');
           //Lightbox header
           tmp = '<span><h2>Edit Existing Entry:</h2></span><br/><div class="jTable_lightbox_body_table_wrap"><table><thead><tr>';

           //Populate table header
           $(root).find('thead tr').children().each(function(index) {
             tmp +='<td><h4>' + $(root).find('thead tr').children().eq(index).text() + ': </h4></td>';
           });
           //End table header
           tmp += '</tr></thead><tbody><tr>';
           //Populate value input
           $('.jTable_selected').children().each(function(index) {
             console.log($(this).text());
             tmp += '<td><input type="text" value="' + $(this).text() + '" /></td>';
           });
           //End table
           tmp += '</tr></tbody>';
           //Add buttons
           tmp += '</table></div><div class="jTable_lightbox_buttons"><button id="jTable_ok_row">Ok</button><button id="jTable_cancel_row">Cancel</button></div>';

           //Populate lightbox
           $('.jTable_lightbox_body').append(tmp);

           //Lightbox handelers
           $('#jTable_cancel_row').click(function() {
             $('.jTable_lightbox_veil').remove();
             $('.jTable_lightbox_body').remove();
           });

           $('#jTable_ok_row').click(function() {

             $('.jTable_lightbox_body table tbody tr').children().each(function(index) {
               //Replace content with lightbox values
               $('.jTable_selected').children().eq(index).text($(this).children().eq(0).val());
             });

             //Break down lightbox
             $('.jTable_lightbox_veil').remove();
             $('.jTable_lightbox_body').remove();

           });

           //Update
           $(root).jTable('update');
         });
       }
     }

   });

   //Bind table headers
   $(this).find('thead').addClass('jTable_head').find('tr').each(function(index) {

     //Add table header CSS
     $(this).addClass('jTable_header');
     $(this).children().each(function(index) {
       $(this).attr('id', 'col_'+index);

       $(this).mouseover( function() {
         $(this).find('img').addClass('jTable_rotate_right');
       });
       $(this).mouseout( function() {
         $(this).find('img').removeClass('jTable_rotate_right');
       });

       $(this).click(function(event, ui){

         $(this).find('img').addClass('jTable_open');

         //Find index of the header that got pressed
         header_index = $(this).attr('id').substr(4);

         sortArray = new Array();
         var new_table = '';

         //Populate sort array from header
         $(this).parent().parent().parent().find('tbody tr').each(function(index) {
           sortArray[index] = new Array( index, $(this).children().eq(header_index).text() );
         });

         //Sort array
         sortArray.sort(function(a,b){return a[1]-b[1];});

         //Rearange table DOM
         $(sortArray).each(function() {
           new_table += '<tr>';
           $(root).find('table tbody tr').eq(this[0]).children().each(function() {new_table += '<td>' + $(this).text() + '</td>'});
           new_table += '</tr>';
         });

         //Delete DOM
         $(this).parent().parent().parent().find('tbody tr').remove();

         //Insert new DOM
         $(this).parent().parent().parent().find('tbody').html(new_table);


         $(root).jTable('update');

       });

     });
   });

   //reset CSS
   $(this).find('tbody').addClass('jTable_body').children().each(function(index) {
     if( index % 2 )
       $(this).addClass('row_even');
     else
       $(this).addClass('row_odd');

     //Bind .draggable pluging
       $(this).draggable({

         start: function(event, ui) {
           //Drag target CSS
           //$(this).addClass('drag_target');
           $('.jTable_selected').removeClass('jTable_selected');
           $(this).addClass('jTable_selected');
         },
         stop: function(event, ui) {

           //find mouse position
           mouseX = event.pageX;
           mouseY = event.pageY;

           //Remove hover and drag target CSS
           $('.jTable_highlight_top').removeClass('jTable_highlight_top');
           $('.jTable_highlight_bot').removeClass('jTable_highlight_bot');
           $(this).removeClass('drag_target');

           //Drop drag target
           $(this).parent().children().each(function(index) {

             //Dont add it to drag target
             if( this != event.target ) {

               //Keep drop within the table x
               if( mouseX > $(this).offset().left && mouseX < ($(this).offset().left + $(this).width()) ) {

                 //Drop above
                 if( mouseY > $(this).offset().top && mouseY < ($(this).offset().top + ($(this).height()/2) ) ) {

                   //Prepend with drag target
                   $(this).parent().children().eq(index).before(event.target);
                   //Update
                   $(root).jTable('update');

                   //Drop below
                 } else if( mouseY > ($(this).offset().top + ($(this).height()/2)) && mouseY < ($(this).offset().top + ($(this).height()) ) ) {

                   //Append with drag target
                   $(this).parent().children().eq(index).after(event.target);
                   //Update
                   $(root).jTable('update');
                 }
               }
             }
           });
         },
         drag: function(event, ui) {

           //Get mouse position
           mouseX = event.pageX;
           mouseY = event.pageY;

           //Remove hover CSS
           $('.jTable_highlight_top').removeClass('jTable_highlight_top');
           $('.jTable_highlight_bot').removeClass('jTable_highlight_bot');

           //Set drag hover
           $(this).parent().children().each(function() {

             //Dont hover over the target
             if( !$(this).hasClass('drag_target') ) {

               //Keep hover within the table x
               if( mouseX > $(this).offset().left && mouseX < $(this).offset().left + $(this).width() ) {

                 //Hover above
                 if( mouseY > $(this).offset().top && mouseY < ($(this).offset().top + ($(this).height()/2) ) ) {
                   $(this).addClass('jTable_highlight_top');
                   //Hover below
                 } else if( mouseY > ($(this).offset().top + ($(this).height()/2)) && mouseY < ($(this).offset().top + ($(this).height()) ) ) {
                   $(this).addClass('jTable_highlight_bot');
                 }
               }
             }
           });
         }
       });
   });

 }
  };

  //Define jTable Plugin
  $.fn.jTable = function( method ) {

    root = this;

    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.jTable' );
    }

  };

})( jQuery );