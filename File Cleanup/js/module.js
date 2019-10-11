//The URL for all the ajax functions for the module, populated by PHP
var ajaxUrl = null;

jQuery(window).load(function () {
    $(".actionBtn").hide();
    chkFile();
});

function hideNA() {
    $('.na').parent().toggle();
}

function chkFile(){
        $.ajax({
            method: "POST",
            url: ajaxUrl,
            data: { chkFile: $(".chkFile").first().prev().html() },
        }).done(function( msg ) {
            $(".chkFile").first().html(msg).removeClass("chkFile").addClass(msg=="NA"?"na":"done");
            if($(".chkFile").length){
                setTimeout(chkFile(),50);
            }else{
                $(".loading").html("Done.").removeClass("lds-dual-ring");
                $(".actionBtn").show();
            }
        });
    }

function hideDone(){
    $(".done").parent().toggle();
}
function cpNA(){
    var tmp="";
    $(".na").each((i,e)=>{
        tmp+=$(e).prev().html()+" ";
    });
    copyToClipboard(tmp);
}
function copyToClipboard(tmptxt) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(tmptxt).select();
  document.execCommand("copy");
  $temp.remove();
}
function backToList(){
    window.location.href = window.location.href.substring(0,window.location.href.indexOf("&path"));
}
