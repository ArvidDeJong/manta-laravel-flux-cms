document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener('toastr:success', event => {
        console.log(event.detail[0].message);
        toastr.success(event.detail[0].message, event.detail[0].title, {
            "timeOut": "3000",
            "progressBar": true
        });
    });
    document.addEventListener('toastr:warning', event => {
        console.log(event.detail[0].message);
        toastr.warning(event.detail[0].message, event.detail[0].title, {
            "timeOut": "3000",
            "progressBar": true
        });
    });
    document.addEventListener('toastr:error', event => {
        console.log(event.detail[0].message);
        toastr.error(event.detail[0].message, event.detail[0].title, {
            "timeOut": "3000",
            "progressBar": true
        });
    });
    document.addEventListener('toastr:info', event => {
        console.log(event.detail[0].message);
        toastr.info(event.detail[0].message, event.detail[0].title, {
            "timeOut": "3000",
            "progressBar": true
        });
    });


});

Fancybox.bind("[data-fancybox]", {
    // Your custom options
});
