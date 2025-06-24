$(function () {
    "use strict";

    // استهداف عناصر المنتجات
    let imgs = $(".item_for_data .productinfo img");
    let prices = $(".item_for_data .productinfo h2");
    let nameImge = $(".item_for_data .productinfo p");
    let prices2 = $(".item_for_data .product-overlay h2");
    let nameImge2 = $(".item_for_data .product-overlay p");

    // استهداف عناصر العناصر الموصى بها
    let re_imgs = $(".recomend_data img");
    let re_prices = $(".recomend_data h2");
    let re_nameImge = $(".recomend_data p");

    // جلب البيانات مرة واحدة
    $.get("https://elhanem.com/item.php", function (dataItem) {
        if (!dataItem || !Array.isArray(dataItem)) {
            console.error("Invalid data received from API");
            return;
        }

        // تعبئة بيانات المنتجات العادية
        $.each(dataItem, function (index, item) {
            if (index < imgs.length) {
                $(imgs[index]).attr("src", `admin/uploded/img-uploded/${item.single_image}`);
                $(prices[index]).text(item.price);
                $(nameImge[index]).text(item.name_item);
                $(prices2[index]).text(item.price);
                $(nameImge2[index]).text(item.name_item);
            }
        });

        // تعبئة بيانات المنتجات الموصى بها (آخر العناصر)
        let reverseIndex = dataItem.length - 1;
        for (let i = 0; i < Math.min(re_imgs.length, dataItem.length); i++) {
            $(re_imgs[i]).attr("src", `admin/uploded/img-uploded/${dataItem[reverseIndex].single_image}`);
            $(re_prices[i]).text(dataItem[reverseIndex].price);
            $(re_nameImge[i]).text(dataItem[reverseIndex].name_item);
            reverseIndex--;
        }
    }, "json");
});

 
