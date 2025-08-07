<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .product-image {
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product List</h2>
        <button class="btn btn-primary" id="view-cart-btn" data-bs-toggle="modal" data-bs-target="#cartModal">
            View Cart
        </button>

    </div>

    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 mb-4" id="product-{{ $product->id }}">
                <div class="card h-100 p-2">
                    @if($product->images->count())
                        <div style="overflow-x: auto; white-space: nowrap;" class="mb-2">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="product-image me-2" style="width: 100px; height: 100px; display: inline-block; object-fit: cover;">
                            @endforeach
                        </div>
                    @else
                        <img src="https://via.placeholder.com/300x200" class="card-img-top product-image" alt="No Image">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Price: ₹{{ $product->price }}</p>
                        <div class="input-group mb-2">
                            <input type="number" class="form-control quantity-input" min="1" value="1" style="max-width: 100px;">
                            <button class="btn btn-success add-to-cart-btn ms-2" data-id="{{ $product->id }}">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cart Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items-list" class="list-group">
                    <!-- Cart items will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to Cart
    $('.add-to-cart-btn').on('click', function () {
        const button = $(this);
        const productId = button.data('id');
        const quantity = button.closest('.card-body').find('.quantity-input').val() || 1;

        $.post('/inovantsolution/public/cart-add', {
            product_id: productId,
            quantity: quantity
        })
        .done(function (res) {
            alert(res.message);
        })
        .fail(function () {
            alert('Failed to add to cart');
        });
    });

    // Show Cart Modal
    $('#view-cart-btn').on('click', function () {
        fetch('/inovantsolution/public/cart-list')
            .then(res => res.json())
            .then(data => {
                const items = data.cart_items; // 👈 FIX: access the actual array

                if (!items || items.length === 0) {
                    $('#cart-items-list').html('<p>No items in cart.</p>');
                    return;
                }

                let html = '<table class="table table-bordered">';
                html += '<thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead><tbody>';

                items.forEach(item => {
                    const product = item.product;
                    const total = product.price * item.quantity;

                    let imageRowHtml = '';
                    if (product.images.length > 0) {
                        imageRowHtml = product.images.map(img => {
                            return `<img src="/inovantsolution/public/storage/${img.image_path}" style="width: 60px; height: 60px; margin-right: 5px; object-fit: cover;" />`;
                        }).join('');
                    } else {
                        imageRowHtml = `<img src="https://via.placeholder.com/60" style="width: 60px; height: 60px;" />`;
                    }
                    html += `
                        <tr>
                            <td>${imageRowHtml}</td>
                            <td>${product.name}</td>
                            <td>₹${product.price}</td>
                            <td>${item.quantity}</td>
                            <td>₹${total}</td>
                        </tr>
                    `;

                });

                html += '</tbody></table>';
                $('#cart-items-list').html(html);
            })
            .catch(err => {
                console.error(err);
                $('#cart-items-list').html('<p>Error loading cart.</p>');
            });
    });
</script>
</body>
</html>
