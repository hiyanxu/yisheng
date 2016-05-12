$(function () {
    $("#ADiva").click(function () {
        $("#Diva").animate({
            left: "200px"
        },
                600,
                function () {
                    $("#Diva").css("zIndex", 0),
                    $("#Divb").css("zIndex", 0),
                    $("#Divc").css("zIndex", 0),
                    $("this").css("zIndex", 1),
                     $("#Diva").css("zIndex", 2)
                }).animate({
                    left: "0px"
                },
                600
                );
    });
});
$(function () {
    $("#ADivb").click(function () {
        $("#Divb").animate({
            left: "200px"
        },
                600,
                function () {
                    $("#Diva").css("zIndex", 0),
                    $("#Divb").css("zIndex", 0),
                    $("#Divc").css("zIndex", 0),
                    $("this").css("zIndex", 1)
                    $("#Divb").css("zIndex", 2)
                }).animate({
                    left: "0px"
                },
                600
                );
    });
});
$(function () {
    $("#ADivc").click(function () {
        $("#Divc").animate({
            left: "200px"
        },
                600,
                function () {
                    $("#Diva").css("zIndex", 0),
                    $("#Divb").css("zIndex", 0),
                    $("#Divc").css("zIndex", 0),
                    $("this").css("zIndex", 1)
                    $("#Divc").css("zIndex", 2)
                }).animate({
                    left: "0px"
                },
                600
                );
    });
});
