<?php

namespace App\Http\Controllers\CRM\Lead;

use App\Models\Lead;
use App\Models\User;
use App\Models\Architect;
use App\Models\Inquiry;
use App\Models\CityList;
use App\Models\LeadCall;
use App\Models\LeadFile;
use App\Models\LeadTask;
use App\Models\InquiryLog;
use App\Models\LeadSource;
use App\Models\Exhibition;
use App\Models\LeadUpdate;
use App\Models\SalePerson;
use App\Models\LeadClosing;
use App\Models\LeadStatusUpdate;
use App\Models\UserDefaultView;
use App\Models\DebugLog;
use App\Models\LeadContact;
use App\Models\LeadMeeting;
use App\Models\LeadTimeline;
use App\Models\Wlmst_Client;
use Illuminate\Http\Request;
use App\Models\CRMSettingBHK;
use App\Models\InquiryUpdate;
use App\Models\TagMaster;
use App\Models\ChannelPartner;
use App\Models\LeadCompetitor;
use App\Models\Wltrn_Quotation;
use App\Models\CRMSettingSource;
use App\Models\CRMSettingSiteType;
use Illuminate\Support\Facades\DB;
use App\Models\CRMSettingSubStatus;
use App\Http\Controllers\Controller;
use App\Models\CRMLeadAdvanceFilter;
use App\Models\LeadQuestion;
use App\Models\LeadQuestionAnswer;
use App\Models\LeadQuestionOptions;
// use DB;
use App\Models\CRMSettingSourceType;
use App\Models\Wltrn_QuotItemdetail;
use Illuminate\Support\Facades\Auth;
use App\Models\CRMSettingCompetitors;
use App\Models\CRMSettingStageOfSite;
use App\Models\CRMSettingWantToCover;
use App\Models\InquiryQuestionAnswer;
use App\Models\CRMSettingMeetingTitle;
use App\Models\LeadMeetingParticipant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Models\CRMLeadAdvanceFilterItem;
use App\Models\Tags;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Whatsapp\WhatsappApiContoller;
use Illuminate\Support\Arr;
use Mockery\Undefined;
use PhpParser\Node\Expr\AssignOp\Concat;

date_default_timezone_set("Asia/Kolkata");
class LeadController extends Controller
{

    public function __construct()
    {

        $this->middleware(function ($request, $next) {

            $tabCanAccessBy = array(0, 1, 2, 9, 11, 101, 102, 103, 104, 105, 202, 302, 12);

            if (!in_array(Auth::user()->type, $tabCanAccessBy)) {
                return redirect()->route('dashboard');
            }

            return $next($request);
        });
    }


    function searchStatusInAction(Request $request)
    {


        $Lead = Lead::select('is_deal')->where('id', $request->lead_id)->first();
        $Lead_type = $Lead->is_deal;

        $searchKeyword = isset($request->q) ? $request->q : "";
        $type = isset($request->type) ? $request->type : 0;

        $LeadStatus = getLeadStatus();
        $finalArray[] = array();

        if ($Lead_type == 1) {
            foreach ($LeadStatus as $key => $value) {
                if ($value['type'] == 1) {
                    $countFinal = count($finalArray);
                    $finalArray[$countFinal] = array();
                    $finalArray[$countFinal]['id'] = $value['id'];
                    $finalArray[$countFinal]['text'] = $value['name'];
                }
            }
        } else {
            foreach ($LeadStatus as $key => $value) {
                if ($value['type'] == 0) {
                    $countFinal = count($finalArray);
                    $finalArray[$countFinal] = array();
                    $finalArray[$countFinal]['id'] = $value['id'];
                    $finalArray[$countFinal]['text'] = $value['name'];
                }
            }
        }



        $response = array();
        $response['results'] = $finalArray;
        $response['pagination']['more'] = false;
        return response()->json($response)->header('Content-Type', 'application/json');
    }

  

    function searchReminderTimeSlot(Request $request)
    {

        $searchKeyword = isset($request->q) ? $request->q : "";

        $ReminderTimeSlot = getReminderTimeSlot();

        $finalArray[] = array();
        foreach ($ReminderTimeSlot as $key => $value) {
            $finalArray[$key]['id'] = $value['id'];
            $finalArray[$key]['text'] = $value['name'];
        }

        $response = array();
        $response['results'] = $finalArray;
        $response['pagination']['more'] = false;
        return response()->json($response)->header('Content-Type', 'application/json');
    }

  
}
