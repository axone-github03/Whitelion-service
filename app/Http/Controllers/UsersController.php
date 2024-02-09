<?php

namespace App\Http\Controllers;

use App\Models\CityList;
use App\Models\Company;
use App\Models\Parameter;
use App\Models\PurchaseHierarchy;
use App\Models\PurchasePerson;
use App\Models\SalePerson;
use App\Models\SalesHierarchy;
use App\Models\ServiceHierarchy;
use App\Models\StateList;
use App\Models\TeleSales;
use App\Models\User;
use App\Models\Wlmst_ServiceExecutive;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;

//use Session;

class UsersController extends Controller
{

	public function salesReportingManager(Request $request)
	{

		if ($request->sale_person_type != "") {

			$SalesHierarchy = array();
			$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
			$SalesHierarchy->where('status', 1);
			$SalesHierarchy->where('id', $request->sale_person_type);
			$SalesHierarchy = $SalesHierarchy->get();

			$SalesHierarchyId = array();
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = SalesHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			$SalesHierarchyId = array_unique($SalesHierarchyId);
			$SalesHierarchyId = array_values($SalesHierarchyId);

			$q = $request->q;

			$query = DB::table('sale_person');
			$query->leftJoin('users', 'sale_person.user_id', '=', 'users.id');
			$query->leftJoin('sales_hierarchy', 'sales_hierarchy.id', '=', 'sale_person.type');
			$query->select('users.id as id', 'sale_person.type', 'sales_hierarchy.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
			$query->whereIn('sale_person.type', $SalesHierarchyId);
			$query->where('users.type', 2);
			$query->where('users.status', 1);
			// $query->where('users.company_id', $request->user_company_id);
			$query->where('users.reference_id', '!=', 0);
			$query->where('users.id', '!=', $request->user_id);
			$query->where(function ($query) use ($q) {
				$query->where('users.first_name', 'like', '%' . $q . '%');
				$query->orWhere('users.last_name', 'like', '%' . $q . '%');
			});

			$query->limit(5);
			$data = $query->get();

			$data = json_decode(json_encode($data), true);

			foreach ($data as $key => $value) {

				$data[$key]['id'] = "u-" . $value['id'];
				$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
				unset($data[$key]['code']);
			}

			$Company = array();
			$Company = Company::select('id', 'name as text');
			$Company->where(function ($query) use ($q) {
				$query->where('name', 'like', '%' . $q . '%');
			});
			$Company = $Company->first();

			if ($Company) {

				$countData = count($data);
				$data[$countData]['id'] = "c-" . $Company['id'];
				$data[$countData]['text'] = $Company->text . " (COMPANY)";
			}

			$response = array();
			$response['results'] = $data;
		} else {
			$response = array();
			$response['results'] = array();
		}

		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function purchaseReportingManager(Request $request)
	{

		if ($request->purchase_person_type != "") {

			$SalesHierarchy = array();
			$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
			$SalesHierarchy->where('status', 1);
			$SalesHierarchy->where('id', $request->purchase_person_type);
			$SalesHierarchy = $SalesHierarchy->get();

			$SalesHierarchyId = array();
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$SalesHierarchy = array();
				$SalesHierarchy = PurchaseHierarchy::select('id', 'parent_id');
				$SalesHierarchy->where('status', 1);
				$SalesHierarchy->whereIn('id', $parentIds);
				$SalesHierarchy = $SalesHierarchy->get();
			}
			/// Repeat Code end

			$parentIds = array();
			foreach ($SalesHierarchy as $key => $value) {
				$SalesHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			$SalesHierarchyId = array_unique($SalesHierarchyId);
			$SalesHierarchyId = array_values($SalesHierarchyId);

			$q = $request->q;

			$query = DB::table('purchase_person');
			$query->leftJoin('users', 'purchase_person.user_id', '=', 'users.id');
			$query->leftJoin('purchase_hierarchy', 'purchase_hierarchy.id', '=', 'purchase_person.type');
			$query->select('users.id as id', 'purchase_person.type', 'purchase_hierarchy.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
			$query->whereIn('purchase_person.type', $SalesHierarchyId);
			$query->where('users.type', 10);
			$query->where('users.status', 1);
			// $query->where('users.company_id', $request->user_company_id);
			$query->where('users.reference_id', '!=', 0);
			$query->where('users.id', '!=', $request->user_id);
			$query->where(function ($query) use ($q) {
				$query->where('users.first_name', 'like', '%' . $q . '%');
				$query->orWhere('users.last_name', 'like', '%' . $q . '%');
			});

			$query->limit(5);
			$data = $query->get();

			$data = json_decode(json_encode($data), true);

			foreach ($data as $key => $value) {

				$data[$key]['id'] = "u-" . $value['id'];
				$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
				unset($data[$key]['code']);
			}

			$Company = array();
			$Company = Company::select('id', 'name as text');
			$Company->where(function ($query) use ($q) {
				$query->where('name', 'like', '%' . $q . '%');
			});
			$Company = $Company->first();

			if ($Company) {

				$countData = count($data);
				$data[$countData]['id'] = "c-" . $Company['id'];
				$data[$countData]['text'] = $Company->text . " (COMPANY)";
			}

			$response = array();
			$response['results'] = $data;
		} else {
			$response = array();
			$response['results'] = array();
		}

		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	function searchStateCities(Request $request)
	{

		$CityList = array();
		$CityList = CityList::select('id', 'name as text');
		//$CityList->where('country_id', $request->country_id);
		$CityList->whereIn('state_id', explode(",", $request->sale_person_state));
		$CityList->where('name', 'like', "%" . $request->q . "%");
		$CityList->where('status', 1);
		$CityList->limit(5);
		$CityList = $CityList->get();

		$response = array();
		$response['results'] = $CityList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function stateCities(Request $request)
	{

		$CityList = array();
		$CityList = CityList::select('id', 'name as text');
		$CityList->whereIn('state_id', explode(",", $request->state_ids));
		$CityList->orderByRaw('FIELD (state_id, ' . $request->state_ids . ') ASC');
		$CityList->where('status', 1);
		$CityList = $CityList->get();
		// $CityArray = array();
		// foreach ($CityList as $key => $value) {
		// 	$newarr['data']['id'] = $value->id;
		// 	$newarr['data']['text'] = $value->text;
		// 	array_push($CityArray,$newarr);
		// }
		$response = array();
		$response['data'] = $CityList;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchSalePersonType(Request $request)
	{

		$SalesHierarchy = array();
		$SalesHierarchy = SalesHierarchy::select('id', 'name as text');
		$SalesHierarchy->where('status', 1);
		$SalesHierarchy->where('name', 'like', "%" . $request->q . "%");
		$SalesHierarchy->limit(5);
		$SalesHierarchy = $SalesHierarchy->get();

		$response = array();
		$response['results'] = $SalesHierarchy;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchPurchasePersonType(Request $request)
	{

		$SalesHierarchy = array();
		$SalesHierarchy = PurchaseHierarchy::select('id', 'name as text');
		$SalesHierarchy->where('status', 1);
		$SalesHierarchy->where('name', 'like', "%" . $request->q . "%");
		$SalesHierarchy->limit(5);
		$SalesHierarchy = $SalesHierarchy->get();

		$response = array();
		$response['results'] = $SalesHierarchy;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchState(Request $request)
	{

		$StateList = array();
		$StateList = StateList::select('id', 'name as text');
		$StateList->where('country_id', $request->country_id);

		$StateList->where('name', 'like', "%" . $request->q . "%");

		$StateList->limit(5);
		$StateList = $StateList->get();

		$response = array();
		$response['results'] = $StateList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchCity(Request $request)
	{

		$CityList = array();
		$CityList = CityList::select('id', 'name as text');
		$CityList->where('country_id', $request->country_id);
		$CityList->where('state_id', $request->state_id);
		$CityList->where('name', 'like', "%" . $request->q . "%");
		$CityList->where('status', 1);
		$CityList->limit(5);
		$CityList = $CityList->get();

		$response = array();
		$response['results'] = $CityList;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchCompany(Request $request)
	{

		$Company = array();
		$Company = Company::select('id', 'name as text');
		$Company->where('name', 'like', "%" . $request->q . "%");
		$Company->where('status', 1);
		$Company->limit(5);
		$Company = $Company->get();
		$response = array();
		$response['results'] = $Company;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function save(Request $request)
	{

		if (userHasAcccess($request->user_type)) {

			if ($request->user_type == 0) {

				// ADMIN

				$user_address_line2 = isset($request->user_address_line2) ? $request->user_address_line2 : '';
				$user_ctc = isset($request->user_ctc) ? $request->user_ctc : 0;

				$rules = array(
					'user_id' => ['required'],
					'user_first_name' => ['required'],
					'user_last_name' => ['required'],
					'user_email' => ['required'],
					'user_phone_number' => ['required'],
					'user_address_line1' => ['required'],
					'user_pincode' => ['required'],
					'user_country_id' => ['required'],
					'user_state_id' => ['required'],
					'user_city_id' => ['required'],

				);

				$customMessage = array(
					'user_id.required' => "Invalid parameters",
					'user_first_name.required' => "Please enter first name",
					'user_last_name.required' => "Please enter last name",
					'user_email.required' => "Please enter email",
					'user_phone_number.required' => "Please enter phone number",
					'user_address_line1.required' => "Please enter addressline1",
					'user_pincode.required' => "Please enter pincode",
					'user_country_id.required' => "Please select country",
					'user_state_id.required' => "Please select state",
					'user_city_id.required' => "Please select city",

				);

				$validator = Validator::make($request->all(), $rules, $customMessage);

				if ($validator->fails()) {

					$response = array();
					$response['status'] = 0;
					$response['msg'] = "The request could not be understood by the server due to malformed syntax";
					$response['statuscode'] = 400;
					$response['data'] = $validator->errors();
				} else {

					$AllUserTypes = getAllUserTypes();

					$alreadyEmail = User::query();
					$alreadyEmail->where('email', $request->user_email);
					$alreadyEmail->where('type', '!=', 10000);
					if ($request->user_id != 0) {
						$alreadyEmail->where('id', '!=', $request->user_id);
					}
					$alreadyEmail = $alreadyEmail->first();

					$alreadyPhoneNumber = User::query();
					$alreadyPhoneNumber->where('type', '!=', 10000);
					$alreadyPhoneNumber->where('phone_number', $request->user_phone_number);
					if ($request->user_id != 0) {
						$alreadyPhoneNumber->where('id', '!=', $request->user_id);
					}
					$alreadyPhoneNumber = $alreadyPhoneNumber->first();

					if ($alreadyEmail) {
						$response = errorRes("Email already exists(" . $AllUserTypes[$alreadyEmail->type]['name'] . "), Try with another email");
					} else if ($alreadyPhoneNumber) {
						$response = errorRes("Phone number already exists(" . $AllUserTypes[$alreadyPhoneNumber->type]['name'] . "), Try with another phone number");
					} else {

						if ($request->user_id == 0) {

							$User = User::where('type', 10000)->where(function ($query) use ($request) {
								$query->where('email', $request->user_email)->orWhere('phone_number', $request->user_phone_number);
							})->first();

							if ($User) {
								$User->type = 0;
								$User->reference_type = getUserTypes()[$User->type]['lable'];
								$User->reference_id = 0;
							} else {
								$User = new User();
								$User->created_by = Auth::user()->id;
								$User->password = Hash::make("111111");
								$User->last_active_date_time = date('Y-m-d H:i:s');
								$User->last_login_date_time = date('Y-m-d H:i:s');
								$User->avatar = "default.png";
								$User->type = 0;
								$User->company_id = 1;
								$User->reference_type = getUserTypes()[$User->type]['lable'];
								$User->reference_id = 0;
							}
						} else {
							$User = User::find($request->user_id);
						}

						$User->first_name = $request->user_first_name;
						$User->last_name = $request->user_last_name;
						$User->email = $request->user_email;
						$User->dialing_code = "+91";
						$User->phone_number = $request->user_phone_number;
						$User->ctc = $user_ctc;
						$User->address_line1 = $request->user_address_line1;
						$User->address_line2 = $user_address_line2;
						$User->pincode = $request->user_pincode;
						$User->country_id = $request->user_country_id;
						$User->state_id = $request->user_state_id;
						$User->city_id = $request->user_city_id;
						$User->status = $request->user_status;
						if (isset($request->user_joining_date) && $request->user_joining_date != "") {
							$joining_date_time = $request->user_joining_date . " " . date('H:i:s');
							$joining_date_time = date('Y-m-d H:i:s', strtotime($joining_date_time));

							$User->joining_date = $joining_date_time;
						}
						$User->save();
						$debugLog = array();
						if ($request->user_id != 0) {
							$debugLog['name'] = "user-edit";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been updated ";
							$response = successRes("Successfully saved user");
						} else {
							$debugLog['name'] = "user-add";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been added ";
							$response = successRes("Successfully added user");
						}
						saveDebugLog($debugLog);
					}

					return response()->json($response)->header('Content-Type', 'application/json');
				}

				/// END ADMIN

			} else if ($request->user_type == 1) {

				// START COMPANY ADMIN

				$user_address_line2 = isset($request->user_address_line2) ? $request->user_address_line2 : '';
				$user_ctc = isset($request->user_ctc) ? $request->user_ctc : 0;

				$rules = array(
					'user_id' => ['required'],
					'user_first_name' => ['required'],
					'user_last_name' => ['required'],
					'user_email' => ['required'],
					'user_phone_number' => ['required'],
					'user_address_line1' => ['required'],
					'user_pincode' => ['required'],
					'user_country_id' => ['required'],
					'user_state_id' => ['required'],
					'user_city_id' => ['required'],

				);

				$customMessage = array(
					'user_id.required' => "Invalid parameters",
					'user_first_name.required' => "Please enter first name",
					'user_last_name.required' => "Please enter last name",
					'user_email.required' => "Please enter email",
					'user_phone_number.required' => "Please enter phone number",
					'user_address_line1.required' => "Please enter addressline1",
					'user_pincode.required' => "Please enter pincode",
					'user_country_id.required' => "Please select country",
					'user_state_id.required' => "Please select state",
					'user_city_id.required' => "Please select city",

				);

				$validator = Validator::make($request->all(), $rules, $customMessage);

				if ($validator->fails()) {

					$response = array();
					$response['status'] = 0;
					$response['msg'] = "The request could not be understood by the server due to malformed syntax";
					$response['statuscode'] = 400;
					$response['data'] = $validator->errors();
				} else {

					$AllUserTypes = getAllUserTypes();

					$alreadyEmail = User::query();
					$alreadyEmail->where('email', $request->user_email);
					$alreadyEmail->where('type', '!=', 10000);

					if ($request->user_id != 0) {
						$alreadyEmail->where('id', '!=', $request->user_id);
					}
					$alreadyEmail = $alreadyEmail->first();

					$alreadyPhoneNumber = User::query();
					$alreadyPhoneNumber->where('phone_number', $request->user_phone_number);
					$alreadyPhoneNumber->where('type', '!=', 10000);

					if ($request->user_id != 0) {
						$alreadyPhoneNumber->where('id', '!=', $request->user_id);
					}
					$alreadyPhoneNumber = $alreadyPhoneNumber->first();

					if ($alreadyEmail) {

						$response = errorRes("Email already exists(" . $AllUserTypes[$alreadyEmail->type]['name'] . "), Try with another email");
					} else if ($alreadyPhoneNumber) {
						$response = errorRes("Phone number already exists(" . $AllUserTypes[$alreadyPhoneNumber->type]['name'] . "), Try with another phone number");
					} else {

						if ($request->user_id == 0) {

							$User = User::where('type', 10000)->where(function ($query) use ($request) {
								$query->where('email', $request->user_email)->orWhere('phone_number', $request->user_phone_number);
							})->first();

							if ($User) {
								$User->type = 1;
								$User->reference_type = getUserTypes()[$User->type]['lable'];
								$User->reference_id = 0;
							} else {

								$User = new User();
								$User->created_by = Auth::user()->id;
								$User->password = Hash::make("111111");
								$User->last_active_date_time = date('Y-m-d H:i:s');
								$User->last_login_date_time = date('Y-m-d H:i:s');
								$User->avatar = "default.png";
								$User->company_id = 1;
								$User->type = 1;
								$User->reference_type = getUserTypes()[$User->type]['lable'];
								$User->reference_id = 0;
							}
						} else {
							$User = User::find($request->user_id);
						}
						$User->first_name = $request->user_first_name;
						$User->last_name = $request->user_last_name;
						$User->email = $request->user_email;
						$User->dialing_code = "+91";
						$User->phone_number = $request->user_phone_number;
						$User->ctc = $user_ctc;
						$User->address_line1 = $request->user_address_line1;
						$User->address_line2 = $user_address_line2;
						$User->pincode = $request->user_pincode;
						$User->country_id = $request->user_country_id;
						$User->state_id = $request->user_state_id;
						$User->city_id = $request->user_city_id;
						$User->status = $request->user_status;
						if (isset($request->user_joining_date) && $request->user_joining_date != "") {
							$joining_date_time = $request->user_joining_date . " " . date('H:i:s');
							$joining_date_time = date('Y-m-d H:i:s', strtotime($joining_date_time));

							$User->joining_date = $joining_date_time;
						}
						$User->save();

						$debugLog = array();
						if ($request->user_id != 0) {
							$debugLog['name'] = "user-edit";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been updated ";
							$response = successRes("Successfully saved user");
						} else {
							$debugLog['name'] = "user-add";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been added ";
							$response = successRes("Successfully added user");
						}
						saveDebugLog($debugLog);
					}

					return response()->json($response)->header('Content-Type', 'application/json');
				}

				// END COMPANY ADMIN

			} else if ($request->user_type == 11) {

				// START SERVICE EXECUTIVE DEPARTMENT

				$user_ctc = isset($request->user_ctc) ? $request->user_ctc : 0;

				$user_address_line2 = isset($request->user_address_line2) ? $request->user_address_line2 : '';

				$rules = array(
					'user_id' => ['required'],
					'user_first_name' => ['required'],
					'user_last_name' => ['required'],
					'user_email' => ['required'],
					'user_phone_number' => ['required'],
					'user_address_line1' => ['required'],
					'user_pincode' => ['required'],
					'user_country_id' => ['required'],
					'user_state_id' => ['required'],
					'user_city_id' => ['required'],
					'service_executive_type' => ['required'],
					'service_executive_state' => ['required'],
					'service_executive_city' => ['required'],
					'service_executive_reporting_manager' => ['required'],

				);

				$customMessage = array(
					'user_id.required' => "Invalid parameters",
					'user_first_name.required' => "Please enter first name",
					'user_last_name.required' => "Please enter last name",
					'user_email.required' => "Please enter email",
					'user_phone_number.required' => "Please enter phone number",
					'user_address_line1.required' => "Please enter addressline1",
					'user_pincode.required' => "Please enter pincode",
					'user_country_id.required' => "Please select country",
					'user_state_id.required' => "Please select state",
					'user_city_id.required' => "Please select city",
					'service_executive_type.required' => "Please select service executive type",
					'service_executive_state.required' => "Please select service executive state",
					'service_executive_city.required' => "Please select service executive city",
					'service_executive_reporting_manager.required' => "Please select reporting manager",
				);

				$validator = Validator::make($request->all(), $rules, $customMessage);

				if ($validator->fails()) {

					$response = array();
					$response['status'] = 0;
					$response['msg'] = "The request could not be understood by the server due to malformed syntax";
					$response['statuscode'] = 400;
					$response['data'] = $validator->errors();
				} else {

					$previousStatus = 1;

					$service_executive_reporting = explode("-", $request->service_executive_reporting_manager);

					if ($service_executive_reporting[0] == "c") {

						$reporting_company_id = 1;
						$reporting_manager_id = 0;
					} else {

						$ServiceExecutive = Wlmst_ServiceExecutive::select('user_id', 'reporting_company_id')->where('user_id', $service_executive_reporting[1])->first();
						$reporting_company_id = $ServiceExecutive->reporting_company_id;
						$reporting_manager_id = $ServiceExecutive->user_id;
					}

					$AllUserTypes = getAllUserTypes();

					$alreadyEmail = User::query();
					$alreadyEmail->where('email', $request->user_email);
					$alreadyEmail->where('status', '=', 1);

					if ($request->user_id != 0) {
						$alreadyEmail->where('id', '!=', $request->user_id);
					}
					$alreadyEmail = $alreadyEmail->first();

					$alreadyPhoneNumber = User::query();
					$alreadyPhoneNumber->where('phone_number', $request->user_phone_number);
					$alreadyPhoneNumber->where('status', '=', 1);

					if ($request->user_id != 0) {
						$alreadyPhoneNumber->where('id', '!=', $request->user_id);
					}
					$alreadyPhoneNumber = $alreadyPhoneNumber->first();

					if ($alreadyEmail) {

						$response = errorRes("Email already exists(" . $AllUserTypes[$alreadyEmail->type]['name'] . "), Try with another email");
					} else if ($alreadyPhoneNumber) {
						$response = errorRes("Phone number already exists(" . $AllUserTypes[$alreadyPhoneNumber->type]['name'] . "), Try with another phone number");
					} else {

						if ($request->user_id == 0) {
							$User = new User();
							$User->created_by = Auth::user()->id;
							$User->password = Hash::make("111111");
							$User->last_active_date_time = date('Y-m-d H:i:s');
							$User->last_login_date_time = date('Y-m-d H:i:s');
							$User->avatar = "default.png";
							$User->company_id = $reporting_company_id;
							$User->type = 11;
							$User->reference_type = getUserTypes()[$User->type]['lable'];
							$ServiceExecutive = new Wlmst_ServiceExecutive();
							$ServiceExecutive->reporting_company_id = $reporting_company_id;
						} else {
							$User = User::find($request->user_id);
							$previousStatus = $User->status;
							$ServiceExecutive = Wlmst_ServiceExecutive::find($User->reference_id);
							if (!$ServiceExecutive) {
								$response = errorRes("Something went wrong");
								return response()->json($response)->header('Content-Type', 'application/json');
							}
						}

						$User->first_name = $request->user_first_name;
						$User->last_name = $request->user_last_name;
						$User->email = $request->user_email;
						$User->dialing_code = "+91";
						$User->phone_number = $request->user_phone_number;
						$User->ctc = $user_ctc;
						$User->address_line1 = $request->user_address_line1;
						$User->address_line2 = $user_address_line2;
						$User->pincode = $request->user_pincode;
						$User->country_id = $request->user_country_id;
						$User->state_id = $request->user_state_id;
						$User->city_id = $request->user_city_id;
						$User->status = $request->user_status;
						if (isset($request->user_joining_date) && $request->user_joining_date != "") {
							$joining_date_time = $request->user_joining_date . " " . date('H:i:s');
							$joining_date_time = date('Y-m-d H:i:s', strtotime($joining_date_time));

							$User->joining_date = $joining_date_time;
						}
						$User->save();

						if ($request->user_id == 0) {

							$ServiceExecutive->user_id = $User->id;
						}

						$ServiceExecutive->type = $request->service_executive_type;
						$ServiceExecutive->reporting_manager_id = $reporting_manager_id;
						$ServiceExecutive->states = implode(",", $request->service_executive_state);
						$ServiceExecutive->cities = implode(",", $request->service_executive_city);
						$ServiceExecutive->save();

						if ($request->user_id == 0) {

							$User->reference_id = $ServiceExecutive->id;
							$User->save();
						}

						$currentStatus = $User->status;

						$debugLog = array();
						if ($request->user_id != 0) {
							$debugLog['name'] = "user-edit";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been updated ";
							$response = successRes("Successfully saved user");
						} else {
							$debugLog['name'] = "user-add";
							$debugLog['description'] = "user #" . $User->id . "(" . $User->first_name . " " . $User->last_name . ") has been added ";
							$response = successRes("Successfully added user");
						}
						saveDebugLog($debugLog);

						if ($previousStatus == 1 && $currentStatus == 0) {

							$params = array();
							// $params['bcc_email'] = "ankitsardhara4@gmail.com";
							// $params['to_email'] = "ankitsardhara4@gmail.com";

							$params['to_email'] = $User->email;
							$bccEmailUserIds = array();

							$bccEmailUserIds = getParentServiceExecutivesIds($ServiceExecutive->user_id);

							$bccEmailUserIds = array_unique($bccEmailUserIds);
							$bccEmailUserIds = array_values($bccEmailUserIds);

							$bccEmailUser = User::select('email')->where('id', $bccEmailUserIds)->get();

							foreach ($bccEmailUser as $keyBE => $valueBE) {

								$bccEmails[] = $valueBE->email;
							}


							$designation = "";
							$ServiceHierarchyType = ServiceHierarchy::find($ServiceExecutive->type);
							if ($ServiceHierarchyType) {
								$designation = $ServiceHierarchyType->code;
							}
							$reportingManager = "Whitelion";
							if ($ServiceExecutive->reporting_manager_id) {
								$reportingManagerUser = User::select('first_name', 'last_name')->find($ServiceExecutive->reporting_manager_id);
								if ($reportingManagerUser) {
									$reportingManager = $reportingManagerUser->first_name . " " . $reportingManagerUser->last_name;
								}
							}

							$params['bcc_email'] = $bccEmails;
							$params['first_name'] = $User->first_name;
							$params['last_name'] = $User->last_name;
							$params['city_name'] = getCityName($User->city_id);
							$params['designation'] = $designation;
							$params['reporting_manager'] = $reportingManager;
							$configrationForNotify = configrationForNotify();
							$params['from_name'] = $configrationForNotify['from_name'];
							$params['from_email'] = $configrationForNotify['from_email'];
							$params['to_name'] = $configrationForNotify['to_name'];
							$params['subject'] = "Updates: This team member is no longer working with our Whitelion.";
							$response['debug'] = $params;

							if (Config::get('app.env') == "local") {
								$params['to_email'] = $configrationForNotify['test_email'];
							}

							Mail::send('emails.service_person_deactive', ['params' => $params], function ($m) use ($params) {
								$m->from($params['from_email'], $params['from_name']);
								$m->bcc($params['bcc_email']);
								$m->to($params['to_email'], $params['to_name'])->subject($params['subject']);
							});
						}

						if ($request->user_id == 0) {

							$params = array();
							// $params['bcc_email'] = "ankitsardhara4@gmail.com";
							// $params['to_email'] = "ankitsardhara4@gmail.com";

							$params['to_email'] = $User->email;
							$bccEmailUserIds = array();

							$bccEmailUserIds = getParentServiceExecutivesIds($ServiceExecutive->user_id);

							$bccEmailUserIds = array_unique($bccEmailUserIds);
							$bccEmailUserIds = array_values($bccEmailUserIds);

							$bccEmailUser = User::select('email')->where('id', $bccEmailUserIds)->get();

							foreach ($bccEmailUser as $keyBE => $valueBE) {

								$bccEmails[] = $valueBE->email;
							}


							$designation = "";
							$ServiceHierarchyType = ServiceHierarchy::find($ServiceExecutive->type);
							if ($ServiceHierarchyType) {
								$designation = $ServiceHierarchyType->code;
							}
							$reportingManager = "Whitelion";
							if ($ServiceExecutive->reporting_manager_id) {
								$reportingManagerUser = User::select('first_name', 'last_name')->find($ServiceExecutive->reporting_manager_id);
								if ($reportingManagerUser) {
									$reportingManager = $reportingManagerUser->first_name . " " . $reportingManagerUser->last_name;
								}
							}

							$params['bcc_email'] = $bccEmails;
							$params['first_name'] = $User->first_name;
							$params['last_name'] = $User->last_name;
							$params['city_name'] = getCityName($User->city_id);
							$params['designation'] = $designation;
							$params['reporting_manager'] = $reportingManager;
							$configrationForNotify = configrationForNotify();
							$params['from_name'] = $configrationForNotify['from_name'];
							$params['from_email'] = $configrationForNotify['from_email'];
							$params['to_name'] = $configrationForNotify['to_name'];
							$params['subject'] = "Updates: Service User Account Open.";
							$response['debug'] = $params;

							if (Config::get('app.env') == "local") {
								$params['to_email'] = $configrationForNotify['test_email'];
							}

							Mail::send('emails.service_person_active', ['params' => $params], function ($m) use ($params) {
								$m->from($params['from_email'], $params['from_name']);
								$m->bcc($params['bcc_email']);
								$m->to($params['to_email'], $params['to_name'])->subject($params['subject']);
							});
						}
					}

					return response()->json($response)->header('Content-Type', 'application/json');
				}
			}
		} else {

			$response = errorRes("Invalid user access", 402);
		}

		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function detail(Request $request)
	{

		$User = User::with(array('country' => function ($query) {
			$query->select('id', 'name');
		}, 'state' => function ($query) {
			$query->select('id', 'name');
		}, 'city' => function ($query) {
			$query->select('id', 'name');
		}, 'company' => function ($query) {
			$query->select('id', 'name');
		}))->find($request->id);
		if ($User) {

			if (userHasAcccess($User->type)) {
				$User['joining_date'] = date('Y-m-d', strtotime($User->joining_date));

				if ($User->type == 2) {

					$User['sale_person'] = SalePerson::select('type', 'reporting_manager_id', 'reporting_company_id', 'states', 'cities')->with(array('type' => function ($query) {
						$query->select('id', 'name');
					}))->find($User->reference_id);

					if ($User['sale_person']) {

						if ($User['sale_person']['reporting_manager_id'] != 0) {

							$query = DB::table('sale_person');
							$query->leftJoin('users', 'sale_person.user_id', '=', 'users.id');
							$query->leftJoin('sales_hierarchy', 'sales_hierarchy.id', '=', 'sale_person.type');
							$query->select('users.id as id', 'sale_person.type', 'sales_hierarchy.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
							$query->where('users.id', $User['sale_person']['reporting_manager_id']);
							$query->limit(1);
							$data = $query->get();

							$data = json_decode(json_encode($data), true);

							foreach ($data as $key => $value) {

								$data[$key]['id'] = "u-" . $value['id'];
								$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
							}

							$User['sale_person']['reporting_manager'] = $data[0];
						} else {

							$Company = array();
							$Company = Company::select('id', 'name as text');
							// $Company->where(function ($query) use ($q) {
							// 	$query->where('name', 'like', '%' . $q . '%');

							// });
							$Company->where('id', $User['sale_person']['reporting_company_id']);
							$Company = $Company->first();

							$data = array();

							if ($Company) {

								$countData = count($data);
								$data[$countData]['id'] = "c-" . $Company['id'];
								$data[$countData]['text'] = $Company->text . " (COMPANY)";
							}

							$User['sale_person']['reporting_manager'] = $data[0];
						}

						$User['sale_person']['states'] = StateList::select('id', 'name as text')->whereIn('id', explode(",", $User['sale_person']->states))->get();

						$User['sale_person']['cities'] = CityList::select('id', 'name as text')->whereIn('id', explode(",", $User['sale_person']->cities))->get();
					}
				} else if ($User->type == 10) {

					$User['purchase_person'] = PurchasePerson::select('type', 'reporting_manager_id', 'reporting_company_id', 'states', 'cities')->with(array('type' => function ($query) {
						$query->select('id', 'name');
					}))->find($User->reference_id);

					if ($User['purchase_person']) {

						if ($User['purchase_person']['reporting_manager_id'] != 0) {

							$query = DB::table('purchase_person');
							$query->leftJoin('users', 'purchase_person.user_id', '=', 'users.id');
							$query->leftJoin('purchase_hierarchy', 'purchase_hierarchy.id', '=', 'purchase_person.type');
							$query->select('users.id as id', 'purchase_person.type', 'purchase_hierarchy.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
							$query->where('users.id', $User['purchase_person']['reporting_manager_id']);
							$query->limit(1);
							$data = $query->get();

							$data = json_decode(json_encode($data), true);

							foreach ($data as $key => $value) {

								$data[$key]['id'] = "u-" . $value['id'];
								$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
							}

							$User['purchase_person']['reporting_manager'] = $data[0];
						} else {

							$Company = array();
							$Company = Company::select('id', 'name as text');
							// $Company->where(function ($query) use ($q) {
							// 	$query->where('name', 'like', '%' . $q . '%');

							// });
							$Company->where('id', $User['purchase_person']['reporting_company_id']);
							$Company = $Company->first();

							$data = array();

							if ($Company) {

								$countData = count($data);
								$data[$countData]['id'] = "c-" . $Company['id'];
								$data[$countData]['text'] = $Company->text . " (COMPANY)";
							}

							$User['purchase_person']['reporting_manager'] = $data[0];
						}

						$User['purchase_person']['states'] = StateList::select('id', 'name as text')->whereIn('id', explode(",", $User['purchase_person']->states))->get();
						$User['purchase_person']['cities'] = CityList::select('id', 'name as text')->whereIn('id', explode(",", $User['purchase_person']->cities))->get();
					}
				} else if ($User->type == 9) {

					$User['tele_sales'] = TeleSales::select('states', 'cities')->find($User->reference_id);

					if ($User['tele_sales']) {

						$User['tele_sales']['states'] = StateList::select('id', 'name as text')->whereIn('id', explode(",", $User['tele_sales']->states))->get();

						$User['tele_sales']['cities'] = CityList::select('id', 'name as text')->whereIn('id', explode(",", $User['tele_sales']->cities))->get();
					}
				} else if ($User->type == 11) {

					$User['service_person'] = Wlmst_ServiceExecutive::select('type', 'reporting_manager_id', 'reporting_company_id', 'states', 'cities')->with(array('type' => function ($query) {
						$query->select('id', 'name');
					}))->find($User->reference_id);

					if ($User['service_person']) {

						if ($User['service_person']['reporting_manager_id'] != 0) {

							$query = DB::table('wlmst_service_user');
							$query->leftJoin('users', 'wlmst_service_user.user_id', '=', 'users.id');
							$query->leftJoin('service_hierarchies', 'service_hierarchies.id', '=', 'wlmst_service_user.type');
							$query->select('users.id as id', 'wlmst_service_user.type', 'service_hierarchies.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
							$query->where('users.id', $User['service_person']['reporting_manager_id']);
							$query->limit(1);
							$data = $query->get();

							$data = json_decode(json_encode($data), true);

							foreach ($data as $key => $value) {

								$data[$key]['id'] = "u-" . $value['id'];
								$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
							}

							$User['service_person']['reporting_manager'] = $data[0];
						} else {

							$Company = array();
							$Company = Company::select('id', 'name as text');
							// $Company->where(function ($query) use ($q) {
							// 	$query->where('name', 'like', '%' . $q . '%');

							// });
							$Company->where('id', $User['service_person']['reporting_company_id']);
							$Company = $Company->first();

							$data = array();

							if ($Company) {

								$countData = count($data);
								$data[$countData]['id'] = "c-" . $Company['id'];
								$data[$countData]['text'] = $Company->text . " (COMPANY)";
							}

							$User['service_person']['reporting_manager'] = $data[0];
						}

						$User['service_person']['states'] = StateList::select('id', 'name as text')->whereIn('id', explode(",", $User['service_person']->states))->get();

						$User['service_person']['cities'] = CityList::select('id', 'name as text')->whereIn('id', explode(",", $User['service_person']->cities))->get();
					}
				}

				$response = successRes("Successfully get user");
				$response['data'] = $User;
			} else {
				$response = errorRes("Invalid user access", 402);
			}
		} else {
			$response = errorRes("Invalid id");
		}
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	// AXONE WORK START
	public function searchServiceExecutiveType(Request $request)
	{

		$ServiceHierarchy = array();
		$ServiceHierarchy = ServiceHierarchy::select('id', 'name as text');
		$ServiceHierarchy->where('status', 1);
		$ServiceHierarchy->where('name', 'like', "%" . $request->q . "%");
		$ServiceHierarchy->limit(5);
		$ServiceHierarchy = $ServiceHierarchy->get();

		$response = array();
		$response['results'] = $ServiceHierarchy;
		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function searchServiceExecutiveReportingManager(Request $request)
	{

		if ($request->service_executive_type != "") {

			$ServiceHierarchy = array();
			$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
			$ServiceHierarchy->where('status', 1);
			$ServiceHierarchy->where('id', $request->service_executive_type);
			$ServiceHierarchy = $ServiceHierarchy->get();

			$ServiceHierarchyId = array();
			/// Repeat Code start
			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$ServiceHierarchy = array();
				$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
				$ServiceHierarchy->where('status', 1);
				$ServiceHierarchy->whereIn('id', $parentIds);
				$ServiceHierarchy = $ServiceHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$ServiceHierarchy = array();
				$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
				$ServiceHierarchy->where('status', 1);
				$ServiceHierarchy->whereIn('id', $parentIds);
				$ServiceHierarchy = $ServiceHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$ServiceHierarchy = array();
				$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
				$ServiceHierarchy->where('status', 1);
				$ServiceHierarchy->whereIn('id', $parentIds);
				$ServiceHierarchy = $ServiceHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$ServiceHierarchy = array();
				$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
				$ServiceHierarchy->where('status', 1);
				$ServiceHierarchy->whereIn('id', $parentIds);
				$ServiceHierarchy = $ServiceHierarchy->get();
			}
			/// Repeat Code end
			/// Repeat Code start
			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			if (count($parentIds) > 0) {

				$ServiceHierarchy = array();
				$ServiceHierarchy = ServiceHierarchy::select('id', 'parent_id');
				$ServiceHierarchy->where('status', 1);
				$ServiceHierarchy->whereIn('id', $parentIds);
				$ServiceHierarchy = $ServiceHierarchy->get();
			}
			/// Repeat Code end

			$parentIds = array();
			foreach ($ServiceHierarchy as $key => $value) {
				$ServiceHierarchyId[] = $value->id;
				if ($value->parent_id != 0) {
					$parentIds[] = $value->parent_id;
				}
			}

			$ServiceHierarchyId = array_unique($ServiceHierarchyId);
			$ServiceHierarchyId = array_values($ServiceHierarchyId);

			$q = $request->q;

			$query = DB::table('wlmst_service_user');
			$query->leftJoin('users', 'wlmst_service_user.user_id', '=', 'users.id');
			$query->leftJoin('sales_hierarchy', 'sales_hierarchy.id', '=', 'wlmst_service_user.type');
			$query->select('users.id as id', 'wlmst_service_user.type', 'sales_hierarchy.code', DB::raw('CONCAT(first_name," ", last_name) AS text'));
			$query->whereIn('wlmst_service_user.type', $ServiceHierarchyId);
			$query->where('users.type', 11);
			$query->where('users.status', 1);
			// $query->where('users.company_id', $request->user_company_id);
			$query->where('users.reference_id', '!=', 0);
			$query->where('users.id', '!=', $request->user_id);
			$query->where(function ($query) use ($q) {
				$query->where('users.first_name', 'like', '%' . $q . '%');
				$query->orWhere('users.last_name', 'like', '%' . $q . '%');
			});

			$query->limit(5);
			$data = $query->get();

			$data = json_decode(json_encode($data), true);

			foreach ($data as $key => $value) {

				$data[$key]['id'] = "u-" . $value['id'];
				$data[$key]['text'] = $data[$key]['text'] . " (" . $data[$key]['code'] . ")";
				unset($data[$key]['code']);
			}

			$Company = array();
			$Company = Company::select('id', 'name as text');
			$Company->where(function ($query) use ($q) {
				$query->where('name', 'like', '%' . $q . '%');
			});
			$Company = $Company->first();

			if ($Company) {

				$countData = count($data);
				$data[$countData]['id'] = "c-" . $Company['id'];
				$data[$countData]['text'] = $Company->text . " (COMPANY)";
			}

			$response = array();
			$response['results'] = $data;
		} else {
			$response = array();
			$response['results'] = array();
		}

		$response['pagination']['more'] = false;
		return response()->json($response)->header('Content-Type', 'application/json');
	}

	public function checkUserPhoneNumberAndEmail(Request $request)
	{
		if ($request->is_number == 1) {
			$rules = array();
			$rules['user_phone_number'] = 'required|digits:10|numeric';

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
				$response = errorRes("The request could not be understood by the server due to malformed syntax");
				$response['data'] = $validator->errors();
			} else {
				$User = User::select('id', 'first_name', 'last_name');
				$User->where('phone_number', $request->user_phone_number);
				if ($request->user_id != 0 || $request->user_id != '') {
					$User->where('id', '!=', $request->user_id);
				}
				$User = $User->first();
				if ($User) {

					$response = errorRes("User already registed with phone number, #" . $User->id . " assigned to " . $User->first_name . " " . $User->last_name);
				} else {
					$response = successRes("User phone number is valid");
				}
			}
		} else if ($request->is_number == 0) {
			$rules = array();
			$rules['user_email'] = 'required';

			$validator = Validator::make($request->all(), $rules);

			if ($validator->fails()) {
				$response = errorRes("The request could not be understood by the server due to malformed syntax");
				$response['data'] = $validator->errors();
			} else {
				$User = User::select('id', 'first_name', 'last_name');
				$User->where('email', $request->user_email);
				if ($request->user_id != 0 || $request->user_id != '') {
					$User->where('id', '!=', $request->user_id);
				}
				$User = $User->first();
				if ($User) {

					$response = errorRes("User already registed with email, #" . $User->id . " assigned to " . $User->first_name . " " . $User->last_name);
				} else {
					$response = successRes("User Email is valid");
				}
			}
		}
		return response()->json($response)->header('Content-Type', 'application/json');
	}
}
