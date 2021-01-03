$(document).ready(function(){
   
    $('.edit_details').find(':input').attr('readonly',true);
    $('.edit_button').on('click',function(){
       $('.submit_button').removeAttr('hidden');
       $('.edit_details').find(':input').removeAttr('readonly');
       $('.edit_button').attr('hidden',true);
    });
   
});