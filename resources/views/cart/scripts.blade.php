<script>
    document.addEventListener('DOMContentLoaded', function() {
        const version = '2.3';
        console.log('Cart Scripts Version: ' + version);
        
        const updateUI = (data) => {
            const currency = document.querySelector('meta[name="currency"]')?.getAttribute('content') || "{{ $settings->currency ?? 'SAR' }}";
            
            // Update Summary Totals
            document.getElementById('cart-subtotal').innerText = `${data.subtotal} ${currency}`;
            
            const offerDiscountEl = document.getElementById('offer-discount');
            if (offerDiscountEl) {
                if (data.offer_discount > 0) {
                    offerDiscountEl.closest('.py-4').classList.remove('hidden');
                    offerDiscountEl.innerText = `-${data.offer_discount.toFixed(2)} ${currency}`;
                } else {
                    offerDiscountEl.closest('.py-4').classList.add('hidden');
                }
            }
            
            const couponDiscountEl = document.getElementById('coupon-discount');
            if (couponDiscountEl) {
                if (data.coupon_discount > 0) {
                    couponDiscountEl.closest('.py-4').classList.remove('hidden');
                    couponDiscountEl.innerText = `-${data.coupon_discount.toFixed(2)} ${currency}`;
                } else {
                    couponDiscountEl.closest('.py-4').classList.add('hidden');
                }
            }
            
            document.getElementById('cart-total').innerText = `${data.total} ${currency}`;

            // Update Shipping Cost Display
            const shippingDisplay = document.getElementById('shipping-cost-display');
            if (shippingDisplay) {
                if (data.shipping_is_free) {
                    shippingDisplay.innerHTML = `<span class="text-green-600 font-bold">${"{{ __('Free Shipping') }}"}</span>`;
                } else {
                    shippingDisplay.innerText = data.shipping_label || `0.00 ${currency}`;
                }
            }

            // Update Free Shipping Progress Bar
            const threshold = data.free_shipping_threshold || 0;
            const subtotalValue = typeof data.subtotal === 'string' ? data.subtotal.replace(/[^\d.]/g, '') : data.subtotal;
            const subtotal = parseFloat(subtotalValue);
            const container = document.getElementById('shipping-progress-container');
            const bar = document.getElementById('shipping-progress-bar');
            const percentText = document.getElementById('shipping-progress-percent');
            const statusText = document.getElementById('shipping-progress-text');

            if (container && threshold > 0) {
                if (data.free_shipping || data.shipping_is_free) {
                    container.classList.add('hidden');
                } else {
                    container.classList.remove('hidden');
                    const percentage = Math.min(100, Math.max(0, (subtotal / threshold) * 100));
                    const remaining = (threshold - subtotal).toFixed(2);
                    
                    bar.style.width = `${percentage}%`;
                    percentText.innerText = `${Math.round(percentage)}%`;
                    
                    if (remaining > 0) {
                        statusText.innerText = "{{ __('Add :amount more for free shipping', ['amount' => ':amount']) }}".replace(':amount', `${remaining} ${currency}`);
                    } else {
                        statusText.innerText = "{{ __('You unlocked free shipping!') }}";
                    }
                }
            }

            // Update Global Cart Count
            const cartBadge = document.getElementById('cart-count');
            if (cartBadge) {
                const cartCount = data.items.reduce((acc, item) => acc + item.quantity, 0);
                cartBadge.innerText = cartCount;
            }

            // Update all quantity inputs for items still in cart
            data.items.forEach(item => {
                const inputs = document.querySelectorAll(`.quantity-input-${item.id}`);
                inputs.forEach(input => {
                    input.value = item.quantity;
                });
            });

            // Sync DOM items with server data (remove rows that no longer exist)
            const serverItemIds = data.items.map(item => String(item.id));
            
            // Sync Desktop Rows
            document.querySelectorAll('[id^="cart-item-row-"]').forEach(row => {
                const id = row.id.replace('cart-item-row-', '');
                if (!serverItemIds.includes(id)) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => row.remove(), 500);
                }
            });

            // Sync Mobile Rows
            document.querySelectorAll('[id^="cart-item-mobile-row-"]').forEach(row => {
                const id = row.id.replace('cart-item-mobile-row-', '');
                if (!serverItemIds.includes(id)) {
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 500);
                }
            });

            // Check if cart is empty after synchronization
            if (data.items.length === 0) {
                setTimeout(() => location.reload(), 600); // Small delay for animations
            }
        };

        // Quantity Update Logic
        const quantityButtons = document.querySelectorAll('.quantity-btn');
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const action = this.getAttribute('data-action');
                const input = document.querySelector(`.quantity-input-${id}`);
                const currentQuantity = parseInt(input.value);

                let newQuantity = currentQuantity;
                if (action === 'increase') {
                    newQuantity++;
                } else if (action === 'decrease' && currentQuantity > 1) {
                    newQuantity--;
                } else {
                    return;
                }

                input.value = newQuantity;

                fetch(`/cart/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            quantity: newQuantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.items) {
                            const updatedItem = data.items.find(item => item.id == id);
                            const currency = document.querySelector('meta[name="currency"]')?.getAttribute('content') || "{{ $settings->currency ?? 'SAR' }}";
                            
                            if (updatedItem) {
                                const itemTotal = (updatedItem.price * updatedItem.quantity).toFixed(2);
                                const desktopTotal = document.getElementById(`cart-item-total-${id}`);
                                const mobileTotal = document.getElementById(`cart-item-total-mobile-${id}`);
                                
                                if (desktopTotal) desktopTotal.innerText = `${itemTotal} ${currency}`;
                                if (mobileTotal) mobileTotal.innerText = `${itemTotal} ${currency}`;
                            }
                            updateUI(data);
                        }
                    })
                    .catch(error => {
                        console.error('Update error:', error);
                        input.value = currentQuantity;
                    });
            });
        });

        // Removal Logic
        const removeButtons = document.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!confirm("{{ __('Are you sure you want to remove this item?') }}")) return;

                const desktopRow = document.getElementById(`cart-item-row-${id}`);
                const mobileRow = document.getElementById(`cart-item-mobile-row-${id}`);

                // Visual feedback immediate
                if (desktopRow) desktopRow.style.opacity = '0.5';
                if (mobileRow) mobileRow.style.opacity = '0.5';

                fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.items) {
                        // Animate out and remove
                        if (desktopRow) {
                            desktopRow.style.transform = 'translateX(-20px)';
                            desktopRow.style.opacity = '0';
                            setTimeout(() => desktopRow.remove(), 500);
                        }
                        if (mobileRow) {
                            mobileRow.style.transform = 'scale(0.95)';
                            mobileRow.style.opacity = '0';
                            setTimeout(() => mobileRow.remove(), 500);
                        }
                        updateUI(data);
                    }
                })
                .catch(error => {
                    console.error('Removal error:', error);
                    if (desktopRow) desktopRow.style.opacity = '1';
                    if (mobileRow) mobileRow.style.opacity = '1';
                });
            });
        });

        // Shipping Zone Change Logic
        const zoneSelect = document.getElementById('shipping_zone_id');
        if (zoneSelect) {
            zoneSelect.addEventListener('change', function() {
                const zoneId = this.value;
                
                fetch("{{ route('cart.zone.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        zone_id: zoneId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        updateUI(data);
                    }
                })
                .catch(error => {
                    console.error('Zone update error:', error);
                });
            });
        }
    });
</script>