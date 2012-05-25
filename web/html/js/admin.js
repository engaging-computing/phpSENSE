function gatherCheckedValues(itemId) {
    var x = new Array();
    
    $('#'+itemId).find('tr > td > input:checkbox').each(function(i){
       if($(this).attr('checked')){
           x.push($(this).val());
       } 
    });
    
    return x;
}

function findItems(tableId, columnAttr, attrState) {
    $('#'+tableId).find('tr:not(tr.header)').each(function() {        
       if($(this).find('td.'+columnAttr).text() == attrState)  {
           $(this).find('td > input:checkbox').attr('checked', true);
       }
    });
}

function checkAllFeaturedExperiments() {
    findItems('management_table', 'featured', 'Yes');
}

function checkAllNonFeaturedExperiments() {
    findItems('management_table', 'featured', 'No');
}

function checkAllHiddenExperiments() {
    findItems('management_table', 'hidden', 'Yes');
}

function checkAllVisibleExperiments() {
    findItems('management_table', 'featured', 'No');
}

function checkAllAdminUsers() {
    findItems('management_table', 'administrator', 'Yes');
}

function checkAllRegularUsers() {
    findItems('management_table', 'administrator', 'No');
}

function checkAllPublishedArticles() {
    findItems('management_table', 'published', 'Yes');
}

function checkAllUnpublishedArticles() {
    findItems('management_table', 'published', 'No');
}

function checkAll() {
    $('#management_table').find('td > input:checkbox').each( function() {
	$(this).attr( 'checked', true );
    });
}

function uncheckAll() {
    $('#management_table').find('td > input:checkbox').each( function() {
	$(this).attr( 'checked', false );
    });
}

function checkAllUpcomingEvents() {
    var today = new Date();
    $('#management_table').find('tr:not(tr.header)').each(function() {        
       var d = Date.parse($(this).find('td.start').text().replace(/-/gi, '/'));
       if(d >= today) {
          $(this).find('td > input:checkbox').attr('checked', true);
       }
    });
}

function checkAllPastEvents() {
    var today = new Date().getTime();
    $('#management_table').find('tr:not(tr.header)').each(function() {
        var d = Date.parse($(this).find('td.start').text().replace(/-/gi, '/'));
        if(d < today) {
            $(this).find('td > input:checkbox').attr('checked', true);
        }
    });
}

function deleteEvent() {

    var del = new Array();
    var loc = '../admin.php?action=deleteevent&names=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;
    });
}

function deleteNews() { 
	
    var del = new Array();
    var loc = '../admin.php?action=deletenews&nid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function publishNews() { 
	
    var del = new Array();
    var loc = '../admin.php?action=newspublish&nids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function deleteUser() { 
	
    var del = new Array();
    var loc = '../admin.php?action=deleteuser&uid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(5).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function adminUser() { 
	
    var del = new Array();
    var loc = '../admin.php?action=makeadmin&uid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(5).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}


function unhideSession(){
    var del = new Array();
    var loc = '../admin.php?action=sessionunhide&sid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

    
	for( var i = 0; i < del.length; i++ ) {
       if( i < del.length - 1){
            loc += del[i] + ':';
       } else {
             loc += del[i];
       }

	}

	window.location = loc;
}

function deleteSession(){
    var del = new Array();
    var loc = '../admin.php?action=sessiondelete&sid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
       if( i < del.length - 1){
            loc += del[i] + ':';
       } else {
             loc += del[i];
       }
	}
	window.location = loc;
}

function hideSession(){
    var del = new Array();
    var loc = '../admin.php?action=sessionhide&sid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

    
	for( var i = 0; i < del.length; i++ ) {
       if( i < del.length - 1){
            loc += del[i] + ':';
       } else {
             loc += del[i];
       }

	}

	window.location = loc;
}

function deleteExperiment()  { 
	
    var del = new Array();
    var loc = '../admin.php?action=deleteexperiment&eid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function featureExperiment()  { 
	
    var del = new Array();
    var loc = '../admin.php?action=featureexperiment&eid=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function deleteHelp() { 
	
    var del = new Array();
    var loc = '../admin.php?action=deletehelp&hids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function publishHelp() { 
	
    var del = new Array();
    var loc = '../admin.php?action=helppublish&hids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function deleteFaq() { 
	
    var del = new Array();
    var loc = '../admin.php?action=deletefaq&fids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function publishFaq() { 
	
    var del = new Array();
    var loc = '../admin.php?action=faqpublish&fids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(0).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

function resetPass() { 
	
    var del = new Array();
    var loc = '../admin.php?action=passreset&uids=';

    $('#management_table').find('td > input:checkbox').each( function() {
	if( $(this).attr('checked') ) 
	    del[del.length] = $(this).parent().siblings(1).eq(5).children().attr('href').split("=")[1];
    });

	for( var i = 0; i < del.length; i++ ) {
	  loc += del[i] + ':';
	}

	window.location = loc;

}

