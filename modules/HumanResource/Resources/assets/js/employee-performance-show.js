$("#btnPrint").on("click", function () {
    var printContents = document.getElementById("printArea").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload(true);
});
