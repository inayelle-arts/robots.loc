$(function () {
    var generateButton = $("#generate-xlsx");
    generateButton.on("click", function () {
        var dataMap = [], exists = generateButton.attr("data-exists"), status = generateButton.attr("data-status"), filesize = generateButton.attr("data-filesize"), maps = generateButton.attr("data-sitemaps"), hosts = generateButton.attr("data-hosts");
        dataMap.push(exists, status, filesize, maps, hosts);
        console.log(dataMap);
        var data = JSON.stringify(dataMap);
        $.ajax({
            url: "/toxlsx.php",
            method: "post",
            data: { data: data },
            success: function (response) {
                window.location = response;
            }
        });
    });
});
