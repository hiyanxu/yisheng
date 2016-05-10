function initMenu() {

    //        var hi=$("#HiddenFieldClass").val();
    $('#menu ul').hide();
    var hClass = $("#HiddenFieldClass").val();
    if (hClass == 0) {
        $("#ul0").show();
    }
    if (hClass == 1) {
        $("#ul1").show();
    }
    if (hClass == 2) {
        $("#ul2").show();
    }
    if (hClass == 3) {
        $("#ul3").show();
    }
    if (hClass == 4) {
        $("#ul4").show();
    }
    if (hClass == 5) {
        $("#ul5").show();
    }
    if (hClass == 6) {
        $("#ul6").show();
    }
    $('#menu li a').mousemove(
                function () {
                    var checkElement = $(this).next();
                    if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        return false;
                    }
                    if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        $('#menu ul:visible').slideUp('normal');
                        checkElement.slideDown('normal');
                        return false;
                    }
                }
             );
}
$(document).ready(function () { initMenu(); });