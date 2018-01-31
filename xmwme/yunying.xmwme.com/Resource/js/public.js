/**
 * 设置左导航与内容高度一致
 * j+2
 */
$(function(){
    var $subnav = $("#subnav");
    var $content = $subnav.siblings('.content');

    setTimeout(function(){
        var sh = $subnav.height();
        var ch = $content.height();
        if(sh>ch){
            $content.height(sh);
        }else if(sh<ch){
            $subnav.height(ch);
        }
    },1000)
})



$.fn.tabScroll = function(option){
    var opt = {

    }
    if($(this).find("tbody tr").length === 1 && $(this).find("tbody td").length === 1){
        $(this).find("table").width("100%");
        return;
    }
    var th = $(this).find("thead th:last").text();

    var $control = $('<table class="tab-list-1 tab-control">\
					<thead>\
						<tr>\
							<th>'+ th +'</th>\
						</tr>\
					</thead>\
					<tbody></tbody>\
				</table>');

    $(this).find("table").wrapAll($('<div class="tab-scroll"></div>'));

    $(this).find("tbody tr").each(function(i){
        $control.find("tbody").append('<tr><td>'+ $(this).find("td:last").html() + '</td></tr>');
    })

    $(this).append($control);
}

$(function(){
    $("#tabScroll").tabScroll();
})

$(document).ready(function(){
    $('input[type="checkbox"]').click(function(){
        var model = $(this).attr("name");
        var o_list = $(this).val() + "Model[]";
        if(model == "model[]"){
            $("input[name='"+o_list+"']").attr("checked",$(this).attr("checked"));
        }else{
            var modelNam = model.substr(0,model.length-7);
            if($("input[@type=checkbox][name='"+ model +"']:checked").size() == 0) {
                //$("#" + modelNam).attr("checked",false);
            }else {$("#" + modelNam).attr("checked",true);}
        }
    });
});

function show_prate(obj)
{
    if($(obj).val() == 1 || $(obj).val() == 2 || $(obj).val() == 5){
        $('#prate').attr('disabled', false);
    }else{
        $('#prate').attr('disabled', true);
    }
}
/**
 * @param obj
 */
function change_total_money(obj)
{
    var before = parseFloat($('#before').html());
    if($(obj).val()){
        //$(obj).val($(obj).val().replace(/[^\d.]/g,''));
        var a = parseFloat($(obj).val());
        if(a<0){
            a = 0;
        }
        $(obj).val(a.toFixed(3))
    }else{
        var a = 0;
    }
    $('#after').html(parseFloat(before + a).toFixed(3));
}

function check_number(obj)
{
    var a = parseFloat($(obj).val()).toFixed(2);
    $(obj).val(a)
}


function verify_allowed(str)
{
    $('#'+str).find('.btn-1').attr('disabled', true);
    $('#'+str).find('.btn-1').addClass('btn-1-1');
    $('#verify').val(2);
    $('#'+str).submit();
    $('#'+str).submit.disabled = true;
}


function verify_forbidden(str)
{
    $('#'+str).find('.btn-1').attr('disabled', true);
    $('#'+str).find('.btn-1').addClass('btn-1-1');
    $('#verify').val(1);
    $('#'+str).submit();
    $('#'+str).submit.disabled = true;
}


function submit_allowed(str)
{
    $('#'+str).find('.btn-1').attr('disabled', true);
    $('#'+str).find('.btn-1').addClass('btn-1-1');
    $('#verify').val(1);
    $('#'+str).submit();
    $('#'+str).submit.disabled = true;
}

function selectAll(ob)
{
    if($(ob).attr('checked'))
    {
        $("td input").attr("checked",true);
    }else{
        $("td input").attr("checked",false);
    }
}


function withdraw_submit_all(obj)
{
    var bool = false;
    $("td input").each(function(){
        if($(this).attr('checked')==true)
        {
            bool = true;
            return false;
        }
    });
    if(!bool)
    {
        alert('请先选择');
        return false;
    }
    var url = $(obj).attr('href');
    $("#mutiForm").attr('action', url);
    $("#mutiForm").submit();
}

function withdraw_select_verify(obj)
{
    var url = $(obj).attr('href');
    $("#searchFormId").attr('action', url);
    $("#searchFormId").submit();

    var sel = $('#searchPath').val();
    $('#searchFormId').attr('action', sel);
}
function getendmaxtime(){
    var date_type = $('#setMaxEndDate').val();
    if( date_type == 'end'){

        if($('#beginTime').val()){
            var begin = new Date($('#beginTime').val());
            begin.setDate(begin.getDate() + 30);

            return begin.getFullYear()+'-'+ (begin.getMonth()+1)+'-'+ begin.getDate();
        }else{
            return '';
        }
    }else if( date_type == 'get'){
        if($('#beginTime').val()){
            var time = new Date().valueOf();
            var begin = new Date($('#beginTime').val());
            begin.setDate(begin.getDate() + 30);
            begin.valueOf();
            var m = new Date(Math.min(time,begin));
        }else{
            var m = new Date();;
        }

        return m.getFullYear()+'-'+ (m.getMonth()+1)+'-'+ m.getDate();
    }
    return '';
}

function getbeginmaxtime()
{
    var date_type = $('#setMaxEndDate').val();
    if( date_type == 'end'){//实际到期时间  只判断小于end

        if($('#endTime').val()){

            var end = new Date($('#endTime').val());

            return end.getFullYear()+'-'+ (end.getMonth()+1)+'-'+ end.getDate();
        }else{
            return '';
        }
    }else if( date_type == 'get'){
        if($('#endTime').val()){
            var time = new Date().valueOf();
            var end = new Date($('#endTime').val());
            end.valueOf();
            var m = new Date(Math.min(time,end));
        }else{
            var m = new Date();;
        }

        return m.getFullYear()+'-'+ (m.getMonth()+1)+'-'+ m.getDate();
    }
    return '';
}

function setMaxDate()
{
    $('#endTime').val('');
    $('#beginTime').val('');
}

function getmaxtime(){
    var time = new Date().valueOf();
    if( $('#year').val() > 0){
        var year = new Date($('#year').val(),11,31,23,59,59);
        year.valueOf();
        time = Math.min(time,year);
    }
    if($('#beginTime').val()){
        var begin = $('#beginTime').val();
        begin = new Date(begin);
        begin.setDate(begin.getDate() + 30);
        begin.valueOf();
        var m = new Date(Math.min(time,begin));
    }else{
        var m = new Date();
        if( $('#year').val() > 0){
            m.valueOf();
            m = new Date(Math.min(m,year));
        }
    }
    return m.getFullYear()+'-'+ (m.getMonth()+1)+'-'+ m.getDate();
}

function getmintime()
{
    var m = new Date();
    var year = new Date($('#year').val());
    if($('#endTime').val()){
        var time = new Date().valueOf();
        var begin = new Date($('#endTime').val());
        begin.setDate(begin.getDate() - 30);
        begin.valueOf();
        begin = Math.max(year,begin);
        m = new Date(Math.min(time,begin));
    }else if( $('#year').val() > 0){
        m.valueOf();
        m = new Date(Math.min(m,year));
    }else{
        m = new Date(m.getFullYear());
    }
    return m.getFullYear()+'-'+ (m.getMonth()+1)+'-'+ m.getDate();
}
function getmintimesub()
{
    if($('#endTime').val()){
        var time = new Date().valueOf();
        var begin = new Date($('#endTime').val());
        begin.setDate(begin.getDate());
        begin.valueOf();
        var m = new Date(Math.min(time,begin));
    }else{
        var m = new Date();
        var year = new Date($('#year').val(),11,31,23,59,59);
        if( $('#year').val() > 0){
            m.valueOf();
            m = new Date(Math.min(m,year));
        }
    }
    return m.getFullYear()+'-'+ (m.getMonth()+1)+'-'+ m.getDate();
}


function showVerifyDiv(id)
{
    showDiv();

    $("#rec_id").val(id);
}
function verify_recharge_status(ss)
{
    $("#rec_status").val(ss);
    $('#verifyFormId').submit();
}
function showDiv()
{
    var Idiv     = document.getElementById("Idiv");
    Idiv.style.display = "block";
//以下部分要将弹出层居中显示
    Idiv.style.left=(document.documentElement.clientWidth-Idiv.clientWidth)/2+document.documentElement.scrollLeft+"px";
    Idiv.style.top =(document.documentElement.clientHeight-Idiv.clientHeight)/2+document.documentElement.scrollTop-50+"px";


//以下部分使整个页面至灰不可点击
    var procbg = document.createElement("div"); //首先创建一个div
    procbg.setAttribute("id","mybg"); //定义该div的id
    procbg.style.background = "#000000";
    procbg.style.width = "100%";
    procbg.style.height = "100%";
    procbg.style.position = "fixed";
    procbg.style.top = "0";
    procbg.style.left = "0";
    procbg.style.zIndex = "500";
    procbg.style.opacity = "0.6";
    procbg.style.filter = "Alpha(opacity=70)";
//背景层加入页面
    document.body.appendChild(procbg);
    document.body.style.overflow = "hidden"; //取消滚动条

}
function closeDiv() //关闭弹出层
{
    var Idiv=document.getElementById("Idiv");
    Idiv.style.display="none";
    document.body.style.overflow = "auto"; //恢复页面滚动条
    var body = document.getElementsByTagName("body");
    var mybg = document.getElementById("mybg");
    body[0].removeChild(mybg);
}

