<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadContact;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\models\ServiceReqTimelion;

class RequestController extends Controller
{
    public function index()
    {
        return view('request/index');
    }

    public function table(Request $request)
    {
        $data = [];
        $data['title'] = 'Request';
        $data['request_module'] = 1;

        $RequestStatus = getRequestStatus();
        $RequestStatus[6]['id'] = 6;
        $RequestStatus[6]['name'] = "All";
        $RequestStatus[6]['code'] = "All";
        $RequestStatus[6]['header_code'] = "All";
        $RequestStatus[6]['sequence_id'] = 7;

        $data['request_status'] = $RequestStatus;


        return view('request/table', compact('data'));
    }

    public function searchCustomer(Request $request)
    {
        $query = $request->input('q');
        $users = LeadContact::select('id', DB::raw('CONCAT(first_name, " ", last_name) as text'))
            ->where('status', 1)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('first_name', 'LIKE', '%' . $query . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $query . '%');
            })
            ->limit(5)
            ->get();

        $response = array();
        $response['results'] = $users;
        $response['pagination']['more'] = false;

        return response()->json($response)->header('Content-Type', 'application/json');
    }

    public function save(Request $request)
    {
        $rules = [];

        $rules['req_customer'] = 'required';
        $rules['req_phone_number'] = 'required|digits:10|numeric';
        $rules['req_first_name'] = 'required';
        $rules['req_electrician_name'] = 'required';
        $rules['req_electrician_number'] = 'required|digits:10|numeric';
        $rules['req_person_type'] = 'required';
        $rules['request_type'] = 'required';
        $rules['req_power_type'] = 'required';
        $rules['req_house_no'] = 'required';
        $rules['req_address_line1'] = 'required';
        $rules['req_address_line2'] = 'required';
        $rules['req_area'] = 'required';
        $rules['req_city'] = 'required';
        $rules['req_state'] = 'required';
        $rules['req_pincode'] = 'required';
        $rules['req_quotation'] = 'required';
        $rules['req_send_customer'] = 'required';

        $customMessage = [];

        $customMessage['req_customer.required'] = 'Enter Customer';
        $customMessage['req_phone_number.required'] = 'Enter Contect Number';
        $customMessage['req_first_name.required'] = 'Enter client Name';
        $customMessage['req_electrician_name.required'] = 'Enter Electrician Name';
        $customMessage['req_electrician_number.required'] = 'Enter Electrician Number';
        $customMessage['req_person_type.required'] = 'Select Person Type';
        $customMessage['request_type.required'] = 'Select Request Type';
        $customMessage['req_power_type.required'] = 'Select Power Type';
        $customMessage['req_house_no.required'] = 'Enter House Number';
        $customMessage['req_address_line1.required'] = 'Enter Address 1';
        $customMessage['req_address_line2.required'] = 'Enter Address 2';
        $customMessage['req_area.required'] = 'Enter Area';
        $customMessage['req_city.required'] = 'Enter City';
        $customMessage['req_state.required'] = 'Enter State';
        $customMessage['req_pincode.required'] = 'Enter Pincode';
        $customMessage['req_quotation.required'] = 'Enter Quotation';
        $customMessage['req_send_customer.required'] = 'Enter Send Customer';


        $validator = Validator::make($request->all(), $rules, $customMessage);

        if ($validator->fails()) {
            $response = [];
            $response['status'] = 0;
            $response['msg'] = $validator->errors()->first();
            $response['statuscode'] = 400;
            $response['data'] = $validator->errors()->first();
        } else {
            $uploadedFile1 = '';
            if ($request->hasFile('req_quotation')) {
                $folderPathofFile = 'assets/images/request/';

                $fileObject1 = $request->file('req_quotation');
                $extension = $fileObject1->getClientOriginalExtension();

                $fileName1 = time() . mt_rand(10000, 99999) . '.' . $extension;

                $destinationPath = public_path($folderPathofFile);

                $fileObject1->move($destinationPath, $fileName1);

                if (File::exists(public_path($folderPathofFile . '/' . $fileName1))) {
                    $uploadedFile1 = $folderPathofFile . '/' . $fileName1;
                    //START UPLOAD FILE ON SPACES


                    unlink(public_path($uploadedFile1));

                    //END UPLOAD FILE ON SPACES
                }
            }

            $RequestEntery = new ModelsRequest;
            $RequestEntery->customer_id = $request->req_customer;
            $RequestEntery->contact_no = $request->req_phone_number;
            $RequestEntery->client_name = $request->req_first_name;
            $RequestEntery->electrician_name = $request->req_electrician_name;
            $RequestEntery->electrician_no = $request->req_electrician_number;
            $RequestEntery->point_con_name = $request->req_point_name;
            $RequestEntery->point_con_no = $request->req_point_number;
            $RequestEntery->req_per_type = $request->req_person_type;
            $RequestEntery->req_type = implode(',', $request['request_type']);
            $RequestEntery->power_type = $request->req_power_type;
            $RequestEntery->home_no = $request->req_house_no;
            $RequestEntery->address_line_1 = $request->req_address_line1;
            $RequestEntery->address_line_2 = $request->req_address_line2;
            $RequestEntery->area = $request->req_area;
            $RequestEntery->city_id = $request->req_city;
            $RequestEntery->state_id = $request->req_state;
            $RequestEntery->pincode = $request->req_pincode;
            $RequestEntery->quotation_pdf = $uploadedFile1;
            $RequestEntery->send_to_customer = implode(',', $request['req_send_customer']);
            $RequestEntery->notes = $request->req_note;
            $RequestEntery->entryby = Auth::user()->id;
            $RequestEntery->entryip = $request->ip();
            $RequestEntery->save();

            if ($RequestEntery) {

                $RequestTimeline  = new ServiceReqTimelion;
                $RequestTimeline->user_id = Auth::user()->id;
                $RequestTimeline->request_id = $RequestEntery->id;
                $RequestTimeline->type = 'Request';
                $RequestTimeline->reffrance_id = $RequestEntery->id;
                $RequestTimeline->description = "Request Entry by " . Auth::user()->first_name . " " . Auth::user()->last_name . ".";
                $RequestTimeline->source = "WEB";
                $RequestTimeline->created_at = Auth::user()->id;
            }
            $response = successRes();
        }
        return response()
            ->json($response)
            ->header('Content-Type', 'application/json');
    }

    public function ajax(Request $request)
    {
        $searchColumns = array(
            0 => 'request.id',
            1 => 'request.client_name',
            2 => 'request.electrician_name',
            3 => 'request.email',
            4 => 'request.contact_no',
            5 => 'request.home_no',

        );

        $sortingColumns = array(
            1 => 'request.id',
            2 => 'request.client_name',
            3 => 'request.contact_no',
            4 => 'request.home_no',
            5 => 'request.state_id',
            6 => 'request.area',
            7 => 'request.city_id',

        );

        $selectColumns = array(
            0 => 'request.id',
            1 => 'request.id',
            2 => 'request.client_name',
            3 => 'request.contact_no',
            4 => 'request.home_no',
            5 => 'request.address_line_1',
            6 => 'request.address_line_2',
            7 => 'request.area',
            8 => 'request.city_id',
            9 => 'request.electrician_name',
            10 => 'request.created_at',
            11 => 'request.updated_at',
            13 => 'request.state_id',
            14 => 'request.pincode',
            15 => 'request.point_con_name',
            16 => 'request.point_con_no',
            17 => 'request.customer_id',
            18 => 'lead_contacts.first_name',
            19 => 'lead_contacts.last_name',

        );

        $query = ModelsRequest::query();
        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;


        $query = ModelsRequest::query();
        $query->leftjoin('lead_contacts', 'request.customer_id', '=', 'lead_contacts.id');
        $query->select($selectColumns);
        $query->limit($request->length);
        // $query->offset($request->start);
        // $query->orderBy($sortingColumns[$request['order'][0]['column']], $request['order'][0]['dir']);

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
        $data = json_decode(json_encode($data), true);

        foreach ($data as $key => $value) {


            $viewData[$key] = [];
            $routeArchitects = route('request.index') . '?id=' . $value['id'];
            $viewData[$key]['name'] = '<a onclick="" href="' . $routeArchitects . '"><b>' . '#' . $value['id'] . '  ' . '</b>' . ucwords(strtolower($value['first_name'])) . ' ' . ucwords(strtolower($value['last_name'])) . '</a>';
            $viewData[$key]['number'] = '<p class="text-muted mb-0">' . $value['contact_no'] . '</p><p class="text-muted mb-0"> </p>';
            $viewData[$key]['location'] = '<p class="text-muted mb-0">' . $value['home_no'] . ", " . $value['address_line_1'] . ", " . $value['address_line_2'] . '</p> <br> <p class="text-muted mb-0">' . $value['area'] . ", " . $value['pincode'] . '</p>';
            $viewData[$key]['status'] = '0';

            $viewData[$key]['date'] = '<p class="text-muted mb-0">' . $value['created_at'] . '</p>';

            $viewData[$key]['customer'] = '<span class="text-muted mb-0">' . $value['first_name'] . ' ' . $value['last_name'] . '</span>';
            $viewData[$key]['created_by'] = '<span class="text-muted mb-0">' . $value['first_name'] . ' ' . $value['last_name'] . '</span>';
            $viewData[$key]['reqdate'] = '<span class="text-muted mb-0">' . $value['created_at'] . '</span>';
        }

        $jsonData = [
            'draw' => intval($request['draw']),
            // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            'recordsTotal' => intval($recordsTotal),
            // total number of records
            'recordsFiltered' => intval($recordsFiltered),
            // total number of records after searching, if there is no searching then totalFiltered = totalData
            'data' => $viewData, // total data array
            // "data12" => $query, // total data array
        ];
        return $jsonData;
    }
}
