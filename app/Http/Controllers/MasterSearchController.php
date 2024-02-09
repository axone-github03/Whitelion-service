<?php

namespace App\Http\Controllers;
use App\Models\PrivilegeUserType;
use App\Models\User;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;

//use Session;

class MasterSearchController extends Controller {
    public function __construct() {

		$this->middleware(function ($request, $next) {

			// $tabCanAccessBy = array(0, 1);

			// if (!in_array(Auth::user()->type, $tabCanAccessBy)) {
			// 	return redirect()->route('dashboard');
			// }

			return $next($request);

		});

	}

	function SalesUserAjax(Request $request){
		$UserSearch_column = array(
			'users.id',
			'users.first_name',
			'users.last_name',
			'users.email',
			'users.phone_number',
			'users.address_line1',
			'users.address_line2',
			DB::raw('CONCAT(users.first_name," ",users.last_name)'),
			DB::raw('CONCAT(users.address_line1," ",users.address_line2)'),
		);

		$UserSelect_column = array(
			0 => 'users.id',
			1 => 'users.first_name',
			2 => 'users.last_name',
			3 => 'users.email',
			4 => 'users.phone_number',
			5 => 'users.address_line1',
			6 => 'users.address_line2',
		);

		if (isset($request->search_value) && $request->search_value != "") {
			$search_value = $request->search_value;
			$recordsTotal = User::whereIn('users.type', array(2));
			$recordsTotal->where(function ($query33) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$recordsTotal = $recordsTotal->count();
        	$recordsFiltered = $recordsTotal;

			$unionQueryUser = User::query();
			$unionQueryUser->select($UserSelect_column);
			$unionQueryUser->whereIn('users.type', array(2));
			$unionQueryUser->where(function ($query00) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query00->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query00->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$unionQueryUser->limit($request->length);
			$unionQueryUser->offset($request->start);
			$unionQueryUser->orderBy($UserSearch_column[$request['order'][0]['column']], $request['order'][0]['dir']);

			$Data = $unionQueryUser->get();
			$data = json_decode(json_encode($Data), true);

			$viewData = array();
			foreach ($data as $key => $value) {
				$viewData[$key] = array();
				$viewData[$key]['id'] = highlightString($value['id'],$search_value);
				$viewData[$key]['name'] = '<a href="javascript:void(0)">'.highlightString($value['first_name'] .' '. $value['last_name'],$search_value) .'</a>';
				$viewData[$key]['email'] = highlightString($value['email'],$search_value);
				$viewData[$key]['mobile'] = highlightString($value['phone_number'],$search_value);
				$viewData[$key]['address'] = highlightString($value['address_line1'] .' '. $value['address_line2'],$search_value);
			}

		} else {
			$recordsTotal = 0;
			$recordsFiltered = 0;
			$viewData = array();
		}

		$jsonData = [
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $viewData, 
			"count" => intval($recordsTotal)
        ];

        return response()->json($jsonData);
	}


	function ArchitectAjax(Request $request){

		$isSalePerson = isSalePerson();
		$isChannelPartner = isChannelPartner(Auth::user()->type);
		$isAdminOrCompanyAdmin = isAdminOrCompanyAdmin();
		$isMarketingDispatcherUser = isMarketingDispatcherUser();
		if ($isSalePerson == 1) {
			$SalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
		}

        if ($isSalePerson == 1) {

            $childSalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
        }
        $isTaleSalesUser = isTaleSalesUser();

        if ($isTaleSalesUser == 1) {
            $TeleSalesCity = TeleSalesCity(Auth::user()->id);
        }

		$UserSearch_column = array(
			'users.id',
			'users.first_name',
			'users.last_name',
			'users.phone_number',
			'users.email',
			'users.address_line1',
			'users.address_line2',
			DB::raw('CONCAT(users.first_name," ",users.last_name)'),
			DB::raw('CONCAT(users.address_line1," ",users.address_line2)'),
		);

		$UserSelect_column = array(
			'users.id',
			'users.first_name',
			'users.last_name',
			'users.email',
			'users.phone_number',
			'users.address_line1',
			'users.address_line2',
		);

		if (isset($request->search_value) && $request->search_value != "") {
			$search_value = $request->search_value;
			$recordsTotal = User::whereIn('users.type', array(201, 202));
			$recordsTotal->leftJoin('architect', 'architect.user_id', '=', 'users.id');
			if ($isAdminOrCompanyAdmin == 1) {
			} else if ($isSalePerson == 1) {
				$recordsTotal->whereIn('architect.sale_person_id', $SalePersonsIds);
			} else if ($isChannelPartner != 0) {
				$recordsTotal->where('architect.added_by', Auth::user()->id);
			}
			$recordsTotal->where(function ($query33) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$recordsTotal = $recordsTotal->count();
        	$recordsFiltered = $recordsTotal;

			$unionQueryArc = User::query();
			$unionQueryArc->select($UserSelect_column);
			$unionQueryArc->leftJoin('architect', 'architect.user_id', '=', 'users.id');
			$unionQueryArc->addSelect(DB::raw("'". route('new.architects.index') ."' as url"));
			$unionQueryArc->whereIn('users.type', array(201, 202));
			if ($isAdminOrCompanyAdmin == 1) {
			} else if ($isSalePerson == 1) {
				$unionQueryArc->whereIn('architect.sale_person_id', $SalePersonsIds);
			} else if ($isChannelPartner != 0) {
				$unionQueryArc->where('architect.added_by', Auth::user()->id);
			}
			$unionQueryArc->where(function ($query00) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query00->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query00->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$unionQueryArc->limit($request->length);
			$unionQueryArc->offset($request->start);
			$unionQueryArc->orderBy($UserSearch_column[$request['order'][0]['column']], $request['order'][0]['dir']);

			$Data = $unionQueryArc->get();
			$data = json_decode(json_encode($Data), true);
			
			$viewData = array();
			foreach ($data as $key => $value) {
				$viewData[$key] = array();
				$viewData[$key]['id'] = highlightString($value['id'],$search_value);
				$viewData[$key]['name'] = '<a href="'.$value['url'].'?id='.$value['id'].'" target="_blank">'. highlightString($value['first_name'] .' '. $value['last_name'],$search_value) .'</a>';
				$viewData[$key]['email'] = highlightString($value['email'],$search_value);
				$viewData[$key]['mobile'] = highlightString($value['phone_number'],$search_value);
				$viewData[$key]['address'] = highlightString($value['address_line1'] .' '. $value['address_line2'],$search_value);
			}
		} else {
			$recordsTotal = 0;
			$recordsFiltered = 0;
			$viewData = array();
		}

		$jsonData = [
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $viewData, 
			"count" => intval($recordsTotal)
        ];

        return response()->json($jsonData);
	}
	

	function ElectricianAjax(Request $request){

		$isSalePerson = isSalePerson();
		$isChannelPartner = isChannelPartner(Auth::user()->type);
		$isAdminOrCompanyAdmin = isAdminOrCompanyAdmin();
		$isMarketingDispatcherUser = isMarketingDispatcherUser();
		if ($isSalePerson == 1) {
			$SalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
		}

        if ($isSalePerson == 1) {

            $childSalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
        }
        $isTaleSalesUser = isTaleSalesUser();

        if ($isTaleSalesUser == 1) {
            $TeleSalesCity = TeleSalesCity(Auth::user()->id);
        }

		$UserSearch_column = array(
			'users.id',
			'users.first_name',
			'users.last_name',
			'users.email',
			'users.phone_number',
			'users.address_line1',
			'users.address_line2',
			DB::raw('CONCAT(users.first_name," ",users.last_name)'),
			DB::raw('CONCAT(users.address_line1," ",users.address_line2)'),
		);

		$UserSelect_column = array(
			0 => 'users.id',
			1 => 'users.first_name',
			2 => 'users.last_name',
			3 => 'users.email',
			4 => 'users.phone_number',
			5 => 'users.address_line1',
			6 => 'users.address_line2',
		);
		
		if (isset($request->search_value) && $request->search_value != "") {
			$search_value = $request->search_value;
			$recordsTotal = User::whereIn('users.type', array(301, 302));
			$recordsTotal->leftJoin('electrician', 'electrician.user_id', '=', 'users.id');
			if ($isAdminOrCompanyAdmin == 1 || $isMarketingDispatcherUser == 1) {
			} else if ($isSalePerson == 1) {
				$recordsTotal->whereIn('electrician.sale_person_id', $SalePersonsIds);
			} else if ($isChannelPartner != 0) {
				$recordsTotal->where('electrician.added_by', Auth::user()->id);
			}
			$recordsTotal->where(function ($query33) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$recordsTotal = $recordsTotal->count();
        	$recordsFiltered = $recordsTotal;

			$unionQueryEle = User::query();
			$unionQueryEle->select($UserSelect_column);
			$unionQueryEle->leftJoin('electrician', 'electrician.user_id', '=', 'users.id');
			$unionQueryEle->addSelect(DB::raw("'". route('new.electricians.index') ."' as url"));
			$unionQueryEle->whereIn('users.type', array(301, 302));
			if ($isAdminOrCompanyAdmin == 1 || $isMarketingDispatcherUser == 1) {
			} else if ($isSalePerson == 1) {
				$unionQueryEle->whereIn('electrician.sale_person_id', $SalePersonsIds);
			} else if ($isChannelPartner != 0) {
				$unionQueryEle->where('electrician.added_by', Auth::user()->id);
			}
			$unionQueryEle->where(function ($query00) use ($search_value, $UserSearch_column) {
				for ($i = 0; $i < count($UserSearch_column); $i++) {
					if ($i == 0) {
						$query00->where($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query00->orWhere($UserSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$unionQueryEle->limit($request->length);
			$unionQueryEle->offset($request->start);
			$unionQueryEle->orderBy($UserSearch_column[$request['order'][0]['column']], $request['order'][0]['dir']);

			$Data = $unionQueryEle->get();
			$data = json_decode(json_encode($Data), true);
			
			$viewData = array();
			foreach ($data as $key => $value) {
				$viewData[$key] = array();
				$viewData[$key]['id'] = highlightString($value['id'],$search_value);
				$viewData[$key]['name'] = '<a href="'.$value['url'].'?id='.$value['id'].'" target="_blank">'. highlightString($value['first_name'] .' '. $value['last_name'],$search_value) .'</a>';
				$viewData[$key]['email'] = highlightString($value['email'],$search_value);
				$viewData[$key]['mobile'] = highlightString($value['phone_number'],$search_value);
				$viewData[$key]['address'] = highlightString($value['address_line1'] .' '. $value['address_line2'],$search_value);
			}
		} else {
			$recordsTotal = 0;
			$recordsFiltered = 0;
			$viewData = array();
		}

		
		
        // $unionQueryEle->offset($request->start);
		


		$jsonData = [
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $viewData, 
			"count" => intval($recordsTotal)
        ];

        return response()->json($jsonData);
	}


	function LeadAjax(Request $request){

		$isSalePerson = isSalePerson();
		if ($isSalePerson == 1) {
            $childSalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
        }

		$LeadDealSelect_column = array(
			0 => 'leads.id',
			1 => 'leads.first_name',
			2 => 'leads.last_name',
			3 => 'leads.email',
			4 => 'leads.phone_number',
			5 => 'leads.addressline1 as address_line1',
			6 => 'leads.addressline2 as address_line2',
		);

		$LeadDealSearch_column = array(
			'leads.id',
			'leads.first_name',
			'leads.last_name',
			'leads.phone_number',
			'leads.addressline1',
			'leads.addressline2',
			DB::raw('CONCAT(leads.first_name," ",leads.last_name)'),
			DB::raw('CONCAT(leads.addressline1," ",leads.addressline2)'),
		);
		
		if (isset($request->search_value) && $request->search_value != "") {
			$search_value = $request->search_value;
			$recordsTotal = Lead::where('leads.is_deal', '0');
			if ($isSalePerson == 1) {
				$recordsTotal->whereIn('leads.assigned_to', $childSalePersonsIds);
			}
			$recordsTotal->where(function ($query33) use ($search_value, $LeadDealSearch_column) {
				for ($i = 0; $i < count($LeadDealSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$recordsTotal = $recordsTotal->count();
        	$recordsFiltered = $recordsTotal;

			$unionQueryLead = Lead::query();
			$unionQueryLead->select($LeadDealSelect_column);
			$unionQueryLead->addSelect(DB::raw("'". route('crm.lead') ."' as url"));
			$unionQueryLead->where('leads.is_deal', '0');
			if ($isSalePerson == 1) {
				$unionQueryLead->whereIn('leads.assigned_to', $childSalePersonsIds);
			}
			if(isset($request->search_value)){
				$search_value = $request->search_value;
			} else if($request['search']['value']) {
				$search_value = $request['search']['value'];
			}
			$unionQueryLead->where(function ($query33) use ($search_value, $LeadDealSearch_column) {
				for ($i = 0; $i < count($LeadDealSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$unionQueryLead->limit($request->length);
			$unionQueryLead->offset($request->start);
			$unionQueryLead->orderBy($LeadDealSearch_column[$request['order'][0]['column']], $request['order'][0]['dir']);
			$Data = $unionQueryLead->get();
			$data = json_decode(json_encode($Data), true);
			
			$viewData = array();
			foreach ($data as $key => $value) {
				$viewData[$key] = array();
				$viewData[$key]['id'] = highlightString($value['id'],$search_value);
				$viewData[$key]['name'] = '<a href="'.$value['url'].'?id='.$value['id'].'" target="_blank">'. highlightString($value['first_name'] .' '. $value['last_name'],$search_value) .'</a>';
				$viewData[$key]['email'] = highlightString($value['email'],$search_value);
				$viewData[$key]['mobile'] = highlightString($value['phone_number'],$search_value);
				$viewData[$key]['address'] = highlightString($value['address_line1'] .' '. $value['address_line2'],$search_value);
			}
		} else {
			$recordsTotal = 0;
			$recordsFiltered = 0;
			$viewData = array();
		}

		
        // $unionQueryLead->offset($request->start);
		


		$jsonData = [
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $viewData, 
			"count" => intval($recordsTotal)
        ];

        return response()->json($jsonData);
	}


	function DealAjax(Request $request){
		$isSalePerson = isSalePerson();
		if ($isSalePerson == 1) {
            $childSalePersonsIds = getChildSalePersonsIds(Auth::user()->id);
        }

		$LeadDealSelect_column = array(
			0 => 'leads.id',
			1 => 'leads.first_name',
			2 => 'leads.last_name',
			3 => 'leads.email',
			4 => 'leads.phone_number',
			5 => 'leads.addressline1 as address_line1',
			6 => 'leads.addressline2 as address_line2',
		);

		$LeadDealSearch_column = array(
			'leads.id',
			'leads.first_name',
			'leads.last_name',
			'leads.phone_number',
			'leads.addressline1',
			'leads.addressline2',
			DB::raw('CONCAT(leads.first_name," ",leads.last_name)'),
			DB::raw('CONCAT(leads.addressline1," ",leads.addressline2)'),
		);

		if (isset($request->search_value) && $request->search_value != "") {
			$search_value = $request->search_value;
			$recordsTotal = Lead::where('leads.is_deal', '1');
			if ($isSalePerson == 1) {
				$recordsTotal->whereIn('leads.assigned_to', $childSalePersonsIds);
			}
			$recordsTotal->where(function ($query33) use ($search_value, $LeadDealSearch_column) {
				for ($i = 0; $i < count($LeadDealSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			$recordsTotal = $recordsTotal->count();
        	$recordsFiltered = $recordsTotal;

			$unionQueryDeal = Lead::query();
			$unionQueryDeal->select($LeadDealSelect_column);
			$unionQueryDeal->addSelect(DB::raw("'". route('crm.deal') ."' as url"));
			$unionQueryDeal->where('leads.is_deal', '1');
			if ($isSalePerson == 1) {
				$unionQueryDeal->whereIn('leads.assigned_to', $childSalePersonsIds);
			}
			$unionQueryDeal->where(function ($query33) use ($search_value, $LeadDealSearch_column) {
				for ($i = 0; $i < count($LeadDealSearch_column); $i++) {
					if ($i == 0) {
						$query33->where($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					} else {
						$query33->orWhere($LeadDealSearch_column[$i], 'like', "%" . $search_value . "%");
					}
				}
			});
			
			$unionQueryDeal->limit($request->length);
			$unionQueryDeal->offset($request->start);
			$unionQueryDeal->orderBy($LeadDealSearch_column[$request['order'][0]['column']], $request['order'][0]['dir']);
			$Data = $unionQueryDeal->get();
			$data = json_decode(json_encode($Data), true);
			
			$viewData = array();
			foreach ($data as $key => $value) {
				$viewData[$key] = array();
				$viewData[$key]['id'] = highlightString($value['id'],$search_value);
				$viewData[$key]['name'] = '<a href="'.$value['url'].'?id='.$value['id'].'" target="_blank">'. highlightString($value['first_name'] .' '. $value['last_name'],$search_value) .'</a>';
				$viewData[$key]['email'] = highlightString($value['email'],$search_value);
				$viewData[$key]['mobile'] = highlightString($value['phone_number'],$search_value);
				$viewData[$key]['address'] = highlightString($value['address_line1'] .' '. $value['address_line2'],$search_value);
			}
		} else {
			$recordsTotal = 0;
			$recordsFiltered = 0;
			$viewData = array();
		}

		
        // $unionQueryLead->offset($request->start);
		


		$jsonData = [
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $viewData, 
			"count" => intval($recordsTotal)
        ];

        return response()->json($jsonData);
	}
}