@extends('admin.layouts.app')

@section('title', 'Download Quotation')

@section('content')
<style>
    .info-tabs {
        padding: 16px;
        display: flex;
        row-gap: 16px;
        column-gap: 16px;
    }

    .info-tabs a {
        width: 100%;
    }

    .info-card {
        height: auto;
        width: 100%;
        padding: 16px;
        border-radius: 6px;
        background-color: var(--white);
        border: 1px solid var(--dark-light);
        transition: all ease-in 0.3s;
    }

    .info-card:hover {
        box-shadow: var(--shadow);
    }


    .info-card img {
        max-width: 50px;
        margin-bottom: 12px;
    }
</style>
<?php //dd($appointment); ?>
<div class="container-fluid p-0">
    <nav class="navbar py-3 mb-4 bg-body-tertiary">
        <div class="container">
            <!-- <a class="fs-6" href="quotation.html"><i class="bi bi-arrow-left fs-6 me-2"></i> Back</a> -->
            <button class="primary-btn addBtn" data-quotation-name="{{$quotations->name}}" type="button"><i class="bi bi-cloud-arrow-down-fill fs-6 me-2"></i>
                Download Quotation
            </button>
        </div>
    </nav>
</div>
<div id="downloadagble_quote">
    <div class="container mb-3 bg-white p-0 pt-0 px-4 d-flex align-items-start rounded" style="gap: 24px;">
        <img class="m-0" src="{{ asset('images/logo.svg') }}" alt="">
        <div class="w-100 p-3">
            <div class="d-flex justify-content-between border-bottom mb-2 pb-1">
                <div>
                    <h6 class="m-0 fw-bold">Client Details</h6>
                    <div class="m-0">
                        <label class="form-label m-0 me-2">Date: </label>
                        <label for="clientName" class="form-label m-0">
                        <?php $date = strtotime($quotations->date); // Convert to timestamp if it's a string
                        echo date('d-M-Y', $date);?></label>
                    </div>
                    
                </div>
            </div>

            <div class="row ">
                <div class="col-md-6">
                    <div class="mb-1">
                        <label class="form-label me-2">Client Name: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->name}}</label>
                    </div>

                    <div class="mb-1">
                        <label class="form-label me-2">Quotation For: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->quot_for}}</label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label me-2">Cartage: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->cartage}}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-1">
                        <label class="form-label me-2">Contact Number: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->number}}</label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label me-2">Email ID: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->email}}</label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label me-2">Address: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$quotations->address}}, {{$appointment->city}}, {{$appointment->state}} - {{$appointment->pincode}}, {{$appointment->country}}</label>
                    </div>
                </div>
            </div>



            <div class="d-flex justify-content-between border-bottom mb-2 pb-1">
                <div>
                    <h6 class="m-0 fw-bold">Order Details</h6>
                </div>
            </div>

            <div class="row ">
                <div class="col-md-6">
                    <div class="mb-1">
                        <label class="form-label me-2">Transaction Id: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$order_data->txn_id}}</label>
                    </div>

                    <div class="mb-1">
                        <label class="form-label me-2">Total Amount: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$order_data->order_value}}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-1">
                        <label class="form-label me-2">Payment Mode: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$order_data->payment_mode}}</label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label me-2">Payment By: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{$order_data->payment_mode_by}}</label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label me-2">Installation Date: </label>
                        <label for="clientName" class="form-label text-dark fw-bold">{{ \Carbon\Carbon::parse($order_data->installation_date)->format('d-m-Y, h:i A') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container bg-white py-4 px-4 mb-4 rounded">
        <h6 class="mb-2 fw-bold">Quotation Details</h6>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Oty.</th>
                    <th scope="col">unit</th>
                    <th scope="col">MRP</th>
                    <th scope="col">Discount(%)</th>
                    <th scope="col">Discounted Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sectionItems as $sectionItem)
                <tr>
                    <th class="fs-6"><u>{{$sectionItem['section_name']}}</u></th>
                </tr>
                    @foreach ($sectionItem['items'] as $item)
                    <tr>
                        <td>{{$item['item']}}</td>
                        <td>{{$item['qty']}}</td>
                        <td>{{$item['unit']}}</td>
                        <td>{{$item['price']}}</td>
                        <td>{{$item['discount']}}</td>
                        <td>{{ (float)$item['price'] - (float)$item['discount'] }}</td>
                    </tr>
                    @endforeach
                
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

<script>
    document.querySelector('.primary-btn.addBtn').addEventListener('click', function() {
        var element = document.querySelector("#downloadagble_quote");
        var name = $(this).data('quotation-name');
        let pdfName = name+'_Invoice.pdf';
        html2pdf()
            .from(element)
            .save(pdfName);
    });
</script>
@endsection