/**
 * Created by anthony on 16/6/15.
 */
$(document).ready(function(){
    var menu = $(".list-menu");
    var func = $(".func");

    func.first().fadeIn();
    menu.click(function(ev){
        ev.stopPropagation();
        menu.removeClass("active");
        var to = this.getAttribute("data-to");
        $("[data-to='"+to+"']").addClass("active");
        func.fadeOut(0);
        func.eq(to).fadeIn(0);
    });
    //时间选择
    $(".form_datetime").datepicker({
        //format: "dd MM yyyy - hh:ii"
        format: "yyyy/MM/dd",
        language: 'zh-CN',
        autoclose:true,
    });
});
