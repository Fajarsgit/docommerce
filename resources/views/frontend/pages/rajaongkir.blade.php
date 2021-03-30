@extends('frontend.layouts.master')
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
		<!-- 	<div class="mb-3 ml-5 pt-5 justify-content-center">Apply Shipping</div>
			<form name="getShipping">
			<div class="mb-3 ml-5 pt-5 justify-content-center">
			  <label for="provinsi" class="form-label">Choose Your Province</label>
			  <select name="provinsi" wire:model="provinsi_id" class="form-select" aria-label="Default select example">
			  <option value="0">Choose Province</option>
			  @forelse 
			  <option value="{{$p['province_id']}}">{{ $p['province'] }}</option>
			  @empty
			  <option value="0">Province not Available</option>
			  @endforelse
			</select>
			</div>
			<div class="mb-3 ml-5 pt-5 justify-content-center">
			  <label for="kota" class="form-label">Choose Your City</label>
			  <select name="kota" class="form-select" aria-label="Default select example">
			  <option value="0">Choose City</option>
			  @if($provinsi_id) 
			  @forelse($daftarKota as $k)
			  <option value="{{$k['city_id']}}">{{ $k['city_name'] }}</option>
			  @empty
			  <option value="0">City not Available</option>
			  @endforelse
			  @endif
			</select>
			</div>
			<div class="mb-3 ml-5 pt-5 justify-content-center">
			  <label for="nama_jasa" class="form-label">Choose Your Delivery Service</label>
			  <select name="nama_jasa" class="form-select" aria-label="Default select example">
			  <option value="0">Choose Your Delivery Service</option>
			  @forelse ($daftarProvinsi as $p)
			  <option value="{{$p['province_id']}}">{{ $p['province'] }}</option>
			  @empty
			  <option value="0">Province not Available</option>
			  @endforelse
			</select>
			</div>

<div class="button">
	<button type="submit" class="btn">proceed to checkout</button>
</div>	 -->
@endsection