/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    
    $('.date').datepicker({ dateFormat: 'yy-mm-dd' });
    $('.spinner').spinner();
    
    $(".box .h_title").not(this).next("ul").hide("normal");
    $(".box .h_title").not(this).next("#home").show("normal");
    $(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
});

function SelectAll(elem){
    $(".align-center :checkbox").prop('checked', $(elem).prop('checked'));
}

function DeletAll(path){
    if($('input[class=cb]:checked').length>0){
                 $('#adminform').attr("action", path);
                 $('#adminform').submit();
                 }else{
                     $('#mes p').text('Необходимо Выбрать хотябы один элемент');
                     $('#mes').prop('title','Ошибка');
                     $('#mes').dialog({
                         modal: true,
                         buttons: {
                             Ok: function() { $( this ).dialog( "close" );}
                                 }
                             });
                     }
          return false;
}
 