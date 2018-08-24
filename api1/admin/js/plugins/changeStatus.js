$(document).ready(function() {   

       // toggle active
    $('a.tickEnable').click(function(e) {
        e.preventDefault();
        var ele = $(this);
        var loading = ele.next();
        var child = ele.children('img');
        var id = ele.attr('id');
        var status = ele.attr('href');
        var field = ele.attr('data-field');
        var model = document.adminForm.model.value;
        $(this).hide();
        $.ajax({
            beforeSend : function() {
                loading.show();
            },
            success : function() {
                loading.hide();
            },
            url : '../../ajax/active.php',
            type: 'post',
            data : {id : id, status : status, model : model, field : field},
            complete : function(result) {
                if(status == 0) {
                    child.attr('src', '../../images/publish_x.png');
                    ele.attr('href', 1);
                }
                else {
                    child.attr('src', '../../images/tick.png');
                    ele.attr('href', 0);
                }
                ele.show();
            }
        });
    });


});
function changeStatus(id,thisz){
var model = $('#adminForm').find('input[name="model"]').val();
var status =$(thisz).attr('href');
/*if (status==0) {
    status=1;
}else if( status==1){
 status=0;
}*/
var field =$(thisz).attr('data-field');
        var loading = $(thisz).next();

                $(thisz).hide();
        $.ajax({
            beforeSend : function() {
                loading.show();
            },
            success : function() {
                loading.hide();
            },
            url : '../../ajax/active.php',
            type: 'post',
            data : {id : id, status : status, model : model, field : field},
            complete : function(result) {
                if(status == 0) {
                    $(thisz).attr('src', '../../images/publish_x.png');
                    $(thisz).attr('href', 1);
                }
                else {
                    $(thisz).attr('src', '../../images/tick.png');
                    $(thisz).attr('href', 0);
                }
                $(thisz).show();
            }
        });
}