$(document).ready(function() {
    $('.ui.form')
    .form({
      fields: {
        password     : ['minLength[5]', 'empty'],
        retype_password   : ['minLength[5]','match[password]', 'empty'],
      }
    });
});