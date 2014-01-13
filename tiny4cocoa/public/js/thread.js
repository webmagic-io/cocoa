function replyto(floor,name){
  var val = $("#wmd-input").val();
  if(val.length>0)
    $("#wmd-input").val(val+"\r\n\r\n");
  $("#wmd-input").val($("#wmd-input").val()+floor+"楼 @"+name+" ");
  $("#wmd-input").focus();
  moveCaretToEnd(document.getElementById("wmd-input"));
}

function weiboShare(owner) {
  
  var href = window.location.href.split('#')[0];
  var weibotext;
  if(owner==1)
    weibotext = "我在 #tiny4cocoa# 发布了一个帖子《";
  else
    weibotext = "我在 #tiny4cocoa# 发现了一个帖子《";   
  weibotext += threadtitle + "》 " + href;
  $("#weibocontent").val(weibotext);
  $('#weiboshareDialog').modal();
}

function closeWeiboShare() {
  
  $('#weiboshareDialog').modal("hide");
}

function sendweibo() {
  
  var weibocontent = $("#weibocontent").val();
  $.post( "/user/sendweibo/",
          {
            weibocontent:weibocontent
          },
          function( data ) {
            if(data=="notoken")
              alert("请先到右上角用户菜单绑定新浪微博");
          });
  $('#weiboshareDialog').modal("hide");  
}