var URL_REGEX = /^((http|https):\/\/)?[a-z0-9.\-_]{2,}\.[a-z0-9]{2,}$/;
function validateURL(url) {
    return URL_REGEX.test(url);
}
$(function () {
    var urlInput = $("#url"), errorMessage = $("#regex-error"), submit = $("#submit");
    urlInput.on("changed paste keyup", function () {
        if (validateURL(urlInput.val()))
            errorMessage.hide();
    });
    submit.on("click", function () {
        if (!validateURL(urlInput.val())) {
            errorMessage.show();
            return false;
        }
    });
});
