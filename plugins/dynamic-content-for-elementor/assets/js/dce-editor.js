/* 
 * DCE EDITOR
 * dynamic.ooo
 */

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
function getUrlParam(parameter, defaultvalue){
    var urlparameter = defaultvalue;
    if(window.location.href.indexOf(parameter) > -1){
        urlparameter = getUrlVars()[parameter];
    }
    return urlparameter;
}

function dce_disable_save_button() {
    // enable save buttons
    jQuery('#elementor-panel-saver-button-publish, #elementor-panel-saver-button-save-options, #elementor-panel-saver-menu-save-draft').addClass('elementor-saver-disabled').prop('disabled', true);
    return true;
}

function dce_enable_save_button() {
    // enable save buttons
    //console.log('enable save button');
    jQuery('#elementor-panel-saver-button-publish, #elementor-panel-saver-button-save-options, #elementor-panel-saver-menu-save-draft').removeClass('elementor-saver-disabled').removeClass('elementor-disabled').prop('disabled', false).removeProp('disabled');
    return true;
}

/*
function dce_popup_toggle(cid, navigator) {
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    if (change_data) {
        if (settings['show_popup_editor']) {
            elementorFrontend.config.elements.data[cid].attributes['show_popup_editor'] = '';
        } else {
            elementorFrontend.config.elements.data[cid].attributes['show_popup_editor'] ='yes';
        }
    }
    //dce_menu_list_item_toggle(cid);
    if (navigator) {
        elementor_navigator_element_toggle(cid);
    }
    var eid = dce_get_element_id_from_cid(cid);
    return true;
}
*/

/******************************************************************************/

// RAW PHP
jQuery(window).load(function() {
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    //console.log(elementor);
    if (jQuery('#elementor-preview-iframe').length) {
        setInterval(function(){
            if (iFrameDOM.find("div.elementor-widget-dce-rawphp").length) { 
                if (iFrameDOM.find("div.elementor-widget-dce-rawphp.elementor-loading").length) { 
                    //&& iFrameDOM.find("div[data-id=<?php echo $this->get_id(); ?>]").hasClass('elementor-loading')) {
                    dce_disable_save_button();
                    jQuery('#elementor-panel-saver-button-publish').addClass('elementor-saver-disabled-dce');
                    jQuery('.dce-notice-phpraw').slideDown();
                    //console.log('errore');
                } else {
                    if (jQuery('#elementor-panel-saver-button-publish').hasClass('elementor-saver-disabled-dce')) {
                        dce_enable_save_button();
                        jQuery('#elementor-panel-saver-button-publish').removeClass('elementor-saver-disabled-dce');
                    }
                    jQuery('.dce-notice-phpraw').slideUp();
                }
            }
            //console.log('controllato php_raw');
        }, 1000);
    }
});

jQuery(document).ready(function() {

    jQuery(document).on('mousedown','.elementor-control-repeater_shape_path .elementor-repeater-fields, .elementor-control-repeater_shape_polyline .elementor-repeater-fields',function(){
        var repeater_index = $(this).index();
        //alert('shape'+repeater_index);
        // ------------
        var eid = dce_get_element_id_from_cid(dce_model_cid);
        var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
        var morphed = iFrameDOM.find('.elementor-element[data-id='+eid+'] svg.dce-svg-morph');
        // ------------
        //morphed.trigger('changeDataMorph',[repeater_index]);
        if(morphed.attr('data-run') == 'paused') morphed.attr('data-morphid',repeater_index);
        //scambiaSVGmorphing();
        //morphed.data('changeDataMorph')();
        //morphed.data("morphid", repeater_index).trigger('changeDataMorph');

        //alert(morphed.attr('class')+repeater_index);
        //alert(eid);
        //alert( $(this).index() );
    });
    jQuery(document).on('change','.elementor-control-playpause_control',function(){
            var runAnimation = elementorFrontend.config.elements.data[dce_model_cid].attributes['playpause_control'];

            // ------------
            var eid = dce_get_element_id_from_cid(dce_model_cid);
            var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
            var morphed = iFrameDOM.find('.elementor-element[data-id='+eid+'] #dce-svg-'+eid);
            // ------------
            morphed.attr('data-run',runAnimation);

            //morphed.data("run", runAnimation).trigger('changeData'); 
            //alert(morphed.attr('class')+repeater_index);
            //alert(eid);
            //alert( runAnimation );
        });
});

/******************************************************************************/

/*
// POPUP
jQuery(document).ready(function() {
    
    jQuery(document).on('click', '.elementor-navigator__element__toggle', function() {
        var element = jQuery(this).closest('.elementor-navigator__element');
        var cid = element.data('model-cid');   
        var eid = jQuery(this).data('eid');
        //console.log('dce visibility navigator '+cid);
        dce_popup_toggle(cid, true);
    });
    
    jQuery(document).on('change', '.elementor-switch-input[data-setting=show_popup_editor]', function() {
        //var cid = jQuery(this).attr('id').split('-').pop();
        var cid = dce_model_cid;
        //console.log('dce visibility settings '+cid);
        dce_popup_toggle(cid, false);
    });
    
});
*/

