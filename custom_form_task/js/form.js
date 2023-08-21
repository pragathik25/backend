// (function ($, Drupal) {
//     $(document).ready(function () {
//         var $noLastName = $('#no-last-name');
//         var $lastName = $('.form-item-last-name');
//         if ($noLastName.is(':checked')) {
//             $lastName.hide();
//         }

//         $noLastName.on('change', function () {
//         if ($(this).is(':checked')) {
//             $lastName.hide();
//         } else {
//             $lastName.show();
//         }
//         });
//     });
// }(jQuery, Drupal));
document.addEventListener('DOMContentLoaded', function() {
    const noLastName = document.getElementById('no-last-name');
    const lastName = document.querySelector('.form-item-last-name');

    if (noLastName.checked) {
    lastName.style.display = 'none';
    }

    noLastName.addEventListener('change', function() {
    if (this.checked) {
        lastName.style.display = 'none';
    } else {
        lastName.style.display = 'block';
    }
    });
});
