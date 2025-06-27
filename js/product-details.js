document.addEventListener("DOMContentLoaded", () => {
    const queryParams = new URLSearchParams(window.location.search);

    const productName = queryParams.get("name") || "Unknown Product";
    const productPrice = queryParams.get("price") || "0 EGP";
    const productImage = queryParams.get("image") || "default.jpg";
    const productBrand = queryParams.get("brand") || "No Brand";


    const productSizes = queryParams.get("sizes") ? JSON.parse(decodeURIComponent(queryParams.get("sizes"))) : [];
    const additionalImages = queryParams.get("additionalImages") ? JSON.parse(decodeURIComponent(queryParams.get("additionalImages"))) : [];

    // تحديث العناصر في الصفحة
    const productNameElement = document.getElementById("product-name");
    if (productNameElement) {
        productNameElement.textContent = productName;
    }

    const productPriceElement = document.getElementById("product-price");
    if (productPriceElement) {
        productPriceElement.textContent = productPrice;
    }

    const productBrandElement = document.getElementById("product-brand");
    if (productBrandElement) {
        productBrandElement.textContent = productBrand;
    }

    const productImageElement = document.getElementById("product-image");
    if (productImageElement) productImageElement.src = productImage;

    // إضافة الصور المصغرة
    const additionalImagesContainer = document.getElementById("additional-images");
    if (additionalImagesContainer) {
        additionalImagesContainer.innerHTML = '';
        additionalImages.forEach(image => {
            const imgElement = document.createElement("img");
            imgElement.src = image;
            imgElement.alt = "Additional Image";
            imgElement.style.margin = "5px";
            imgElement.style.cursor = "pointer";
            imgElement.addEventListener("click", () => {
                productImageElement.src = image;
            });
            additionalImagesContainer.appendChild(imgElement);
        });
    }

    // التعامل مع زر "إضافة إلى السلة"
    const addToCartButton = document.getElementById("add-to-cart");
    if (addToCartButton) {
        addToCartButton.addEventListener("click", () => {
            const selectedSizeElement = document.getElementById("product-size");
            const selectedSize = selectedSizeElement ? selectedSizeElement.value : "Default Size";
            const selectedImage = productImageElement.src;

            addToCart(productName, productPrice, selectedImage, selectedSize);
        });
    }
});

// وظيفة لإضافة المنتج إلى السلة
function addToCart(name, price, image, size) {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];

    // التحقق مما إذا كان المنتج مضافًا مسبقًا
    const existingProduct = cart.find(item => item.name === name && item.size === size);

    if (existingProduct) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "هذا المنتج موجود بالفعل في السلة!",
            footer: '<a href="#">Why do I have this issue?</a>'
          });        return;
    }

    cart.push({ name, price, image, size });

    // حفظ السلة في localStorage
    localStorage.setItem("cart", JSON.stringify(cart));

    // eslint-disable-next-line no-undef
    Swal.fire({
        title: "Good job!",
        text: "تمت إضافة المنتج إلى السلة!",
        icon: "success"
    });
}


const sizeSelect = document.getElementById("product-size");
const sizeSelectElement = document.getElementById("product-sizes-details");
if (sizeSelect) {
    sizeSelect.addEventListener("change", () => {
        const selectedSize = sizeSelect.value || "Default Size";
        sizeSelectElement.innerHTML = selectedSize;
    });
}