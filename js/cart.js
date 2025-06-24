

document.addEventListener("DOMContentLoaded", () => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartTableBody = document.querySelector("#cart-table tbody");
    const clearCartButton = document.getElementById("clear-cart");
    const subTotalElement = document.querySelector("#cart-subtotal");
    const shippingCostElement = document.querySelector("#shipping-cost");
    const discountAmountElement = document.querySelector("#discount-amount");
    const totalElement = document.querySelector("#cart-total");
    const couponInput = document.getElementById("coupon-code");
    const applyCouponButton = document.getElementById("apply-coupon");
    const continueBtn = document.querySelector(".btn.btn-primary.send");
    


    const shippingCost = "- -";
    async function fetchCouponDiscount(subTotal) {
        const couponCode = couponInput.value.trim();
        if (!couponCode) return 0;

        try {
            const response = await fetch(`https://elhanem.com/new_work/get_coupon.php?code=${couponCode}`);
            const data = await response.json();

            if (data.valid) {
                Swal.fire({
                    title: "Good job!",
                    text: "🎉 كود الخصم صالح! سيتم تطبيقه.",
                    icon: "success"
                });
                return subTotal * (data.discount / 100);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "⚠️ كود الخصم غير صحيح.",
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                return 0;
            }
        } catch (error) {
            console.error("Fetch Error:", error);
            return 0;
        }
    }

    async function calculateTotals() {
        const subTotal = cart.reduce((sum, product) => {
            const price = parseFloat(product.price.replace(/[^\d.]/g, "")) || 0;
            return sum + price * (product.quantity || 1);
        }, 0);

        const discount = await fetchCouponDiscount(subTotal);
        const total = subTotal - discount;

        subTotalElement.textContent = `EGP ${subTotal.toFixed(2)}`;
        shippingCostElement.textContent = ` EGP ${shippingCost}`;
        discountAmountElement.textContent = `EGP - ${discount.toFixed(2)}`;
        totalElement.textContent = `EGP ${total.toFixed(2)}`;
    }


    function updateCartDisplay() {
        cartTableBody.innerHTML = "";

        if (cart.length === 0) {
            cartTableBody.innerHTML = `<tr><td colspan="6">سلة التسوق الخاصة بك فارغة!</td></tr>`;
            return;
        }
        cart.forEach((product, index) => {
            product.quantity = product.quantity || 1;
            product.size = product.size || "1x";
            product.color = product.color || "red";            
            const price = parseFloat(product.price.replace(/[^\d.]/g, "")) || 0;

            const row = document.createElement("tr");

            row.innerHTML = `
     <td><img src="${product.image}" alt="${product.name}" width="100"></td>
     <td>${product.name}</td>
     <td id="price-${index}">${(price * product.quantity).toFixed(2)} EGP</td>
     <td>
        <button onclick="updateQuantity(${index}, -1)">-</button>
        <span id="quantity-${index}">${product.quantity}</span>
        <button onclick="updateQuantity(${index}, 1)">+</button>
     </td>
     <td>
        <select onchange="updateSize(${index}, this.value)">
            <option value="1x" ${product.size === "1x" ? "selected" : ""}>1x</option>
            <option value="2x" ${product.size === "2x" ? "selected" : ""}>2x</option>
            <option value="3x" ${product.size === "3x" ? "selected" : ""}>3x</option>
         </select>
     </td>
     <td><button onclick="removeFromCart(${index})">حذف</button></td>
     `;
 cartTableBody.appendChild(row); });

        calculateTotals();
    }

    function saveCart() {
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartDisplay();
    }

    window.updateQuantity = (index, change) => {
        if (cart[index].quantity + change > 0) {
            cart[index].quantity += change;
            saveCart();
        }
    };

    window.updateSize = (index, newSize) => {
        cart[index].size = newSize;
        saveCart();
    };

    window.removeFromCart = (index) => {
        cart.splice(index, 1);
        saveCart();
    };

    if (clearCartButton) {
        clearCartButton.addEventListener("click", () => {
            localStorage.removeItem("cart");
            window.location.reload();
        });
    }
    const btn = document.getElementById("some-id");
    if (btn) {
      btn.addEventListener("click", () => {
        calculateTotals();
      });
    }
 
    // زر إرسال عبر الإيميل
    if (continueBtn) {
        continueBtn.addEventListener("click", async (e) => {
            e.preventDefault();
            if (cart.length === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "⚠️ العربه فارغه",
                });
                return;
            } else {
                Swal.fire({
                    title: "Good job!",
                    text: "تم ارسال الطلب بنجاح",
                    icon: "success"
                });
            }

            const name = document.querySelector("#name").value.trim();
            const address = document.querySelector("#address").value.trim();
            const phoneNumber = document.querySelector("#phone").value.trim();
            const email = document.querySelector("#email").value.trim();
            const couponCode = couponInput.value.trim();

            if (!name || !address || !phoneNumber || !email) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "بالرجاء تعبئة جميع الحقول!",
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                return;
            }


            const subTotal = cart.reduce((sum, product) => {
                const basePrice = parseFloat(product.price.replace(/[^\d.]/g, ""));
                return sum + basePrice * product.quantity;
            }, 0);

            let discount = 0;
            if (couponCode === "UTOPIA10") discount = subTotal * 0.1;
            else if (couponCode === "UTOPIA20") discount = subTotal * 0.2;

            const total = subTotal - discount;

            let message = "\u200F🛍️ *تفاصيل الطلب:*\n";
            cart.forEach((product, index) => {
                const imageUrl = product.image.startsWith("http")
                    ? product.image
                    : `https://elhanem.com/admin/uploded/img-uploded/${product.image}`;
                message += `\n\u200F${index + 1}- *${product.name}*`;
                message += `\n\u200F - الكمية: ${product.quantity}`;
                message += `\n\u200F - السعر: ${product.price}`;
                message += `\n\u200F - المقاس: ${product.size}`;
                message += `\n\u200F - اللون: ${product.color}`;
                message += `\n\u200F - صورة المنتج:\n\u200F${imageUrl}\n`;
            });

            message += `\n\u200F *معلومات العميل:*\n`;
            message += `\u200F الاسم: ${name}`;
            message += `\n\u200F العنوان: ${address}`;
            message += `\n\u200F رقم الهاتف: ${phoneNumber}`;
            message += `\n\u200F البريد الإلكتروني: ${email}`;

            message += `\n\n\u200F كود الخصم: ${couponCode || "لا يوجد"}`;
            message += `\n\u200F *الإجمالي بعد الخصم:* ${total.toFixed(2)} جنيه `;

            const subject = `\u200F *طلب جديد من *${name}**`;
            const fullMessage = `${subject}\n\n${message}`;
            const whatsappNumber = "+201066427362";
            const whatsappLink = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(fullMessage)}`;
            window.open(whatsappLink, "_blank");


        });
    }

    updateCartDisplay();
});