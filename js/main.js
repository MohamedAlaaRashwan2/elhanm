// eslint-disable-next-line no-undef
$('#sl2').slider();

// eslint-disable-next-line no-unused-vars
var RGBChange = function () {
    // eslint-disable-next-line no-undef
    $('#RGB').css('background', 'rgb(' + r.getValue() + ',' + g.getValue() + ',' + b.getValue() + ')');
};

/* scroll to top */
// eslint-disable-next-line no-undef
$(document).ready(function () {
    // eslint-disable-next-line no-undef
    $(function () {
        // eslint-disable-next-line no-undef
        $.scrollUp({
            scrollName: 'scrollUp',
            scrollDistance: 300,
            scrollFrom: 'top',
            scrollSpeed: 300,
            easingType: 'linear',
            animation: 'fade',
            animationSpeed: 200,
            scrollTrigger: false,
            scrollText: '<i class="fa fa-angle-up"></i>',
            scrollTitle: false,
            scrollImg: false,
            activeOverlay: false,
            zIndex: 2147483647
        });
    });
});

/* تحميل المنتجات من السيرفر */
let products = [];
var pro; // Declare pro as a global variable

// eslint-disable-next-line no-undef
$.get("https://elhanem.com/item.php", function (dataItem) {
    for (let a = 0; a < dataItem.length; a++) {
        let AddPashToImge = [];

        // التحقق من وجود صور متعددة
        if (Array.isArray(dataItem[a].images) && dataItem[a].images.length > 0) {
            for (let i = 0; i < dataItem[a].images.length; i++) {
                AddPashToImge.push(`https://elhanem.com/admin/uploded/group-img/${dataItem[a].images[i]}`);
            }
        }

        // التحقق من وجود الصورة الرئيسية
        let singleImage = dataItem[a].single_image
            ? `https://elhanem.com/admin/uploded/img-uploded/${dataItem[a].single_image}`
            : "https://elhanem.com/default-image.jpg";

        let colors = dataItem[a].color.split(",");
        pro = {
            image: singleImage,
            imagefordetl: singleImage,
            price: dataItem[a].price,
            name: dataItem[a].name_item,
            brand: "ELHANIM",
            sizes: ["1x", "2x", "3x"],
            additionalImage: AddPashToImge,
            color: colors[0],
            availableColors: colors,
            type: dataItem[a].type,
        };
        products.push(pro);
    }
    loadProducts(1); // تحميل المنتجات بعد جلب البيانات
}, 'json');
let filteredProducts = products; // هنعرض منه المنتجات

const buttonsPage = document.querySelectorAll('.page-1');
buttonsPage.forEach(button => {
    button.addEventListener('click', () => {
        buttonsPage.forEach(e => {
            e.classList.remove('active');
        });
        button.classList.add('active');
    });
});

const buttons = document.querySelectorAll('.filter-buttons button');
buttons.forEach(button => {
    button.addEventListener('click', () => {
        buttons.forEach(e => {
            e.classList.remove('active');
        });
        button.classList.add('active');
    });
});
/* تحميل المنتجات في الصفحة */
// eslint-disable-next-line no-unused-vars
function filterProducts(type) {
    if (type === 'all') {
        filteredProducts = products;
    } else {
        filteredProducts = products.filter(product => product.type === type);
    }
    loadProducts(1); // عرض النتيج

}
function loadProducts(page) {
    const productsPerPage = 9;
    const startIndex = (page - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const productsToDisplay = filteredProducts.slice(startIndex, endIndex);
    const productsList = document.getElementById("products-list");
    productsList.innerHTML = ""; // مسح المحتوى القديم
    productsToDisplay.forEach((product) => {
        // eslint-disable-next-line no-unused-vars
        let colorOptions = "";
        product.availableColors.forEach(color => {
            colorOptions += `
              <option value="${color}" ${product.color === color ? "selected" : ""}>
                ${color}
              </option>
            `;
        });
        const productDiv = document.createElement("div");
        productDiv.classList.add("col-sm-4");
        productDiv.classList.add("col-smm");
        productDiv.innerHTML = `
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <a href="product-details.html?name=${encodeURIComponent(product.name)}&price=${encodeURIComponent(product.price)}&image=${encodeURIComponent(product.imagefordetl)}&brand=${encodeURIComponent(product.brand)}&sizes=${encodeURIComponent(JSON.stringify(product.sizes))}&additionalImages=${encodeURIComponent(JSON.stringify(product.additionalImage))}">
                            <img src="${product.image}" alt="${product.name}" />
                        </a>
                        <h2>${product.price} EGP</h2>
                        <p>${product.name}</p>
                        <a href="#" class="btn btn-default add-to-cart" onclick="addToCart('${product.name}', '${product.price}', '${product.imagefordetl}')">
                            <i class="fa fa-shopping-cart"></i> Add to cart
                        </a>
                    </div>
                </div>
            </div>
        `;
        productsList.appendChild(productDiv);
    });

    // eslint-disable-next-line no-undef
    currentPage = page;
}
/* إضافة المنتج إلى السلة */
// eslint-disable-next-line no-unused-vars
function addToCart(name, price, image, size) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    // إذا لم يتم تحديد الحجم، يتم تحديد الحجم الافتراضي
    size = size || "M";

    // البحث عن المنتج في السلة لمنع التكرار
    let existingProduct = cart.find(item => item.name === name && item.size === size);

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.push({ name, price, image, size, quantity: 1 });
    }
    // حفظ السلة في localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // eslint-disable-next-line no-undef
    Swal.fire({
        title: "Good job!",
        text: "تمت إضافة المنتج إلى السلة!",
        icon: "success"
    });
    // eslint-disable-next-line no-undef
    loadCart();
}

/* تحميل المنتجات عند فتح الصفحة */
document.addEventListener('DOMContentLoaded', function () {
    if (products.length > 0) {
        loadProducts(1);
    }
});
