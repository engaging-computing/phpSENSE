<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

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
