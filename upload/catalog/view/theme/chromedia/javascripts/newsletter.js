// $('.btn-newsletter-subscription').on('click', function(e) {
//     var self = $(this);
//     var email = 

//     e.preventDefault();
//     self.hide();
//     self.showLoader();

//     $.ajax({
//         type: "POST",
//         url: 'index.php?route=account/newsletter',
//         data: { email : email },
//         dataType: 'json',     
//         success: function(jsondata){
//             self.show();
//             self.removeLoader();

//             if (jsondata.success) {
                
//             }
//         }
//     });
// });