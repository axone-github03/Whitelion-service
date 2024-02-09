<?php

namespace App\Http\Controllers;

use App\Models\CityList;
use App\Models\CountryList;
use App\Models\DataMaster;
use App\Models\MainMaster;
use App\Models\StateList;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

	public function __construct() {
	}

	public function searchCountry(Request $request)
	{

		$searchKeyword = isset($request->q) ? $request->q : "in";
		$id = isset($request->id) ? $request->id : 0;

		$CountryList = CountryList::select('id', 'name as text');
		if ($id != 0) {
			$CountryList->where('id', $id);
			$CountryList->limit(1);
		} else {
			$CountryList->where('name', 'like', "%" . $searchKeyword . "%");
			$CountryList->limit(5);
		}

		$CountryList = $CountryList->get();
		$response = array();
		$response['results'] = $CountryList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchCity(Request $request)
	{

		$searchKeyword = isset($request->q) ? $request->q : "sur";

		$CityList = CityList::select('id', 'name as text');
		$CityList->where('name', 'like', "%" . $searchKeyword . "%");
		$CityList->where('status', 1);
		$CityList->limit(5);
		$CityList = $CityList->get();
		$response = array();
		$response['results'] = $CityList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchCityStateCountry(Request $request)
	{

		$searchKeyword = isset($request->q) ? $request->q : "sur";

		$CityList = CityList::select('city_list.id', 'city_list.name as city_list_name', 'state_list.name as state_list_name');
		$CityList->leftJoin('state_list', 'state_list.id', '=', 'city_list.state_id');
		$CityList->where('city_list.name', 'like', "%" . $searchKeyword . "%");
		$CityList->where('city_list.status', 1);
		$CityList->limit(5);
		$CityList = $CityList->get();
		foreach ($CityList as $key => $value) {
			$CityList[$key]['text'] = $value['city_list_name'] . ", " . $value['state_list_name'] . ", India";
		}


		$response = array();
		$response['results'] = $CityList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchStateFromCountry(Request $request)
	{

		$searchKeyword = isset($request->q) ? $request->q : "guj";
		$countryId = isset($request->country_id) ? $request->country_id : "";
		$id = isset($request->id) ? $request->id : 0;
		$StateList = array();

		if ($countryId != "") {

			$StateList = StateList::select('id', 'name as text');
			$StateList->where('name', 'like', "%" . $searchKeyword . "%");
			$StateList->where('country_id', $request->country_id);
			$StateList->limit(5);
			$StateList = $StateList->get();
		} else if ($id != 0) {

			$StateList = StateList::select('id', 'name as text');
			$StateList->where('id', $id);
			$StateList->limit(1);
			$StateList = $StateList->get();
		}

		$response = array();
		$response['results'] = $StateList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchCityFromState(Request $request)
	{

		$searchKeyword = isset($request->q) ? $request->q : "sur";
		$stateId = isset($request->state_id) ? $request->state_id : "";
		$id = isset($request->id) ? $request->id : 0;
		$CityList = array();

		if ($stateId != "") {

			$CityList = CityList::select('id', 'name as text');
			$CityList->where('state_id', $request->state_id);
			$CityList->where('name', 'like', "%" . $searchKeyword . "%");
			$CityList->where('status', 1);
			$CityList->limit(5);
			$CityList = $CityList->get();
		} else if ($id != 0) {
			$CityList = CityList::select('id', 'name as text');
			$CityList->where('id', $id);
			$CityList->limit(1);
			$CityList = $CityList->get();
		}

		$response = array();
		$response['results'] = $CityList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	function searchCourier(Request $request)
	{

		$DataMaster = array();

		$MainMaster = MainMaster::select('id')->where('code', 'COURIER_SERVICE')->first();
		if ($MainMaster) {

			$DataMaster = array();
			$DataMaster = DataMaster::select('id', 'name as text');
			$DataMaster->where('main_master_id', $MainMaster->id);
			$DataMaster->where('name', 'like', "%" . $request->q . "%");
			$DataMaster->limit(5);
			$DataMaster = $DataMaster->get();
		}

		$response = array();
		$response['results'] = $DataMaster;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	function notificationScheduler(Request $request) {
		
	}
	
}
