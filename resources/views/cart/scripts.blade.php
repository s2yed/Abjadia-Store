<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.quantity-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const action = this.getAttribute('data-action');
                const input = document.querySelector(`.quantity-input-${id}`);
                let currentQuantity = parseInt(input.value);

                let newQuantity = currentQuantity;
                if (action === 'increase') {
                    newQuantity++;
                } else if (action === 'decrease' && currentQuantity > 1) {
                    newQuantity--;
                } else {
                    return; // Do nothing if quantity is 1 and trying to decrease
                }

                // Update input visually immediately (optimistic UI)
                input.value = newQuantity;

                // Send AJAX request
                fetch(`/cart/update/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-HTTP-Method-Override': 'PATCH',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            quantity: newQuantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI with calculated values from server
                            const desktopTotal = document.getElementById(`cart-item-total-${id}`);
                            if (desktopTotal) desktopTotal.innerText = `${data.itemTotal} EGP`;

                            const mobileTotal = document.getElementById(`cart-item-total-mobile-${id}`);
                            if (mobileTotal) mobileTotal.innerText = `${data.itemTotal} EGP`;

                            document.getElementById('cart-subtotal').innerText = `${data.total} EGP`;
                            document.getElementById('cart-total').innerText = `${data.total} EGP`;

                            // Update global cart badge
                            const cartBadge = document.getElementById('cart-count');
                            if (cartBadge) {
                                cartBadge.innerText = data.cartCount;
                            }
                        } else {
                            // Revert on failure
                            input.value = currentQuantity;
                            alert('Something went wrong. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        input.value = currentQuantity;
                    });
            });
        });
    });
</script>