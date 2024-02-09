<?php
namespace App\Http\Controllers;
use App\Models\CountryList;
use DB;
use Illuminate\Http\Request;

class UsersCompanyAdminController extends Controller {

	public function __construct() {

		$this->middleware(function ($request, $next) {

			if (!userHasAcccess(1)) {
				return redirect()->route('dashboard');
			}
			return $next($request);

		});

	}

	public function index() {
		$data = array();
		$data['title'] = "Compnay Admin -  Users";
		$data['country_list'] = CountryList::get();
		$data['type'] = 1;
		return view('users/compnay_admin', compact('data'));
	}

	public function ajax(Request $request) {

		$searchColumns = array(

			0 => 'users.id',
			1 => 'users.first_name',
			2 => 'users.last_name',
			3 => 'users.email',
			4 => 'users.phone_number',
		);

		$columns = array(
			0 => 'users.id',
			1 => 'users.first_name',
			2 => 'users.email',
			3 => 'users.status',
			4 => 'users.last_name',
			5 => 'users.type',
			6 => 'users.created_at',
			7 => 'users.status',
			8 => 'users.phone_number',
			9 => 'companies.name as company_name',
			10 => 'companies.first_name as company_first_name',
			11 => 'companies.last_name as company_last_name',

		);

		$recordsTotal = DB::table('users')->where('type', 1)->count();
		$recordsFiltered = $recordsTotal; // when there is no search parameter then total number rows = total number filtered rows.
		$query = DB::table('users');
		$query->leftJoin('companies', 'companies.id', '=', 'users.company_id');
		$query->select($columns);
		$query->where('type', 1);
		$query->limit($request->length);
		$query->offset($request->start);
		$query->orderBy($columns[$request['order'][0]['column']], $request['order'][0]['dir']);
		$isFilterApply = 0;

		if (isset($request['search']['value'])) {
			$isFilterApply = 1;
			$search_value = $request['search']['value'];
			$query->where(function ($query) use ($search_value, $searchColumns) {

				for ($i = 0; $i < count($searchColumns); $i++) {

					if ($i == 0) {
						$query->where($searchColumns[$i], 'like', "%" . $search_value . "%");

					} else {

						$query->orWhere($searchColumns[$i], 'like', "%" . $search_value . "%");

					}

				}

			});

		}

		$data = $query->get();
		// echo "<pre>";
		// print_r(DB::getQueryLog());
		// die;

		$data = json_decode(json_encode($data), true);

		if ($isFilterApply == 1) {
			$recordsFiltered = count($data);
		}

		foreach ($data as $key => $value) {

			$data[$key]['id'] = '<div class="avatar-xs"><span class="avatar-title rounded-circle">' . $data[$key]['id'] . '</span></div>';

			$data[$key]['company'] = '<h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">' . $value['company_name'] . '</a></h5>
             <p class="text-muted mb-0">' . $value['company_first_name'] . ' ' . $value['company_last_name'] . '</p>';

			$data[$key]['name'] = '<h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">' . $value['first_name'] . " " . $value['last_name'] . '</a></h5>
             <p class="text-muted mb-0">' . getUserTypeName($value['type']) . '</p>';

			// if ($data[$key]['created_at'] == $data[$key]['last_active_date_time']) {

			// 	$data[$key]['last_active_date_time'] = "-";
			// 	$data[$key]['last_login_date_time'] = "-";

			// } else {

			// 	$data[$key]['last_active_date_time'] = convertDateTime($value['last_active_date_time']);
			// 	$data[$key]['last_login_date_time'] = convertDateTime($value['last_login_date_time']);

			// }
			// $data[$key]['active_login'] = '<p class="text-muted mb-0">' . $data[$key]['last_active_date_time'] . '</p>
			//           <p class="text-muted mb-0">' . $data[$key]['last_login_date_time'] . '</p>';

			$data[$key]['status'] = getUserStatusLable($value['status']);
			$data[$key]['email'] = '<p class="text-muted mb-0">' . $value['email'] . '</p>
             <p class="text-muted mb-0">' . $value['phone_number'] . '</p>';

			$uiAction = '<ul class="list-inline font-size-20 contact-links mb-0">';

			$uiAction .= '<li class="list-inline-item px-2">';
			$uiAction .= '<a onclick="editView(\'' . $value['id'] . '\')" href="javascript: void(0);" title="Edit"><i class="bx bx-edit-alt"></i></a>';
			$uiAction .= '</li>';

			$uiAction .= '</ul>';
			$data[$key]['action'] = $uiAction;

		}

		$jsonData = array(
			"draw" => intval($request['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($recordsTotal), // total number of records
			"recordsFiltered" => intval($recordsFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $data, // total data array

		);
		return $jsonData;

	}

	function export() {

		$columns = array(
			'users.id',
			'users.first_name',
			'users.last_name',
			'users.email',
			'users.dialing_code',
			'users.phone_number',
			'users.status',
			'users.created_at',

		);

		$query = DB::table('users');
		$query->select($columns);
		$query->where('type', 1);
		$query->orderBy('id', 'desc');
		$data = $query->get();

		$headers = array("#ID", "Firstname", "Lastname", "Email", "Phone", "Status", "Created");

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="users-company-admin.csv"');
		$fp = fopen('php://output', 'wb');

		fputcsv($fp, $headers);

		foreach ($data as $key => $value) {

			$createdAt = convertDateTime($value->created_at);
			$status = $value->status;
			if ($status == 0) {
				$status = "Inactive";
			} else if ($status == 1) {
				$status = "Active";
			} else if ($status == 2) {
				$status = "Blocked";
			}

			$lineVal = array(
				$value->id,
				$value->first_name,
				$value->last_name,
				$value->email,
				$value->dialing_code . " " . $value->phone_number,
				$status,
				$createdAt,

			);

			fputcsv($fp, $lineVal, ",");

		}

		fclose($fp);

	}

}