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

    body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td p {
            margin: 4px 0px !important;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .no-border td {
            border: none;
        }
        .table-heading{
            background-color:#E0E0E0;
        }
        .align-right{
            text-align: right !important;
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
    <div class="container mb-3 bg-white p-0 pt-0 px-4 align-items-start rounded" style="gap: 24px;">
    <body>
    <table>
        <tr>
            <td colspan="4">
                <h2 style="text-align: center; margin: 4px 0px !important; font-size: 18px;">SALES ORDER</h2>
            </td>
        </tr>
        <tr>
            <td colspan="1">
                <p><strong>CURTAINS AND BLINDS</strong></p>
                <p>Plot No 3, Khasra-385, Ground Floor, 100 FT Road,
                    Ghitorni, Delhi - 110030</p>
                <p><b>GSTIN/UIN</b>: 07AABPS3060K1ZN</p>
                <p><b>E-Mail: </b> info@pretfab.com</p>
            </td>
            <td>
                <table class="no-border">
                    <tr>
                        <td><strong>Voucher No.</strong> {{$quotations->voucher_no}}</td>
                    </tr>
                    <tr>
                        <td><strong>Dated:</strong> {{ \Carbon\Carbon::parse($quotations->date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mode/Terms of Payment:</strong> Online</td>
                    </tr>
                    <tr>
                        <td><strong>Buyer's Ref/Order No:</strong> {{$quotations->buyer_ref}}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="no-border">
                    <tr>
                        <td><strong>Other References:</strong> {{$quotations->other_ref ?? 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td><strong>Dispatched through:</strong> {{$quotations->dispatch ?? 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td><strong>Destination:</strong> {{$quotations->destination}}</td>
                    </tr>
                    <tr>
                        <td><strong>City/Port of Loading:</strong> {{$quotations->destination}}</td>
                    </tr>
                    <tr>
                        <td><strong>City/Port of Discharge:</strong> {{ $appointment->state}}</td>
                    </tr>
                    <tr>
                        <td><strong>Terms of Delivery:</strong> {{$quotations->terms_delivery}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p><strong>Consignee (Ship to)</strong></p>
                <p>{{ $order_data['franchise']['name'] ?? $order_data['franchise']['company_name'] }}</p>
                <p>{{ $order_data['franchise']['mobile'] }}</p>
                <p>{{ $order_data['franchise']['address'] ?? '' }}</p>
                <p><strong>GSTIN/UIN</strong>:  {{ $quotations->gst_no }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p><strong>Buyer (Bill to)</strong></p>
                <p>{{ $appointment['name'] ?? '' }}</p>
                <p>{{ $appointment['address'] ?? '' }}</p>
                <p><strong>GSTIN/UIN</strong>: {{ $quotations->gst_no }}</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th class="table-heading">Description of Goods and Services</th>
            <th class="table-heading">HSN/SAC</th>
            <th class="table-heading">GST Rate</th>
            <th class="table-heading">Quantity</th>
            <th class="table-heading">Rate</th>
            <th class="table-heading">Discount %</th>
            <th class="table-heading">Amount</th>
        </tr>
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
        
    </table>

    <table>
        <tr>
            <td style="text-align: right; width: 35.20%;">SGST-OUTPUT</td>
            <td class="align-right">185.44</td>
        </tr>
        <tr>
            <td style="text-align: right; width: 35.20%;">CGST-OUTPUT</td>
            <td class="align-right">185.44</td>
        </tr>
        <tr>
            <td style="text-align: right; width: 35.20%;"><span style="float: left; font-style: italic; font-weight: 200;">Less: </span>Round Off</td>
            <td class="align-right">-0.38</td>
        </tr>
    </table>
    <table>
        <tr class="table-heading">
            <td style="text-align: right; width: 35.20%;">Total</td>
            <td style="text-align: right; width: 36%;"><strong>75.5 <span>MTRS</span></strong></td>
            <td class="align-right"><strong>₹ 7780.00</strong></td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 62%;">Amount Chargeable (in words):</td>
            <td class="align-right">E. & O.E</td>
        </tr>
        <tr>
            <td style="width: 62%;"><strong>INR Seven Thousand Seven Hundred Eighty Eight Only</strong></td>
            <td><strong>For Curtains and Blinds</strong></td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 62%;"><strong>Declaration:</strong><br>The above mentioned rates are valid for 7 days from
                the date of issue.
                We declare that this invoice shows the actual price of the goods described and that all particulars are
                true and correct.</td>
            <td colspan="2">
                <img style="margin: 12px 0px;"
                    src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Jon_Kirsch%27s_Signature.png"
                    alt="Signature" width="150">
                <br>
                <p style="font-size: 12px;">Authorised Signatory</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                <p><strong>Terms and Conditions</strong></p>
                <ul style="padding-left: 25px;">
                    <li>Payment non-refundable.</li>
                    <li>Delivery of goods within 15 days
                        of payment received.</li>
                    <li>Payment can be done
                        via Bank account, Google Pay, or Phone Pay.</li>
                    <li>Any changes in order will be charged extra.</li>
                </ul>
            </td>
        </tr>
    </table>
</body>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

<script>
    document.querySelector('.primary-btn.addBtn').addEventListener('click', function() {
        var element = document.querySelector("#downloadagble_quote");
        var name = $(this).data('quotation-name');
        let pdfName = name+'_Invoice.pdf';

        html2pdf().from(element).set({
            html2canvas: {
                scale: 2.5,  // Scale the canvas for better quality
            }
        }).save(pdfName);
        
    });
</script>
@endsection