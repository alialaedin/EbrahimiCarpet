function confirmDelete(formId) {
    swal({
        title: "آیا مطمئن هستید؟",
        text: "بعد از حذف این آیتم دیگر قابل بازیابی نخواهد بود!",
        icon: "warning",
        buttons: ["انصراف", "حذف کن"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            document.getElementById(formId).submit();
            swal("آیتم با موفقیت حذف شد.", {
                icon: "success",
            });
        }
    });
}

function convertToPersianNumbers(text) {
    return text.replace(/[0-9]/g, function (digit) {
        return String.fromCharCode(digit.charCodeAt(0) + 1728);
    });
}

$(document).ready(function () {
    // $("body")
    //     .find("*")
    //     .each(function () {
    //         if ($(this).is(":not(:has(*))")) {
    //             var text = $(this).text();
    //             var persianText = convertToPersianNumbers(text);
    //             $(this).text(persianText);
    //         }
    //     });

    $("input.comma").on("keyup", function (event) {
        if (event.which >= 37 && event.which <= 40) return;
        $(this).val(function (index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    // $(".price").each(function () {
    //   var number = parseInt($(this).text(), 10);
    //   if (!isNaN(number)) {
    //     $(this).text(number.toLocaleString("fa-IR"));
    //   }
    // });

    // $(".number").each(function () {
    //   var number = $(this).text();
    //   var persianNumber = number.replace(/[0-9]/g, function (digit) {
    //     return String.fromCharCode(digit.charCodeAt(0) + 1728);
    //   });
    //   $(this).text(persianNumber);
    // });

    $(".date").each(function () {
        var date = new Date($(this).text());
        if (!isNaN(date)) {
            var options = { year: "numeric", month: "numeric", day: "numeric" };
            var formattedDate = date.toLocaleDateString("fa-IR", options);
            $(this).text(formattedDate);
        }
    });
});
