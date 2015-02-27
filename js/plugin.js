/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


;
jQuery(function ($) {
    if (typeof rc_object != "undefined") {
        var template = $("#relatify_output").html();
        $("#relatify_output_target").html(_.template(template, {items: rc_object}));
    }
});