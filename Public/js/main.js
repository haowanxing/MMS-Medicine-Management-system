/**
 * Created by anthony on 16/6/15.
 */
var NOW_PAGE;//全局
$(document).ready(function () {
    var menu = $(".list-menu");
    var func = $(".func");

    func.first().fadeIn();
    menu.click(function (ev) {
        ev.stopPropagation();
        menu.removeClass("active");
        var to = this.getAttribute("data-to");
        $("[data-to='" + to + "']").addClass("active");
        func.fadeOut(0);
        func.eq(to).fadeIn(0);
    });
    //时间选择
    $(".form_datetime").datepicker({
        //format: "dd MM yyyy - hh:ii"
        format: "yyyy/MM/dd",
        language: 'zh-CN',
        autoclose: true,
    });
    //模态框关闭
    $('.modal').on('hidden.bs.modal', function (e) {
        var form = $(this).find("form")[0];
        if(form){
            form.reset();
        }
    })
});
function loadMore(target, url, page) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {"do": "loadmore", "page": page},
        success: function (data) {
            if (data) {
                console.log(data);
                $(target).html(data.table);
            }
        }
    });
}
function formatDate(timestamp) {
    var date = new Date(parseInt(timestamp) * 1000);
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    var retString = year + "/" + month + "/" + day;
    if (hour != 0 && minute != 0 && second != 0) {
        retString += " " + hour + ":" + minute + ":" + second
    }
    return retString;
}