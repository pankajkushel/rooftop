@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="card">
    <h5 class="card-header text-center">
        Product Details
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Type</th>
                        <td>Type Value</td>
                    </tr>
                    <tr>
                        <th>Code</th>
                        <td>Code Value</td>
                    </tr>
                    <tr>
                        <th>File Number</th>
                        <td>File Number Value</td>
                    </tr>
                    <tr>
                        <th>Supplier Name</th>
                        <td>Supplier Name Value</td>
                    </tr>
                    <tr>
                        <th>Supplier Collection</th>
                        <td>Supplier Collection Value</td>
                    </tr>
                    <tr>
                        <th>Supplier Collection Design</th>
                        <td>Supplier Collection Design Value</td>
                    </tr>
                    <tr>
                        <th>Design SKU</th>
                        <td>Design SKU Value</td>
                    </tr>
                    <tr>
                        <th>Width</th>
                        <td>Width Value</td>
                    </tr>
                    <tr>
                        <th>Rubs Martendale</th>
                        <td>Rubs Martendale Value</td>
                    </tr>
                    <tr>
                        <th>Usage</th>
                        <td>Usage Value</td>
                    </tr>
                    <tr>
                        <th>Type (Technical specs)</th>
                        <td>Type Technical specs Value</td>
                    </tr>
                    <tr>
                        <th>Design Type</th>
                        <td>Design Type Value</td>
                    </tr>
                    <tr>
                        <th>Color</th>
                        <td>Color Value</td>
                    </tr>
                    <tr>
                        <th>Composition</th>
                        <td>Composition Value</td>
                    </tr>
                    <tr>
                        <th>Note</th>
                        <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. A veritatis, in ab modi dolor quas molestiae repellendus atque natus ut, facere quidem, velit quae veniam consequuntur. Omnis a praesentium quod.</td>
                    </tr>
                    
                    <tr>
                        <th>Image Gallery</th>
                        <td>
                            <!-- @foreach($product->image_gallery as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Product Image" style="width: 100px; height: 100px; margin: 5px;">
                            @endforeach -->
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $product->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $product->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products List</a>
        </div>
    </div>
</div>
@endsection
