@extends('admin.layouts.app')
@section('title', 'Calculator')
@section('content')

<div class="dataOverviewSection mt-3">
                    <div class="section-title">
                        <h6 class="fw-bold m-0 custem">Calculators</h6>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="dataOverview">
                                <div class="mb-3">
                                    <h6 class="m-0">Curtain Calculator</h6>
                                    <p class="m-0 text-muted">Please enter the required Curtain size in meters.</p>
                                </div>
                                <div class="w-100 mb-3">
                                    <label for="Height" class="form-label mb-1">Height (m)</label>
                                    <input type="number" id="curtain_height" name="curtain_height" class="form-control w-100">
                                </div>
                                <div class="w-100 mb-3">
                                    <label for="Width" class="form-label mb-1">Width (m)</label>
                                    <input type="number" id="curtain_width" name="curtain_width" class="form-control w-100">
                                </div>
                                <button class="primary-btn addedBtn" name="curtain_calculator" id="curtain_calculator">Calculate</button>
                                <div class="resultView mt-4 border-top border-bottom py-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="m-0 fw-normal">Total Panel requirement: </p>
                                        <p class="m-0 fw-bold"><span id="curtain_panel"></span> Panels</p>
                                    </div>
                                    <div class="d-flex justify-content-between m-0">
                                        <p class="m-0 fw-normal">Total fabric requirement: </p>
                                        <p class="m-0 fw-bold"><span id="curtain_fabric"></span> meters</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dataOverview">
                                <div class="mb-3">
                                    <h6 class="m-0">Blind Calculator</h6>
                                    <p class="m-0 text-muted">Please enter the required blind size in meters.</p>
                                </div>
                                <div class="w-100 mb-3">
                                    <label for="Height" class="form-label mb-1">Height (m)</label>
                                    <input type="number" name="blind_height" id="blind_height" class="form-control w-100">
                                </div>
                                <div class="w-100 mb-3">
                                    <label for="Width" class="form-label mb-1">Width (m)</label>
                                    <input type="number" name="blind_width" id="blind_width" class="form-control w-100">
                                </div>
                                <button class="primary-btn addedBtn" name="blind_calculator" id="blind_calculator">Calculate</button>
                                <div class="resultView mt-4 border-top border-bottom py-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="m-0 fw-normal">Total Panel requirement: </p>
                                        <p class="m-0 fw-bold"><span id="blind_panel"></span> Panels</p>
                                    </div>
                                    <div class="d-flex justify-content-between m-0">
                                        <p class="m-0 fw-normal">Total fabric requirement: </p>
                                        <p class="m-0 fw-bold"><span id="blind_fabric"></span> meters</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection
@section('script')
<script>

$('#curtain_calculator').click(function() {

    const height = parseFloat($('#curtain_height').val());
    const width = parseFloat($('#curtain_width').val());

    if (isNaN(height) || isNaN(width)) {
        alert("Please enter valid numbers for height and width.");
        return;
    }

    const Theight = height + 0.4;
    const Twidth = width / 0.5;

    curtainPanel =Math.ceil(Twidth);
    const curtain_fabric = (Theight * curtainPanel).toFixed(2);

    $('#curtain_panel').text(curtainPanel);
    $('#curtain_fabric').text(curtain_fabric);

});


$('#blind_calculator').click(function() {

    const height = parseFloat($('#blind_height').val());
    const width = parseFloat($('#blind_width').val());

    if (isNaN(height) || isNaN(width)) {
        alert("Please enter valid numbers for height and width.");
        return;
    }

    const Theight = height + 0.4;
    const Twidth = width / 1.30;

    blind_panel =Math.ceil(Twidth);
    blind_fabric = (Theight * blind_panel).toFixed(2);
    $('#blind_panel').text(blind_panel);
    $('#blind_fabric').text(blind_fabric);
});

</script>

@endsection