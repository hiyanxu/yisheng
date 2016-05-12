$(function () {
    $("#CheckNews0").click(function () {
        var src = $("#CheckNews0 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNoticeContent0").css("display", "none");
            $("#CheckNotice0 img").attr("src", "../Image/icon4.png");
            $("#CheckNewsContent0").slideDown();
            $("#CheckNews0 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNewsContent0").slideUp();
            $("#CheckNews0 img").attr("src", "../Image/icon4.png")
        }

    });
    $("#CheckNotice0").click(function () {
        var src = $("#CheckNotice0 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNewsContent0").css("display", "none");
            $("#CheckNews0 img").attr("src", "../Image/icon4.png");
            $("#CheckNoticeContent0").slideDown();
            $("#CheckNotice0 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNoticeContent0").slideUp();
            $("#CheckNotice0 img").attr("src", "../Image/icon4.png")
        }

    });
    $("#CheckNews1").click(function () {
        var src = $("#CheckNews1 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNoticeContent1").css("display", "none");
            $("#CheckNotice1 img").attr("src", "../Image/icon4.png");
            $("#CheckNewsContent1").slideDown();
            $("#CheckNews1 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNewsContent1").slideUp();
            $("#CheckNews1 img").attr("src", "../Image/icon4.png")
        }

    });
    $("#CheckNotice1").click(function () {
        var src = $("#CheckNotice1 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNewsContent1").css("display", "none");
            $("#CheckNews1 img").attr("src", "../Image/icon4.png");
            $("#CheckNoticeContent1").slideDown();
            $("#CheckNotice1 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNoticeContent1").slideUp();
            $("#CheckNotice1 img").attr("src", "../Image/icon4.png")
        }

    });
    $("#CheckNews2").click(function () {
        var src = $("#CheckNews2 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNoticeContent2").css("display", "none");
            $("#CheckNotice2 img").attr("src", "../Image/icon4.png");
            $("#CheckNewsContent2").slideDown();
            $("#CheckNews2 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNewsContent2").slideUp();
            $("#CheckNews2 img").attr("src", "../Image/icon4.png")
        }

    });
    $("#CheckNotice2").click(function () {
        var src = $("#CheckNotice2 img").attr("src");
        if (src == "../Image/icon4.png") {
            $("#CheckNewsContent2").css("display", "none");
            $("#CheckNews2 img").attr("src", "../Image/icon4.png");
            $("#CheckNoticeContent2").slideDown();
            $("#CheckNotice2 img").attr("src", "../Image/icon1.png");
        }
        if (src == "../Image/icon1.png") {
            $("#CheckNoticeContent2").slideUp();
            $("#CheckNotice2 img").attr("src", "../Image/icon4.png")
        }

    });
});