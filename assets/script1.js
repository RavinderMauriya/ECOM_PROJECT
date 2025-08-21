/* ------------------------------
    Mobile Navigation Toggle
------------------------------ */
const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if (bar) bar.addEventListener('click', () => nav.classList.add('active'));
if (close) close.addEventListener('click', () => nav.classList.remove('active'));


/* ------------------------------
    Add to Cart 
------------------------------ */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.cart');
    if (btn) {
        e.preventDefault();

        const card = btn.closest('.pro');
        const productId = card?.getAttribute('data-id');
        const quantity = 1;

        if (!productId) return;

        fetch('cart/add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity })
        })
        .then(res => res.json())
        .then(data => {
            console.log('response:', data);

            if (data.status === 'success') {
                alert('✅ Product added to cart!');
                renderCart();
            } /* else if (data.message === 'Login required') { */
             else if (data.message && data.message.toLowerCase().includes('login')) {

                alert('You need to log in to add items to the cart.');
                window.location.href = 'login.php';
            } else {
                alert('❌ Failed to add to cart.');
            }
        })
        .catch(err => console.error('❌ Add to cart error:', err));
    }
});


/* ------------------------------
   Render Cart Items from DB
------------------------------ */
function renderCart() {
    const tbody = document.querySelector('#cart tbody');
    const subtotalTable = document.querySelector('#subtotal table');
    if (!tbody || !subtotalTable) return;

    fetch('cart/fetch.php')
        .then(res => res.json())
        .then(cart => {
            tbody.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const price = parseFloat(item.price);
                const subtotal = price * item.quantity;
                total += subtotal;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><a href="#" class="remove-item" data-product-id="${item.product_id}"><i class="fa-regular fa-circle-xmark"></i></a></td>
                    <td><img src="${item.image}" width="50"></td>
                    <td>${item.title}</td>
                    <td>₹${price.toFixed(2)}</td>
                    <td><input type="number" value="${item.quantity}" class="quantity-input" data-product-id="${item.product_id}"></td>
                    <td>₹${subtotal.toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });

            subtotalTable.innerHTML = `
                <tr><td>Cart Subtotal</td><td>₹${total.toFixed(2)}</td></tr>
                <tr><td>Shipping</td><td>Free</td></tr>
                <tr><td><strong>Total</strong></td><td><strong>₹${total.toFixed(2)}</strong></td></tr>
            `;
        })
        .catch(err => console.error('❌ Fetch cart error:', err));
}


/* ------------------------------
   Remove Item from Cart
------------------------------ */
document.addEventListener('click', function (e) {
    const removeBtn = e.target.closest('.remove-item');
    if (removeBtn) {
        e.preventDefault();
        const productId = removeBtn.getAttribute('data-product-id');

        fetch('cart/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart();
            } else {
                alert('❌ Failed to remove item.');
            }
        })
        .catch(err => console.error('❌ Remove error:', err));
    }
});


/* ------------------------------
 Update Quantity in Cart
------------------------------ */
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('quantity-input')) {
        const productId = e.target.getAttribute('data-product-id');
        const quantity = parseInt(e.target.value);

        if (quantity <= 0 || isNaN(quantity)) {
            alert('❌ Invalid quantity');
            e.target.value = 1;
            return;
        }

        fetch('cart/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart();
            } else {
                alert('❌ Quantity update failed');
            }
        })
        .catch(err => console.error('❌ Quantity error:', err));
    }
}); 


/* ------------------------------
    Load Cart on Page Load
------------------------------ */
document.addEventListener('DOMContentLoaded', renderCart);



