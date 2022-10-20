$(document).ready(function() {
    $('#for_status, #for_priority, #for_category, #for_assignto, #for_reportby, #for_paginate').dropdown({});

    // $('#summernote').summernote({
    //   placeholder: 'Type Message Here..',
    //   tabsize: 2,
    //   height: 300,
    //   toolbar: [
    //       // [groupName, [list of button]]
    //       ['style', ['bold', 'italic', 'underline', 'clear']],
    //       ['font', ['strikethrough', 'superscript', 'subscript']],
    //       ['fontsize', ['fontsize']],
    //       ['color', ['color']],
    //       ['para', ['ul', 'ol', 'paragraph']],
    //       ['height', ['height']],
    //       ['undo'],['redo'],['fullscreen']
    //     ]
    // });

    $('.ui.form').form({
      fields: {
        title     : ['minLength[5]', 'empty'],
        description     : ['minLength[5]', 'empty'],
        created_by : 'empty',
        report_by : 'empty',
      }
    });
});