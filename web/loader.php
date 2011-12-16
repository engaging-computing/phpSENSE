<?php

require_once 'includes/config.php';

set_include_path(LIB_DIR . 'min/lib/' . PATH_SEPARATOR . get_include_path());

require_once 'Minify.php';

Minify::setCache(); // in 2.0 was "useServerCache"
Minify::serve('Groups', array(
    'groups' => array(
        
        'admin' => array(
                            BASE_DIR . '/html/js/admin.js'
                        ),
        
        'js'     => array(    
                           // BASE_DIR . '/html/js/lib/jquery.js',
                            BASE_DIR . '/html/js/lib/jquery-ui.js',
                            BASE_DIR . '/html/js/lib/thickbox.js',
                            BASE_DIR . '/html/js/lib/flydom.js',
                            BASE_DIR . '/html/js/lib/autocomplete.js',
                            BASE_DIR . '/html/js/lib/rating.js',
                            BASE_DIR . '/html/js/isense.js',
                        ),

        
        'css'     => array(    
                            BASE_DIR . '/html/css/rating.css',
                            BASE_DIR . '/html/css/style.css',
                            BASE_DIR . '/html/css/create.css',
                            BASE_DIR . '/html/css/lightbox.css',
                            BASE_DIR . '/html/css/jquery-ui.css',
                            BASE_DIR . '/html/css/tsor.css',
                        ),
        
        'vis'     => array(     
                            BASE_DIR . '/html/js/vis/ScatterChartModule.js',
                            BASE_DIR . '/html/js/vis/SessionMapModule.js',
                            BASE_DIR . '/html/js/vis/MapModule.js',
                            BASE_DIR . '/html/js/vis/MotionChartModule.js',
                            BASE_DIR . '/html/js/vis/isenseMap.js',
                            BASE_DIR . '/html/js/vis/AnnotatedTimeLineModule.js',
                            BASE_DIR . '/html/js/vis/ColumnChartModule.js',
                            BASE_DIR . '/html/js/vis/TableModule.js',
                            BASE_DIR . '/html/js/vis/HistogramModule.js',
                            BASE_DIR . '/html/js/vis/vis.js'
                        ),
                        
        'viscss' => array(
                            BASE_DIR . '/html/css/jquery-ui.css'
                        ),
                        
                        'vis2'  => array(
                                            BASE_DIR . '/html/js/vis2/flot/excanvas.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.colorhelpers.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.crosshair.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.image.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.navigate.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.selection.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.stack.js',
                                            BASE_DIR . '/html/js/vis2/flot/jquery.flot.threshold.js',
                                            BASE_DIR . '/html/js/vis2/logmanager.js',
                                            BASE_DIR . '/html/js/vis2/vis2.js',
                                        ),

                        'flot'  => array(
                                            BASE_DIR . '/html/js/lib/jquery.js',
                                            BASE_DIR . '/html/js/lib/jquery-ui.js',
                                            BASE_DIR . '/html/js/flot/jquery.colorhelpers.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.crosshair.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.image.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.navigate.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.selection.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.stack.js',
                                            BASE_DIR . '/html/js/flot/jquery.flot.threshold.js',
                                            BASE_DIR . '/html/js/flot/excanvas.js'
                                        ),
        'newvis' => array(
            BASE_DIR . '/html/js/vis2/flot/excanvas.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.colorhelpers.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.crosshair.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.image.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.navigate.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.selection.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.stack.js',
            BASE_DIR . '/html/js/vis2/flot/jquery.flot.threshold.js',
            BASE_DIR . '/html/js/vis2/app/logmanager.js',
            BASE_DIR . '/html/js/vis2/app/vismanager.js',
            BASE_DIR . '/html/js/vis2/app/timeline.js',
            BASE_DIR . '/html/js/vis2/app/scatter.js',
            BASE_DIR . '/html/js/vis2/app/bar.js',
            BASE_DIR . '/html/js/vis2/app/map.js',
            BASE_DIR . '/html/js/vis2/app/table.js',
            BASE_DIR . '/html/js/vis2/vis2.js',
        ),
	'WYSIWYG' => array(
	    BASE_DIR . '/html/js/nicEdit/nicEdit.js',
	    BASE_DIR . '/html/js/nicEdit/nicStart.js'
	)
    )
));


?>
