/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


;jQuery(function($) {

    $(document).on('click', '.gallery_meta_upload', function() {

        var send_attachment_bkp = wp.media.editor.send.attachment;
        var obj = $(this);

        wp.media.editor.send.attachment = function(props, attachment) {

            obj
                .siblings('input.default_image_src').val(attachment.url)
                .siblings('input.default_image_id').val(attachment.id)
                .siblings('img').attr('src', attachment.url).show();
            wp.media.editor.send.attachment = send_attachment_bkp;
        }

        wp.media.editor.open();

        return false;
    });

    $('.toggle-items').click(function(e) {
        e.preventDefault();
        $(this).siblings('.all_items').slideToggle();
        return false;
    });

});