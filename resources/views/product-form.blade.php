<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">Product List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" width="60">
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-bs-toggle="modal" data-bs-target="#editProductModal">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $product->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/product-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Product Name" required>
                    <input type="number" name="price" class="form-control mb-2" placeholder="Price" required>
                    <input type="file" name="images[]" class="form-control" multiple required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-product-id">

                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" id="edit-product-name" class="form-control mb-2" required>

                    <label class="form-label">Price</label>
                    <input type="number" name="price" id="edit-product-price" class="form-control mb-2" required>

                    <div class="mb-2">
                        <label class="form-label">Existing Images</label>
                        <div id="edit-product-images" class="d-flex flex-wrap gap-2">
                            <!-- Existing images will be loaded here -->
                        </div>
                    </div>

                    <label class="form-label">Upload New Images (Optional)</label>
                    <input type="file" name="images[]" class="form-control" multiple>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Delete Product
    $('.delete-btn').on('click', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            fetch(`/inovantsolution/public/product-delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                $(`#product-${id}`).remove();
            })
            .catch(error => alert('Failed to delete.'));
        }
    });

    // Edit Modal Fill
    $('.edit-btn').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = $(this).data('price');

        $('#edit-product-id').val(id);
        $('#edit-product-name').val(name);
        $('#edit-product-price').val(price);
        $('#editProductForm').attr('action', `/inovantsolution/public/product-update/${id}`);

        // Clear previous images
        $('#edit-product-images').empty();

        // Fetch existing images via AJAX
        fetch(`/inovantsolution/public/product/${id}/images`)
            .then(response => response.json())
            .then(images => {
                images.forEach(image => {
                    const imageHtml = `
                        <div class="position-relative image-wrapper" data-id="${image.id}">
                            <img src="/inovantsolution/public/storage/${image.image_path}" width="80" class="rounded border">
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image-btn" data-id="${image.id}">×</button>
                        </div>
                    `;
                    $('#edit-product-images').append(imageHtml);
                });
            })
            .catch(err => {
                console.error('Failed to load images', err);
            });
    });

    // Delete product image
    $(document).on('click', '.delete-image-btn', function () {
        const imageId = $(this).data('id');
        const wrapper = $(this).closest('.image-wrapper');

        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/inovantsolution/public/product-image-delete/${imageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'POST'
                })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                wrapper.remove(); // Remove the image from UI
            })
            .catch(error => {
                alert('Failed to delete image');
                console.error(error);
            });
        }
    });

</script>

</body>
</html>
