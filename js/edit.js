// eslint-disable-next-line no-undef
$(function () {
    "use strict";

    // استهداف عناصر المنتجات
    // eslint-disable-next-line no-undef
    let imgs = $(".item_for_data .productinfo img");
    // eslint-disable-next-line no-undef
    let prices = $(".item_for_data .productinfo h2");
    // eslint-disable-next-line no-undef
    let nameImge = $(".item_for_data .productinfo p");
    // eslint-disable-next-line no-undef
    let prices2 = $(".item_for_data .product-overlay h2");
    // eslint-disable-next-line no-undef
    let nameImge2 = $(".item_for_data .product-overlay p");

    // استهداف عناصر العناصر الموصى بها
    // eslint-disable-next-line no-undef
    let re_imgs = $(".recomend_data img");
    // eslint-disable-next-line no-undef
    let re_prices = $(".recomend_data h2.page");
    // eslint-disable-next-line no-undef
    let re_nameImge = $(".recomend_data p");

    // جلب البيانات مرة واحدة
    // eslint-disable-next-line no-undef
    $.get("https://elhanem.com/item.php", function (dataItem) {
        if (!dataItem || !Array.isArray(dataItem)) {
            console.error("Invalid data received from API");
            return;
        }

        // تعبئة بيانات المنتجات العادية
        // eslint-disable-next-line no-undef
        $.each(dataItem, function (index, item) {
            if (index < imgs.length) {
                // eslint-disable-next-line no-undef
                $(imgs[index]).attr("src", `admin/uploded/img-uploded/${item.single_image}`);
                // eslint-disable-next-line no-undef
                $(prices[index]).text(item.price + " EGP");
                // eslint-disable-next-line no-undef
                $(nameImge[index]).text(item.name_item);
                // eslint-disable-next-line no-undef
                $(prices2[index]).text(item.price + " EGP");
                // eslint-disable-next-line no-undef
                $(nameImge2[index]).text(item.name_item);
            }
        });

        // تعبئة بيانات المنتجات الموصى بها (آخر العناصر)
        let reverseIndex = dataItem.length - 1;
        for (let i = 0; i < Math.min(re_imgs.length, dataItem.length); i++) {
            // eslint-disable-next-line no-undef
            $(re_imgs[i]).attr("src", `admin/uploded/img-uploded/${dataItem[reverseIndex].single_image}`);
            // eslint-disable-next-line no-undef
            $(re_prices[i]).text(dataItem[reverseIndex].price + " EGP");
            // eslint-disable-next-line no-undef
            $(re_nameImge[i]).text(dataItem[reverseIndex].name_item);
            reverseIndex--;
        }
    }, "json");
});

 
