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

    .table-heading {
        background-color: #E0E0E0;
    }

    .align-right {
        text-align: right !important;
    }
</style>
<?php //dd($order_data); 
?>
<div class="container-fluid p-0">
    <nav class="navbar py-3 mb-4 bg-body-tertiary">
        <div class="container">
            <!-- <a class="fs-6" href="quotation.html"><i class="bi bi-arrow-left fs-6 me-2"></i> Back</a> -->
            <button class="primary-btn addBtn" data-quotation-name="{{$order_data['name']}}" type="button"><i class="bi bi-cloud-arrow-down-fill fs-6 me-2"></i>
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
                        <h2 style="text-align: center; margin: 4px 0px !important; font-size: 18px;">Quotation Order</h2>
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
                        <?php //dd($order_data); 
                        ?>
                        <table class="no-border">
                            <tr>
                                <td><strong>Voucher No.</strong> {{$order_data['unique_id'] ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Dated:</strong> {{ \Carbon\Carbon::parse($order_data['date'])->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Mode/Terms of Payment:</strong> Online</td>
                            </tr>
                            <tr>
                                <td><strong>Buyer's Ref/Order No:</strong> {{$order_data['buyer_ref'] ?? 'N/A'}}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="no-border">
                            <tr>
                                <td><strong>Other References:</strong> {{$order_data['other_ref'] ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Dispatched through:</strong> {{$order_data['dispatch'] ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Destination:</strong> {{$order_data['destination'] ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>City/Port of Loading:</strong> {{$order_data['destination'] ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td><strong>City/Port of Discharge:</strong> {{ $order_data['appointment']['state'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terms of Delivery:</strong> {{$order_data['terms_delivery'] ?? 'N/A'}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <p><strong>Consignee (Ship to)</strong></p>
                        <p>{{ $order_data['appointment']['name'] ?? $order_data['appointment']['company_name'] }}</p>
                        <p>{{ $order_data['appointment']['mobile'] }}</p>
                        <p>{{ $order_data['appointment']['address'] ?? '' }}</p>
                        <p><strong>GSTIN/UIN</strong>: {{ $order_data['gst_no'] ?? 'N/A' }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <p><strong>Buyer (Bill to)</strong></p>
                        <p>{{ $order_data['appointment']['name'] ?? $order_data['appointment']['company_name'] }}</p>
                        <p>{{ $order_data['appointment']['mobile'] }}</p>
                        <p>{{ $order_data['appointment']['address'] ?? '' }}</p>
                        <p><strong>GSTIN/UIN</strong>: {{ $order_data['gst_no'] ?? 'N/A' }}</p>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <th class="table-heading">Description of Goods and Services</th>
                    <!-- <th class="table-heading">GST Rate</th> -->
                    <th class="table-heading">Quantity</th>
                    <th class="table-heading">Unit</th>
                    <th class="table-heading">Rate</th>
					<th class="table-heading">GST %</th>
                    <th class="table-heading">Discount %</th>
                    <th class="table-heading">Amount</th>
                </tr>
                <?php $total = 0; $gst_amount=0; $total_gst_per=0;$per_item_discount=0; $per_item_total_discount=0;   ?>
                @foreach($order_data['quotaiton_section'] as $sectionItem)
                <tr>
                    <th class="fs-6"><u>{{$sectionItem['section_name']}}</u></th>
                </tr>
                @foreach ($sectionItem['items'] as $item)
                <?php $total = $total + $item['price']*$item['qty']; ?>
				<?php  $gst_amount =$gst_amount+($item['price']*$item['qty']*$item['gst_percentage']/100) ?>
				<?php $per_item_gst_amount=($item['price']*$item['qty']*$item['gst_percentage']/100)?>
				<?php $total_gst_per=$total_gst_per + $item['gst_percentage'];?>
				<?php if(!empty($item['discount'])){
				$per_item_discount=($item['price']*$item['qty']*$item['discount']/100); } ?>
				<?php $per_item_total_discount= $per_item_total_discount + $per_item_discount ?>
                <tr>
                    <!-- <?php // echo'<pre>';print_r($order_data['appointment']['state']); exit; 
                            ?> -->
                    <td>{{$item['name']}}</td>
                    <!-- <td>{{$item['qty']}}</td> -->
                    <td>{{$item['qty']}}</td>
                    <td>{{$item['unit']}}</td>
                    <td>{{$item['price']}}</td>
					<td>{{$item['gst_percentage']}}</td>  
                    <td>{{$item['discount']}}</td>
                    <td>{{((($item['price']*$item['qty'])))}}</td>
                </tr>
                @endforeach

                @endforeach

            </table>
            <table>
                @if($order_data['appointment']['state'] == 'delhi' || $order_data['appointment']['state'] == 'Delhi')
                <tr>
			<!--{{$total_gst_per}}%  -->
                    <td style="text-align: right; width: 35.20%;">IGST-OUTPUT ()</td>
                    <td class="align-right">{{ $gst_amount }}</td>
                </tr>
                @else
                <tr>
                    <td style="text-align: right; width: 35.20%;">SGST-OUTPUT ({{$total_gst_per/2}}%)</td>
                    <td class="align-right">{{ $gst_amount/2 }}</td>
                </tr>
                <tr>
                    <td style="text-align: right; width: 35.20%;">CGST-OUTPUT ({{$total_gst_per/2}}%)</td>
                    <td class="align-right">{{ $gst_amount/2 }}</td>
                </tr>
                @endif

                <tr>
                    <td style="text-align: right; width: 35.20%;"><span style="float: left; font-style: italic; font-weight: 200;">Less: </span>Round Off</td>
                    <td class="align-right">-0.38</td>
                </tr>
            </table>
            <table>
                <tr class="table-heading">
                    <td style="text-align: right; width: 35.20%;">Total</td>
                    <!-- <td style="text-align: right; width: 36%;"><strong>75.5 <span>MTRS</span></strong></td> -->
                    <td class="align-right"><strong>₹ {{ ($total+$gst_amount) }}</strong></td>     
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width: 62%;">Amount Chargeable (in words):</td>
                    <td class="align-right">E. & O.E</td>
                </tr>
                <tr>
				<?php use NumberToWords\NumberToWords;
						$numberToWords = new NumberToWords();
						$numberTransformer = $numberToWords->getNumberTransformer('en'); // 'en' for English

						 ?>
                    <td style="width: 62%;"><strong><?php echo ucwords($numberTransformer->toWords(($total+$gst_amount)))?></strong></td>
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
        let pdfName = name + '_Invoice.pdf';

        html2pdf().from(element).set({
            html2canvas: {
                scale: 2.5, // Scale the canvas for better quality
            }
        }).save(pdfName);

    });
</script>
@endsection