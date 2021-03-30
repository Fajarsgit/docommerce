<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use Illuminate\Support\Str;
use Helper;
use DB;
use \Midtrans\Notification;
use \Midtrans\Config;
use Auth;
use Kavist\RajaOngkir\RajaOngkir;

class RajaOngkirController extends Controller
{	public $cart;
	private $apiKey = 'fae066eb1e3cfbaf4da99158251efebc';
	public $provinsi_id, $kota_id, $jasa, $daftarProvinsi, $daftarKota;
    public function mount($id)
    {	
    	if(Auth::user())
    	{
    		return redirect()->route('login.form');
    	}
    	$this->cart = Cart::find($id);
    }
    public function rajaongkir()
    {
    	$rajaOngkir = new RajaOngkir($this->apiKey);
		$this->daftarProvinsi = $rajaOngkir->provinsi()->all();
		
		

		if($this->provinsi_id)
		{
			$this->daftarKota = $rajaOngkir->kota()->dariProvinsi($this->provinsi_id)->get();
			dd($this->daftarKota);
		}
    	return view('rajaongkir');
    }
}
