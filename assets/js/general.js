jQuery(document).ready(function($) {
    
    $('#date').datepicker({
        dateFormat: "yy/mm/dd"
    });

    $('#color').wpColorPicker();

    $('body').on( 'click', '.photo-upload', function(e){

        e.preventDefault();

        var button = $(this),
        custom_uploader = wp.media({
            title: 'Insert image',
            library : {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type : 'image'
            },
            button: {
                text: 'Use this image' // button label text
            },
            multiple: false
        }).on('select', function() { // it also has "open" and "close" events
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            button.html('<img src="' + attachment.url + '">').next().show().next().val(attachment.id);
        }).open();
    
    });

    // on remove button click
    $('body').on('click', '.photo-remove', function(e){

        e.preventDefault();

        var button = $(this);
        button.next().val(''); // emptying the hidden field
        button.hide().prev().html('Upload image');
    });
});