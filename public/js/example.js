$(function() {
var result;
var oya;
var reset_flg=false;
var start
var end;
var mode=1;

$("input[name=mode]").on("click",function() {
  mode = $(this).val();
});

$('.list').selectable({
  // 初期化
  start: function() {
    reset();
    reset_flg=false;
    $('#result').text('選択中です。');
  },
  // 選択された要素をセット
  selected: function(e, ui) {
    //result += $(ui.selected).text();
    //console.log( $(ui.selected).attr("id"));


    var time = $(ui.selected).children("#time").val();
    if (time == "no") {
      reset();
      return;
    }

    if (start == null || parseInt(start) > parseInt(time)) {
      start = time;
      result += time + "<br />";
    }
    if (end == null || parseInt(end) < parseInt(time)) {
      end = time;
      result += time + "<br />";
    }

    if (oya == null) {
      oya = $(ui.selected).children("#id").val()
    }
  },
  // 表示
  stop: function() {
    if (!reset_flg) {
      //$('#result').text(result + 'が選択されました。');
      //$('#result').html("【ID:" + oya + "】<br />" + result + 'が選択されました。');
      $("#result").html("ID" + oya + "の" + start + "～" + end);

      if (mode == 1) {
        alert("【次のURL】/?id=" + oya + "&start=" + start + "&end=" + end);
      }
      else {
        $("#dialog").dialog("open");
      }
    }
  }
});

$("#commit").on("click", function(){
  if (window.confirm("下記の時刻で予約を取りますか？")) {
    alert("予約処理");
  }
});
$("#close").on("click", function(){
  $("#dialog").dialog("close");
});

function reset() {
  // 選択中を全削除
  $(".list *").each(function(){
    $(this).removeClass("ui-selected");
  });
  oya = null;

  result = '';

  reset_flg=true;
  start = null;
  end = null;
  return;
}

$("#dialog").dialog({
  autoOpen:false,
  modal:true,
  open:function(){
    $("#oya").html(oya);
    $("#start").html(start);
    $("#end").html(end);
  }
});

});
