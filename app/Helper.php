<?php

use App\Models\User;

use App\Models\CRMLog;
use App\Models\CityList;
use App\Models\DebugLog;
use App\Models\StateList;
use App\Models\ProductLog;
use App\Models\UserContact;
use App\Models\UserFiles;
use App\Models\CountryList;
use App\Models\UserNotes;
use App\Models\UserCallAction;
use App\Models\CRMSettingMeetingTitle;
use App\Models\UserMeetingAction;
use App\Models\UserTaskAction;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Wlmst_ServiceExecutive;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Whatsapp\WhatsappApiContoller;

function successRes($msg = 'Success', $statusCode = 200)
{
    $return = [];
    $return['status'] = 1; // 1=Success; 0=error; 2=appupdate
    $return['status_code'] = $statusCode;
    $return['msg'] = $msg;
    return $return;
}

function errorRes($msg = 'Error', $statusCode = 400)
{
    $return = [];
    $return['status'] = 0; // 1=Success; 0=error; 2=appupdate
    $return['status_code'] = $statusCode;
    $return['msg'] = $msg;
    return $return;
}

function getSpacesFolder()
{
    // if($_SERVER['HTTP_HOST'] == '103.218.110.153:242'){
    // 	// return '127.0.0.1:8000';
    // 	return "erp.whitelion.in";
    // }else {
    // 	return $_SERVER['HTTP_HOST'];
    // }
    return 'erp.whitelion.in';
}

function uploadFileOnSpaces($diskFilePath, $spaceFilePath)
{
    $spacesFolder = getSpacesFolder();
    return Storage::disk('spaces')->put($spacesFolder . '/' . $spaceFilePath, @file_get_contents($diskFilePath));
}

function getSpaceFilePath($filePath)
{
    $spacesFolder = getSpacesFolder();
    return 'https://whitelion.sgp1.digitaloceanspaces.com/' . $spacesFolder . '' . $filePath;
}

function loadTextLimit($string, $limit)
{
    $string = htmlspecialchars_decode($string);
    if (strlen($string) > $limit) {
        return substr($string, 0, $limit - 3) . '...';
    } else {
        return $string;
    }
}

function randomString($stringType, $stringLenth)
{
    if ($stringType == 'numeric') {
        $characters = '0123456789';
    } elseif ($stringType == 'alpha-numeric') {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    $randstring = '';
    for ($i = 0; $i < $stringLenth; $i++) {
        $randstring .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randstring;
}

function websiteTimeZone()
{
    return 'Asia/Kolkata';
}

function convertDateTime($GMTDateTime)
{
    $TIMEZONE = websiteTimeZone();
    try {
        // $GMTDateTime = str_replace("T", " ", $GMTDateTime);
        // $GMTDateTime = explode(".", $GMTDateTime);
        // $GMTDateTime = $GMTDateTime[0];
        // print_r($GMTDateTime);
        // die;

        $dt = new DateTime('@' . strtotime($GMTDateTime));
        $dt->setTimeZone(new DateTimeZone($TIMEZONE));
        return $dt->format('d/m/Y h:i A');
    } catch (Exception $e) {
        return $GMTDateTime;
    }
}

function convertDateAndTime($GMTDateTime, $type)
{
    $TIMEZONE = websiteTimeZone();
    try {
        $dt = new DateTime('@' . strtotime($GMTDateTime));
        $dt->setTimeZone(new DateTimeZone($TIMEZONE));
        if ($type == 'date') {
            return $dt->format('d/m/Y');
        } elseif ($type == 'time') {
            return $dt->format('h:i A');
        }
    } catch (Exception $e) {
        return $GMTDateTime;
    }
}

function convertDateAndTimeMounth($GMTDateTime, $type)
{
    $TIMEZONE = websiteTimeZone();
    try {
        $dt = new DateTime('@' . strtotime($GMTDateTime));
        $dt->setTimeZone(new DateTimeZone($TIMEZONE));
        if ($type == 'date') {
            return $dt->format('d M Y');
        } elseif ($type == 'time') {
            return $dt->format('h:i A');
        }
    } catch (Exception $e) {
        return $GMTDateTime;
    }
}

function convertDateAndTime2($GMTDateTime, $type)
{
    if ($type == 'date') {
        return date('d M Y', strtotime($GMTDateTime));
    } elseif ($type == 'time') {
        return date('h:i A', strtotime($GMTDateTime));
    }
}

function convertOrderDateTime($GMTDateTime, $showType)
{
    $TIMEZONE = websiteTimeZone();
    try {
        $dt = new DateTime('@' . strtotime($GMTDateTime));
        $dt->setTimeZone(new DateTimeZone($TIMEZONE));

        if ($showType == 'date') {
            return $dt->format('d M y');
        } elseif ($showType == 'time') {
            return $dt->format('h:i:s A');
        }
    } catch (Exception $e) {
        return $GMTDateTime;
    }
}

function saveDebugLog($params)
{
    $DebugLog = new DebugLog();
    $DebugLog->user_id = Auth::user()->id;
    $DebugLog->name = $params['name'];
    $DebugLog->description = $params['description'];
    $DebugLog->save();
}



function saveCRMUserLog($params)
{
    if (isset(Auth::user()->id) && Auth::user()->id != '') {
        $params['user_id'] = Auth::user()->id;
    }

    if (isset($params['inquiry_id'])) {
        $params['inquiry_id'] = $params['inquiry_id'];
    } else {
        $params['inquiry_id'] = 0;
    }

    if (isset($params['is_manually'])) {
        $params['is_manually'] = $params['is_manually'];
    } else {
        $params['is_manually'] = 0;
    }

    if (isset($params['points'])) {
        $params['points'] = $params['points'];
    } else {
        $params['points'] = 0;
    }

    if (isset($params['order_id'])) {
        $params['order_id'] = $params['order_id'];
    } else {
        $params['order_id'] = 0;
    }

    $DebugLog = new CRMLog();
    $DebugLog->user_id = $params['user_id'];
    $DebugLog->for_user_id = $params['for_user_id'];
    $DebugLog->inquiry_id = $params['inquiry_id'];
    $DebugLog->is_manually = $params['is_manually'];
    $DebugLog->points = $params['points'];
    $DebugLog->order_id = $params['order_id'];
    $DebugLog->name = $params['name'];
    $DebugLog->description = $params['description'];
    $DebugLog->save();
}

function getCityName($cityId)
{
    $CityListName = '';

    $CityList = CityList::select('name')->find($cityId);
    if ($CityList) {
        $CityListName = $CityList->name;
    }

    return $CityListName;
}

function getStateName($stateId)
{
    $StateListName = '';

    $StateList = StateList::select('name')->find($stateId);
    if ($StateList) {
        $StateListName = $StateList->name;
    }

    return $StateListName;
}

function getCountryName($stateId)
{
    $CountryListName = '';

    $CountryList = CountryList::select('name')->find($stateId);
    if ($CountryList) {
        $CountryListName = $CountryList->name;
    }

    return $CountryListName;
}

function priceLable($price)
{
    return number_format($price, 2);
}



function getCRMStageOfSiteStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMSiteTypeStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}
function getCRMBHKStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}
function getCRMWantToCoverStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMSouceTypeStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMCompetitorsStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMSourceStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMSSubStatusLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}

function getCRMContactTagLable($setting)
{
    $setting = (int) $setting;

    if ($setting == 0) {
        $setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($setting == 1) {
        $setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $setting;
}



function getMainMasterStatusLable($mainMasterStatus)
{
    $mainMasterStatus = (int) $mainMasterStatus;

    if ($mainMasterStatus == 0) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($mainMasterStatus == 1) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    } elseif ($mainMasterStatus == 2) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
    }
    return $mainMasterStatus;
}


function getCityStatusLable($cityStatus)
{
    $cityStatus = (int) $cityStatus;

    if ($cityStatus == 0) {
        $cityStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($cityStatus == 1) {
        $cityStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    } elseif ($cityStatus == 2) {
        $cityStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
    }
    return $cityStatus;
}


function getUserStatusLable($userStatus)
{
    $userStatus = (int) $userStatus;
    if ($userStatus == 0) {
        $userStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($userStatus == 1) {
        $userStatus = '<span class="badge badge-pill badge-soft-success font-size-11">Active</span>';
    } elseif ($userStatus == 2) {
        $userStatus = '<span class="badge badge-pill badge-soft-danger font-size-11">Pending</span>';
    }
    return $userStatus;
}

function getUserStatus($userStatus)
{
    $userStatus = (int) $userStatus;
    if ($userStatus == 0) {
        $userStatus = 'Inactive';
    } elseif ($userStatus == 1) {
        $userStatus = 'Active';
    } elseif ($userStatus == 2) {
        $userStatus = 'Pending';
    }
    return $userStatus;
}

function getCompanyStatusLable($companyStatus)
{
    $companyStatus = (int) $companyStatus;
    if ($companyStatus == 0) {
        $companyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($companyStatus == 1) {
        $companyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    } elseif ($companyStatus == 2) {
        $companyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
    }
    return $companyStatus;
}

function getGiftCategoryStatusLable($GiftCategoryStatus)
{
    $GiftCategoryStatus = (int) $GiftCategoryStatus;
    if ($GiftCategoryStatus == 0) {
        $GiftCategoryStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($GiftCategoryStatus == 1) {
        $GiftCategoryStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $GiftCategoryStatus;
}

function getGiftProductStatusLable($GiftCategoryStatus)
{
    $GiftCategoryStatus = (int) $GiftCategoryStatus;
    if ($GiftCategoryStatus == 0) {
        $GiftCategoryStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($GiftCategoryStatus == 1) {
        $GiftCategoryStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $GiftCategoryStatus;
}

function getCRMHelpDocumentStatusLable($HelpDocumentStatus)
{
    $HelpDocumentStatus = (int) $HelpDocumentStatus;
    if ($HelpDocumentStatus == 0) {
        $HelpDocumentStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($HelpDocumentStatus == 1) {
        $HelpDocumentStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    }
    return $HelpDocumentStatus;
    // code...
}

/////

function getUserTypes()
{
    $userTypes = [];
    $userTypes[0]['id'] = 0;
    $userTypes[0]['name'] = 'Admin';
    $userTypes[0]['short_name'] = 'ADMIN';
    $userTypes[0]['lable'] = 'user-admin';
    $userTypes[0]['key'] = 't-user-admin';
    $userTypes[0]['url'] = route('users.admin');
    $userTypes[0]['can_login'] = 1;

    $userTypes[1]['id'] = 1;
    $userTypes[1]['name'] = 'Company Admin';
    $userTypes[1]['short_name'] = 'COMPANY ADMIN';
    $userTypes[1]['lable'] = 'user-company-admin';
    $userTypes[1]['key'] = 't-user-company-admin';
    $userTypes[1]['url'] = route('users.company.admin');
    $userTypes[1]['can_login'] = 1;

    $userTypes[11]['id'] = 11;
    $userTypes[11]['name'] = 'Service User';
    $userTypes[11]['short_name'] = 'SERVICE USER';
    $userTypes[11]['lable'] = 'user-service-executive';
    $userTypes[11]['key'] = 't-user-service-executive';
    $userTypes[11]['url'] = route('users.service.executive');
    $userTypes[11]['can_login'] = 1;




    return $userTypes;
}


function CRMUserType()
{
    $userTypes = [];

    $userTypes[202]['id'] = 202;
    $userTypes[202]['name'] = 'Architect(Prime)';
    $userTypes[202]['lable'] = 'architect-prime';
    $userTypes[202]['short_name'] = 'PRIME';
    $userTypes[202]['another_name'] = 'ARCHITECH';

    $userTypes[302]['can_login'] = 1;
    $userTypes[302]['id'] = 302;
    $userTypes[302]['name'] = 'Electrician(Prime)';
    $userTypes[302]['lable'] = 'electrician-prime';
    $userTypes[302]['short_name'] = 'PRIME';
    $userTypes[302]['another_name'] = 'ELECTRICIAN';

    return $userTypes;
}

function getCustomers()
{
    $userTypes = [];
    $userTypes[10000]['id'] = 10000;
    $userTypes[10000]['name'] = 'User';
    $userTypes[10000]['lable'] = 'user';
    $userTypes[10000]['short_name'] = 'USER';
    $userTypes[10000]['another_name'] = 'USER';
    $userTypes[10000]['can_login'] = 0;
    return $userTypes;
}


function getLeadStatus($statusID = '')
{
    $leadStatus = [];
    $leadStatus[1]['id'] = 1;
    $leadStatus[1]['name'] = 'Entry';
    $leadStatus[1]['type'] = 0;
    $leadStatus[1]['index'] = 1;
    $leadStatus[1]['is_active'] = 0;

    $leadStatus[2]['id'] = 2;
    $leadStatus[2]['name'] = 'Call';
    $leadStatus[2]['type'] = 0;
    $leadStatus[2]['index'] = 2;
    $leadStatus[2]['is_active'] = 0;

    $leadStatus[3]['id'] = 3;
    $leadStatus[3]['name'] = 'Qualified';
    $leadStatus[3]['type'] = 0;
    $leadStatus[3]['index'] = 3;
    $leadStatus[3]['is_active'] = 0;

    $leadStatus[4]['id'] = 4;
    $leadStatus[4]['name'] = 'Demo Meeting Done';
    $leadStatus[4]['type'] = 0;
    $leadStatus[4]['index'] = 4;
    $leadStatus[4]['is_active'] = 0;

    $leadStatus[5]['id'] = 5;
    $leadStatus[5]['name'] = 'Not Qualified';
    $leadStatus[5]['type'] = 0;
    $leadStatus[5]['index'] = 5;
    $leadStatus[5]['is_active'] = 0;

    $leadStatus[6]['id'] = 6;
    $leadStatus[6]['name'] = 'Cold';
    $leadStatus[6]['type'] = 0;
    $leadStatus[6]['index'] = 6;
    $leadStatus[6]['is_active'] = 0;

    // $leadStatus[7]['id'] = 7;
    // $leadStatus[7]['name'] = "Demo Meeting Done";
    // $leadStatus[7]['type'] = 0;
    // $leadStatus[7]['index'] = 7;

    $leadStatus[100]['id'] = 100;
    $leadStatus[100]['name'] = 'Quotation';
    $leadStatus[100]['type'] = 1;
    $leadStatus[100]['index'] = 7;
    $leadStatus[100]['is_active'] = 0;

    $leadStatus[101]['id'] = 101;
    $leadStatus[101]['name'] = 'Negotiation';
    $leadStatus[101]['type'] = 1;
    $leadStatus[101]['index'] = 8;
    $leadStatus[101]['is_active'] = 0;

    $leadStatus[102]['id'] = 102;
    // $leadStatus[102]['name'] = "Order Confirm";
    $leadStatus[102]['name'] = 'Token Received';
    $leadStatus[102]['type'] = 1;
    $leadStatus[102]['index'] = 9;
    $leadStatus[102]['is_active'] = 0;

    $leadStatus[103]['id'] = 103;
    $leadStatus[103]['name'] = 'Won';
    $leadStatus[103]['type'] = 1;
    $leadStatus[103]['index'] = 10;
    $leadStatus[103]['is_active'] = 0;

    $leadStatus[104]['id'] = 104;
    $leadStatus[104]['name'] = 'Lost';
    $leadStatus[104]['type'] = 1;
    $leadStatus[104]['index'] = 11;
    $leadStatus[104]['is_active'] = 0;

    $leadStatus[105]['id'] = 105;
    $leadStatus[105]['name'] = 'Cold';
    $leadStatus[105]['type'] = 1;
    $leadStatus[105]['index'] = 12;
    $leadStatus[105]['is_active'] = 0;

    if ($statusID != 0 && $statusID != '') {
        $leadStatus[$statusID]['is_active'] = 1;
    }

    return $leadStatus;
}


function getInquirySourceTypes()
{
    $sourceTypes = [];
    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Architect(Non Prime)';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 201;
    $sourceTypes[$cSourceType] = $sourceTypeObject;
    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Architect(Prime)';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 202;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Electrician(Non Prime)';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 301;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Electrician(Prime)';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 302;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'ASM';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 101;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'ADM';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 102;
    $sourceTypes[$cSourceType] = $sourceTypeObject;
    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'APM';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 103;
    $sourceTypes[$cSourceType] = $sourceTypeObject;
    $cSourceType = count($sourceTypes);

    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'AD';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 104;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Retailer';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 105;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    // $cSourceType = count($sourceTypes);
    // $sourceTypeObject = array();
    // $sourceTypeObject['lable'] = "Retailer";
    // $sourceTypeObject['type'] = "textrequired";
    // $sourceTypeObject['id'] = 1;
    // $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Whitelion HO';
    $sourceTypeObject['type'] = 'textnotrequired';

    $sourceTypeObject['id'] = 2;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Cold call';
    $sourceTypeObject['type'] = 'fix';
    $sourceTypeObject['id'] = 3;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Marketing activities';
    // $sourceTypeObject['type'] = "fix";
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 4;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Other';
    $sourceTypeObject['type'] = 'textrequired';
    $sourceTypeObject['id'] = 5;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Existing Client';
    $sourceTypeObject['type'] = 'textnotrequired';
    $sourceTypeObject['id'] = 6;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Third Party';
    $sourceTypeObject['type'] = 'user';
    $sourceTypeObject['id'] = 8;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $cSourceType = count($sourceTypes);
    $sourceTypeObject = [];
    $sourceTypeObject['lable'] = 'Exhibition';
    $sourceTypeObject['type'] = 'exhibition';
    $sourceTypeObject['id'] = 9;
    $sourceTypes[$cSourceType] = $sourceTypeObject;

    $isAdminOrCompanyAdmin = isAdminOrCompanyAdmin();
    if ($isAdminOrCompanyAdmin == 1) {
        $cSourceType = count($sourceTypes);
        $sourceTypeObject = [];
        $sourceTypeObject['lable'] = 'None';
        $sourceTypeObject['type'] = 'fix';
        $sourceTypeObject['id'] = 0;
        $sourceTypes[$cSourceType] = $sourceTypeObject;
    }

    return $sourceTypes;
}



function getAllUserTypes()
{
    $userTypes = getUserTypes();
    // $channelPartners = getChannelPartners();
    // $architects = getArchitects();
    // $electricians = getElectricians();
    $customers = getCustomers();
    // foreach ($channelPartners as $key => $value) {
    //     $userTypes[$key] = $value;
    // }
    // foreach ($architects as $key => $value) {
    //     $userTypes[$key] = $value;
    // }
    // foreach ($electricians as $key => $value) {
    //     $userTypes[$key] = $value;
    // }
    foreach ($customers as $key => $value) {
        $userTypes[$key] = $value;
    }

    return $userTypes;
}

function getUserTypeName($userType)
{
    $userType = (int) $userType;
    $userTypeLable = '';
    if (isset(getUserTypes()[$userType]['short_name'])) {
        $userTypeLable = getUserTypes()[$userType]['short_name'];
    } elseif (isset(getCustomers()[$userType]['short_name'])) {
        $userTypeLable = getCustomers()[$userType]['short_name'];
    }

    return $userTypeLable;
}

function getUserTypeMainLabel($userType)
{
    $userType = (int) $userType;
    $userTypeLable = '';
    if (isset(getUserTypes()[$userType]['short_name'])) {
        $userTypeLable = getUserTypes()[$userType]['short_name'];
    }
    // elseif (isset(getChannelPartners()[$userType]['short_name'])) {
    //     $userTypeLable = getChannelPartners()[$userType]['short_name'];
    // } elseif (isset(getArchitects()[$userType]['short_name'])) {
    //     $userTypeLable = 'ARCHITECT ' . getArchitects()[$userType]['short_name'];
    // } elseif (isset(getElectricians()[$userType]['short_name'])) {
    //     $userTypeLable = 'ELECTRICIAN ' . getElectricians()[$userType]['short_name'];
    // } 
    elseif (isset(getCustomers()[$userType]['short_name'])) {
        $userTypeLable = getCustomers()[$userType]['short_name'];
    }

    return $userTypeLable;
}
function getUserTypeNameForLeadTag($userType)
{
    $userType = (int) $userType;
    $userTypeLable = '';
    if (isset(getUserTypes()[$userType]['short_name'])) {
        $userTypeLable = getUserTypes()[$userType]['short_name'];
    }
    //  elseif (isset(getChannelPartners()[$userType]['short_name'])) {
    //     $userTypeLable = getChannelPartners()[$userType]['short_name'];
    // } elseif (isset(getArchitects()[$userType]['short_name'])) {
    //     $userTypeLable = 'ARCHITECT ' . getArchitects()[$userType]['short_name'];
    // } elseif (isset(getElectricians()[$userType]['short_name'])) {
    //     $userTypeLable = 'ELECTRICIAN ' . getElectricians()[$userType]['short_name'];
    // }
    elseif (isset(getCustomers()[$userType]['short_name'])) {
        $userTypeLable = getCustomers()[$userType]['short_name'];
    }

    return $userTypeLable;
}



function isAdminOrCompanyAdmin()
{
    return Auth::user()->type == 0 || Auth::user()->type == 1 ? 1 : 0;
}
function isAdmin()
{
    return Auth::user()->type == 0 ? 1 : 0;
}
function isCompanyAdmin()
{
    return Auth::user()->type == 1 ? 1 : 0;
}
function isSalePerson()
{
    return Auth::user()->type == 2 ? 1 : 0;
}

function isPurchasePerson()
{
    return Auth::user()->type == 10 ? 1 : 0;
}
function isAccountUser()
{
    return Auth::user()->type == 3 ? 1 : 0;
}

function isDispatcherUser()
{
    return Auth::user()->type == 4 ? 1 : 0;
}
function isArchitect()
{
    return Auth::user()->type == 202 ? 1 : 0;
}
function isReception()
{
    return Auth::user()->type == 12 ? 1 : 0;
}

function isElectrician()
{
    return Auth::user()->type == 302 ? 1 : 0;
}

function isMarketingUser()
{
    return Auth::user()->type == 6 ? 1 : 0;
}

function isMarketingDispatcherUser()
{
    return Auth::user()->type == 7 ? 1 : 0;
}

function isThirdPartyUser()
{
    return Auth::user()->type == 8 ? 1 : 0;
}

function isTaleSalesUser()
{
    return Auth::user()->type == 9 ? 1 : 0;
}
function isCreUser()
{
    return Auth::user()->type == 13 ? 1 : 0;
}
function userHasAcccess($userType)
{
    $accessTypes = getUsersAccess(Auth::user()->type);

    $accessTypesList = [];
    foreach ($accessTypes as $key => $value) {
        $accessTypesList[] = $value['id'];
    }

    if (in_array($userType, $accessTypesList)) {
        return true;
    } else {
        return false;
    }
}



function getUsersAccess($userType)
{
    $accessArray = [];

    $AllUserTypes = getUserTypes();

    if ($userType == 0) {
        $accessIds = [0, 1, 11];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 1) {
        $accessIds = [1, 2,];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 2) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 3) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 4) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 5) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 6) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 7) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 101) {
        $accessIds = [3, 4];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 102) {
        $accessIds = [3, 4];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 103) {
        $accessIds = [3, 4];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 104) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 105) {
        $accessIds = [];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    } elseif ($userType == 11) {
        $accessIds = [0, 1, 11];

        foreach ($accessIds as $key => $value) {
            $accessArray[$key] = $AllUserTypes[$value];
        }
    }
    return $accessArray;
}




function UsersNotificationTokens($userId)
{
    $notificationTokens = [];
    $Users = User::select('fcm_token')
        ->whereIn('id', $userId)
        ->orWhere('type', 0)
        ->get();
    if (count($Users) > 0) {
        foreach ($Users as $keyPush => $valuePush) {
            $notificationTokens[] = $valuePush->fcm_token;
        }
    }

    return $notificationTokens;
}

function getServiceHierarchyStatusLable($serviceHierarchyStatus)
{
    $serviceHierarchyStatus = (int) $serviceHierarchyStatus;

    if ($serviceHierarchyStatus == 0) {
        $serviceHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
    } elseif ($serviceHierarchyStatus == 1) {
        $serviceHierarchyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
    } elseif ($serviceHierarchyStatus == 2) {
        $serviceHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
    }
    return $serviceHierarchyStatus;
}

function isServiceExecutiveUser()
{
    return Auth::user()->type == 11 ? 1 : 0;
}

function getParentServiceExecutivesIds($userId)
{
    $ServiceExecutives = Wlmst_ServiceExecutive::select('reporting_manager_id')
        ->where('user_id', $userId)
        ->first();
    $ServiceExecutivessIds = [];
    if ($ServiceExecutives) {
        if ($ServiceExecutives->reporting_manager_id == 0) {
            return [0];
        } else {
            $ServiceExecutivessIds[] = $ServiceExecutives->reporting_manager_id;

            $getParentsServiceExecutivessIds = getParentServiceExecutivesIds($ServiceExecutives->reporting_manager_id);

            $ServiceExecutivessIds = array_merge($ServiceExecutivessIds, $getParentsServiceExecutivessIds);
        }
    } else {
        return [0];
    }
    $ServiceExecutivessIds = array_unique($ServiceExecutivessIds);
    $ServiceExecutivessIds = array_values($ServiceExecutivessIds);
    return $ServiceExecutivessIds;
}



function GSTPercentage()
{
    return 18;
}



function getPreviousMonths($noOfMonth)
{
    $GMTDateTime = date('Y-m-d H:i:s');
    $TIMEZONE = websiteTimeZone();
    $dt = new DateTime('@' . strtotime($GMTDateTime));
    $dt->setTimeZone(new DateTimeZone($TIMEZONE));
    $myCurrentDate = $dt->format('Y-m-d H:i:s');

    $r = [];

    for ($i = 0; $i < $noOfMonth; $i++) {
        if ($i != 0) {
            $myCurrentDate = date('Y-m-d H:i:s', strtotime($myCurrentDate . ' -1 month'));
        }

        $r[$i]['start'] = date('Y-m-1 00:00:00', strtotime($myCurrentDate));
        $r[$i]['end'] = date('Y-m-t 23:59:59', strtotime($myCurrentDate));
        $r[$i]['name'] = date('Y-F', strtotime($myCurrentDate));

        $start = new DateTime($r[$i]['start'], new DateTimeZone($TIMEZONE));
        $start->setTimeZone(new DateTimeZone('GMT'));

        $end = new DateTime($r[$i]['end'], new DateTimeZone($TIMEZONE));
        $end->setTimeZone(new DateTimeZone('GMT'));

        $r[$i]['start_gmt'] = $start->format('Y-m-d H:i:s');
        $r[$i]['end_gmt'] = $end->format('Y-m-d H:i:s');
    }

    return $r;
}

function displayStringLenth($string, $maxLength)
{
    $totalStringLenth = strlen($string);
    if ($totalStringLenth > $maxLength) {
        $stringCrop = substr($string, 0, $maxLength - 3);
        $string = $stringCrop . '...';
    }
    return $string;
}

function getUserNotificationTypes()
{
    $userTypes = [];
    $userTypes[1]['id'] = 1;
    $userTypes[1]['description'] = 'Inquiry Update';
    $userTypes[1]['assigned'] = 0;
    $userTypes[1]['mentioned'] = 0;

    $userTypes[2]['id'] = 2;
    $userTypes[2]['description'] = 'Inquiry Update Reply';
    $userTypes[2]['assigned'] = 0;
    $userTypes[2]['mentioned'] = 0;

    $userTypes[3]['id'] = 3;
    $userTypes[3]['description'] = 'Inquiry change assigned';
    $userTypes[3]['assigned'] = 1;
    $userTypes[3]['mentioned'] = 0;

    $userTypes[4]['id'] = 4;
    $userTypes[4]['description'] = 'Inquiry mentioned ';
    $userTypes[4]['assigned'] = 0;
    $userTypes[4]['mentioned'] = 1;

    return $userTypes;
}

function saveUserNotification($params)
{
    if (!isset($params['inquiry_id'])) {
        $params['inquiry_id'] = 0;
    }

    $UserNotification = new UserNotification();
    $UserNotification->user_id = $params['user_id'];
    $UserNotification->type = $params['type'];
    $UserNotification->from_user_id = $params['from_user_id'];
    $UserNotification->title = $params['title'];
    $UserNotification->description = $params['description'];
    $UserNotification->inquiry_id = $params['inquiry_id'];
    $UserNotification->save();
}


function getMyPrivilege($code)
{
    $hasPrivilege = 0;
    if (Auth::user()->privilege != '') {
        $privilege = json_decode(Auth::user()->privilege, true);
        if (isset($privilege[$code]) && $privilege[$code] == 1) {
            $hasPrivilege = 1;
        }
    }
    return $hasPrivilege;
}

function configrationForNotify()
{
    $response = [];
    $response['from_email'] = 'noreply@whitelion.in';
    $response['from_name'] = 'Whitelion';
    $response['to_name'] = 'Whitelion';

    ////TESTING
    $response['test_email'] = 'ankit.in1184@gmail.com';
    // $response['test_email'] = 'sheliyad.03@gmail.com';
    $response['test_phone_number'] = "9824717656";
    // $response['test_phone_number'] = '9016202912';
    $response['test_email_bcc'] = ['akshitaasalaliya16@gmail.com'];
    $response['test_email_cc'] = ['ankit.in1184@gmail.com'];
    return $response;
}


function sendOTPToMobile($mobileNumber, $otp)
{
    if (Config::get('app.env') == 'local') {
        $mobileNumber = '9016202912'; // LEEKIN
        // $mobileNumber = "9081187602"; // AKSHITA
    }
    $curl = curl_init();
    curl_setopt_array($curl, [
        // CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=624fe2f9427ab2782b2fae2b&mobile=" . $mobileNumber . "&authkey=124116Awe37ib8e57e66f9b&otp=" . $otp,
        CURLOPT_URL => 'https://api.msg91.com/api/v5/otp?template_id=6486f14ed6fc0567113a2fa2&mobile=' . $mobileNumber . '&authkey=124116Awe37ib8e57e66f9b&otp=' . $otp,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '',
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        //echo "cURL Error #:" . $err;
        $return = errorRes('');
        $return['response'] = $err;
        return $return;
    } else {
        $return = successRes('');
        $return['response'] = $response;
        return $return;
    }
}

function sendNotificationTOAndroid($title, $message, $FcmToken, $screenName, $data_value, $image = '')
{
    if (count($FcmToken) > 0) {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAAjO_9mB8:APA91bFUg5s0ou4vzSmuf6EqTLNu3bLpOXJa-v8GwW9HHzC-27ZtEUFloHiMx0Itc6ZhuN3MOitsjG1eRaV5RjDInoSqT4veSXu-TqnyGL_bFkSIH0hIYUmxB6YA77vVenEWPraVR1ma';

        $data = [
            'registration_ids' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'sound' => 'Default',
                'badge' => 1,
                'image' => $image,
            ],
            'data' => [
                'priority' => 'high',
                'sound' => 'default',
                'content_available' => true,
                'screen' => $screenName,
                'data_value' => json_encode($data_value),
            ],
        ];
        $encodedData = json_encode($data);

        $headers = ['Authorization:key=' . $serverKey, 'Content-Type: application/json'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post

        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);
        // return $result;
        $noti_responce = json_decode($result);
        $response = [];
        $response['status'] = $noti_responce->success;
        $response['status_code'] = $noti_responce->success == 1 ? 200 : 400;
        $response['msg'] = $noti_responce->success == 1 ? 'Notification Send Successfully' : 'Notification Failed';
        $response['noti_msg'] = $noti_responce;
    } else {
        $response = [];
        $response['status'] = 0;
        $response['status_code'] = 400;
        $response['msg'] = 'No Token';
        $response['noti_msg'] = '';
    }

    return $response;
}

// -------------------- QUOTATION GLOBLE CREATED START --------------------
function getCheckAppVersion($appsource, $appversion)
{
    $alreadyName = wlmst_appversion::query();

    $alreadyName->where('source', $appsource);
    $alreadyName->where('version', $appversion);
    $alreadyName->where('isactive', 1);
    $alreadyName = $alreadyName->first();

    if ($alreadyName) {
        return true;
    } else {
        return false;
    }
}


function getQuotationMasterStatusLable($mainMasterStatus)
{
    $mainMasterStatus = (int) $mainMasterStatus;

    if ($mainMasterStatus == 0) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-running font-size-11"> Running</span>';
    } elseif ($mainMasterStatus == 1) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> New Request</span>';
    } elseif ($mainMasterStatus == 2) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-change-request font-size-11"> Change Request</span>';
    } elseif ($mainMasterStatus == 3) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-confirm font-size-11"> Confirm Quotation</span>';
    } elseif ($mainMasterStatus == 4) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Rejected Quotation</span>';
    } elseif ($mainMasterStatus == 5) {
        $mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Dis. Approval</span>';
    }
    return $mainMasterStatus;
}
function getQuotationMasterStatusLableText($mainMasterStatus)
{
    $mainMasterStatus = (int) $mainMasterStatus;

    if ($mainMasterStatus == 0) {
        $mainMasterStatus = 'Running';
    } elseif ($mainMasterStatus == 1) {
        $mainMasterStatus = 'New Request';
    } elseif ($mainMasterStatus == 2) {
        $mainMasterStatus = 'Change Request';
    } elseif ($mainMasterStatus == 3) {
        $mainMasterStatus = 'Confirm';
    } elseif ($mainMasterStatus == 4) {
        $mainMasterStatus = 'Rejected';
    } elseif ($mainMasterStatus == 5) {
        $mainMasterStatus = 'Dis. Approval';
    }
    return $mainMasterStatus;
}

function getQuotationStatus()
{
    $QuotStatusTypes = [];
    $QuotStatusTypes[0]['id'] = 0;
    $QuotStatusTypes[0]['name'] = 'Running';
    $QuotStatusTypes[0]['short_name'] = 'RUNNING';
    $QuotStatusTypes[0]['sequence'] = 5;

    $QuotStatusTypes[1]['id'] = 1;
    $QuotStatusTypes[1]['name'] = 'New Request';
    $QuotStatusTypes[1]['short_name'] = 'NEW REQUEST';
    $QuotStatusTypes[1]['sequence'] = 1;

    $QuotStatusTypes[2]['id'] = 2;
    $QuotStatusTypes[2]['name'] = 'Change Request';
    $QuotStatusTypes[2]['short_name'] = 'CHANGE REQUEST';
    $QuotStatusTypes[2]['sequence'] = 2;

    $QuotStatusTypes[3]['id'] = 3;
    $QuotStatusTypes[3]['name'] = 'Confirm';
    $QuotStatusTypes[3]['short_name'] = 'CONFIRM';
    $QuotStatusTypes[3]['sequence'] = 3;

    $QuotStatusTypes[4]['id'] = 4;
    $QuotStatusTypes[4]['name'] = 'Rejected';
    $QuotStatusTypes[4]['short_name'] = 'REJECTED';
    $QuotStatusTypes[4]['sequence'] = 4;

    $QuotStatusTypes[5]['id'] = 5;
    $QuotStatusTypes[5]['name'] = 'Dis. Approval';
    $QuotStatusTypes[5]['short_name'] = 'DISCOUNT APPROVAL';
    $QuotStatusTypes[5]['sequence'] = 5;

    return $QuotStatusTypes;
}


// FOR USER ACTION START
function getUserNoteList($user_id)
{
    $UserUpdateList = UserNotes::query();
    $UserUpdateList->select('user_notes.id', 'user_notes.note', 'user_notes.user_id', 'user_notes.note_type', 'user_notes.note_title', 'created.first_name', 'created.last_name', 'user_notes.created_at');
    $UserUpdateList->leftJoin('users as created', 'created.id', '=', 'user_notes.entryby');
    $UserUpdateList->where('user_notes.user_id', $user_id);
    $UserUpdateList->orderBy('user_notes.id', 'desc');
    $UserUpdateList->limit(5);
    $UserUpdateList = $UserUpdateList->get();
    $UserUpdateList = json_encode($UserUpdateList);
    $UserUpdateList = json_decode($UserUpdateList, true);

    foreach ($UserUpdateList as $key => $value) {
        $UserUpdateList[$key]['message'] = strip_tags($value['note']);

        $UserUpdateList[$key]['created_at'] = convertDateTime($value['created_at']);
        $UserUpdateList[$key]['date'] = convertDateAndTime($value['created_at'], 'date');
        $UserUpdateList[$key]['time'] = convertDateAndTime($value['created_at'], 'time');
    }
    $data = [];
    $data['updates'] = $UserUpdateList;
    $response['view'] = view('user_action/detail_tab/detail_notes_tab', compact('data'))->render();
    $response['data'] = $UserUpdateList;
    return $response;
}

function getUserContactList($user_id)
{
    $UserContact = UserContact::query();
    $UserContact->select('crm_setting_contact_tag.name as tag_name', 'user_contact.*');
    $UserContact->leftJoin('crm_setting_contact_tag', 'crm_setting_contact_tag.id', '=', 'user_contact.contact_tag_id');
    $UserContact->where('user_contact.user_id', $user_id);
    $UserContact->orderBy('user_contact.id', 'desc');
    $UserContact->limit(5);
    $UserContact = $UserContact->get();

    foreach ($UserContact as $key => $value) {
        $UserContact[$key]['message'] = strip_tags($value['note']);
        $UserContact[$key]['created_at'] = $value['created_at'];
        $UserContact[$key]['date'] = convertDateAndTime($value['created_at'], 'date');
        $UserContact[$key]['time'] = convertDateAndTime($value['created_at'], 'time');
    }
    $data = [];
    $data['contacts'] = $UserContact;
    $data['user']['id'] = $user_id;
    $response['view'] = view('user_action/detail_tab/detail_contact_tab', compact('data'))->render();
    $response['data'] = $UserContact;
    return $response;
}

function getUserFileList($user_id)
{
    $UserFile = UserFiles::query();
    $UserFile->select('crm_setting_file_tag.name as tag_name', 'user_files.*', 'users.first_name', 'users.last_name');
    $UserFile->leftJoin('crm_setting_file_tag', 'crm_setting_file_tag.id', '=', 'user_files.file_tag_id');
    $UserFile->leftJoin('users', 'users.id', '=', 'user_files.entryby');
    $UserFile->where('user_files.user_id', $user_id);
    $UserFile->limit(5);
    $UserFile->orderBy('user_files.id', 'desc');
    $UserFile = $UserFile->get();
    $UserFile = json_encode($UserFile);
    $UserFile = json_decode($UserFile, true);

    foreach ($UserFile as $key => $value) {
        $name = explode('/', $value['name']);

        $UserFile[$key]['name'] = end($name);
        $UserFile[$key]['download'] = getSpaceFilePath($value['name']);
        $UserFile[$key]['created_at'] = convertDateTime($value['created_at']);
    }
    $data = [];
    $data['user']['id'] = $user_id;
    $data['files'] = $UserFile;
    $response['view'] = view('user_action/detail_tab/detail_file_tab', compact('data'))->render();
    $response['data'] = $UserFile;
    return $response;
}

function getUserAllOpenList($user_id)
{
    // ACTION CALL START
    $UserCall = UserCallAction::query();
    $UserCall->select('user_call_action.*', 'users.first_name', 'users.last_name');
    $UserCall->where('user_call_action.user_id', $user_id);
    $UserCall->where('is_closed', 0);
    $UserCall->leftJoin('users', 'users.id', '=', 'user_call_action.user_id');
    $UserCall->orderBy('user_call_action.id', 'desc');
    $UserCall = $UserCall->get();
    $UserCall = json_encode($UserCall);
    $UserCall = json_decode($UserCall, true);
    foreach ($UserCall as $key => $value) {
        $UserCall[$key]['date'] = convertDateAndTime($value['call_schedule'], 'date');
        $UserCall[$key]['time'] = convertDateAndTime($value['call_schedule'], 'time');
        $ContactName = UserContact::select('user_contact.id', 'user_contact.first_name', 'user_contact.last_name', DB::raw("CONCAT(user_contact.first_name,' ',user_contact.last_name) AS text"));
        $ContactName->where('user_contact.id', $value['contact_person']);
        $ContactName = $ContactName->first();
        if ($ContactName) {
            $UserCall[$key]['contact_name'] = $ContactName->text;
        } else {
            $UserCall[$key]['contact_name'] = '';
        }
    }
    // ACTION CALL END

    //  ACTION MEETING START
    $UserMeeting = UserMeetingAction::query();
    $UserMeeting->select('user_meeting_action.*', 'users.first_name', 'users.last_name');
    $UserMeeting->where('user_meeting_action.user_id', $user_id);
    $UserMeeting->where('is_closed', 0);
    $UserMeeting->leftJoin('users', 'users.id', '=', 'user_meeting_action.user_id');
    $UserMeeting->orderBy('user_meeting_action.id', 'desc');
    $UserMeeting = $UserMeeting->get();
    $UserMeeting = json_encode($UserMeeting);
    $UserMeeting = json_decode($UserMeeting, true);
    foreach ($UserMeeting as $key => $value) {
        $UserMeeting[$key]['date'] = convertDateAndTime($value['meeting_date_time'], 'date');
        $UserMeeting[$key]['time'] = convertDateAndTime($value['meeting_date_time'], 'time');

        $UserMeetingTitle = CRMSettingMeetingTitle::select('name')
            ->where('id', $value['title_id'])
            ->first();

        if ($UserMeetingTitle) {
            $UserMeeting[$key]['title_name'] = $UserMeetingTitle->name;
        } else {
            $UserMeeting[$key]['title_name'] = $UserMeetingTitle->name;
        }



        $UserResponse = '';
        if (count($ContactIds) > 0) {
            $LeadContact = UserContact::select('user_contact.id', 'user_contact.first_name', 'user_contact.last_name', DB::raw("CONCAT(user_contact.first_name,' ',user_contact.last_name) AS full_name"));
            $LeadContact->whereIn('user_contact.id', $ContactIds);
            $LeadContact = $LeadContact->get();
            if (count($LeadContact) > 0) {
                foreach ($LeadContact as $User_key => $User_value) {
                    $UserResponse .= 'Contact - ' . $User_value['full_name'] . '<br>';
                }
            }
        }

        if (count($UsersId) > 0) {
            $User = User::select('users.id', 'users.type', 'users.first_name', 'users.last_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS full_name"));
            $User->whereIn('users.id', $UsersId);
            $User = $User->get();
            $getAllUserTypes = getAllUserTypes();
            if (count($User) > 0) {
                foreach ($User as $User_key => $User_value) {
                    $UserResponse .= $getAllUserTypes[$User_value['type']]['short_name'] . ' - ' . $User_value['full_name'] . '<br>';
                }
            }
        }

        if ($UserResponse) {
            $UserMeeting[$key]['meeting_participant'] = $UserResponse;
        } else {
            $UserMeeting[$key]['meeting_participant'] = '';
        }
    }
    //  ACTION MEETING END

    // ACTION TASK START
    $UserTask = UserTaskAction::query();
    $UserTask->select('user_task_action.*', 'users.first_name', 'users.last_name');
    $UserTask->where('user_task_action.user_id', $user_id);
    $UserTask->where('is_closed', 0);
    $UserTask->leftJoin('users', 'users.id', '=', 'user_task_action.user_id');
    $UserTask->orderBy('user_task_action.id', 'desc');
    $UserTask = $UserTask->get();
    $UserTask = json_encode($UserTask);
    $UserTask = json_decode($UserTask, true);
    foreach ($UserTask as $key => $value) {
        $UserTask[$key]['date'] = convertDateAndTime($value['due_date_time'], 'date');
        $UserTask[$key]['time'] = convertDateAndTime($value['due_date_time'], 'time');

        $Taskowner = User::select('users.id', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS text"));
        $Taskowner->where('users.status', 1);
        $Taskowner->where('users.id', $value['assign_to']);
        $Taskowner = $Taskowner->first();

        if ($Taskowner) {
            $UserTask[$key]['task_owner'] = $Taskowner->text;
        } else {
            $UserTask[$key]['task_owner'] = ' ';
        }
    }
    // ACTION TASK END

    $data = [];
    $data['calls'] = $UserCall;
    $data['meetings'] = $UserMeeting;
    $data['task'] = $UserTask;
    $data['max_open_actions'] = max(count($UserCall), count($UserMeeting), count($UserTask));
    $data['user']['id'] = $user_id;
    $response['view'] = view('user_action/detail_tab/detail_open_action_tab', compact('data'))->render();
    $response['max_open_actions'] = max(count($UserCall), count($UserMeeting), count($UserTask));
    $response['call_data'] = $UserCall;
    $response['meeting_data'] = $UserMeeting;
    $response['task_data'] = $UserTask;
    return $response;
}

function getUserAllCloseList($user_id)
{
    // ACTION CLOSE CALL START
    $UserCallClosed = UserCallAction::query();
    $UserCallClosed->select('user_call_action.*', 'users.first_name', 'users.last_name');
    $UserCallClosed->where('user_call_action.user_id', $user_id);
    $UserCallClosed->where('is_closed', 1);
    $UserCallClosed->leftJoin('users', 'users.id', '=', 'user_call_action.user_id');
    $UserCallClosed->orderBy('user_call_action.closed_date_time', 'desc');
    $UserCallClosed = $UserCallClosed->get();
    $UserCallClosed = json_encode($UserCallClosed);
    $UserCallClosed = json_decode($UserCallClosed, true);
    foreach ($UserCallClosed as $key => $value) {
        $UserCallClosed[$key]['date'] = convertDateAndTime($value['closed_date_time'], 'date');
        $UserCallClosed[$key]['time'] = convertDateAndTime($value['closed_date_time'], 'time');
        $ContactName = UserContact::select('user_contact.id', 'user_contact.first_name', 'user_contact.last_name', DB::raw("CONCAT(user_contact.first_name,' ',user_contact.last_name) AS text"));
        $ContactName->where('user_contact.id', $value['contact_person']);
        $ContactName = $ContactName->first();
        if ($ContactName) {
            $UserCallClosed[$key]['contact_name'] = $ContactName->text;
        } else {
            $UserCallClosed[$key]['contact_name'] = '';
        }
    }
    // ACTION CLOSE CALL END

    // ACTION CLOSE MEETING START
    $UserMeetingClosed = UserMeetingAction::query();
    $UserMeetingClosed->select('user_meeting_action.*', 'users.first_name', 'users.last_name');
    $UserMeetingClosed->where('user_meeting_action.user_id', $user_id);
    $UserMeetingClosed->where('is_closed', 1);
    $UserMeetingClosed->leftJoin('users', 'users.id', '=', 'user_meeting_action.user_id');
    $UserMeetingClosed->orderBy('user_meeting_action.closed_date_time', 'desc');
    $UserMeetingClosed = $UserMeetingClosed->get();
    $UserMeetingClosed = json_encode($UserMeetingClosed);
    $UserMeetingClosed = json_decode($UserMeetingClosed, true);
    foreach ($UserMeetingClosed as $key => $value) {
        $UserMeetingClosed[$key]['date'] = convertDateAndTime($value['closed_date_time'], 'date');
        $UserMeetingClosed[$key]['time'] = convertDateAndTime($value['closed_date_time'], 'time');

        $UserMeetingTitle = CRMSettingMeetingTitle::select('name')
            ->where('id', $value['title_id'])
            ->first();
        if ($UserMeetingTitle) {
            $UserMeetingClosed[$key]['title_name'] = $UserMeetingTitle->name;
        } else {
            $UserMeetingClosed[$key]['title_name'] = ' ';
        }

        $UserMeetingParticipant = UserMeetingParticipant::where('meeting_id', $value['id'])
            ->orderby('id', 'asc')
            ->get();
        $UserMeetingParticipant = json_decode(json_encode($UserMeetingParticipant), true);

        $UsersId = [];
        $ContactIds = [];
        foreach ($UserMeetingParticipant as $sales_key => $value) {
            if ($value['type'] == 'users') {
                $UsersId[] = $value['participant_id'];
            } elseif ($value['type'] == 'lead_contacts') {
                $ContactIds[] = $value['participant_id'];
            }
        }

        $UserResponse = '';
        if (count($ContactIds) > 0) {
            $LeadContact = UserContact::select('user_contact.id', DB::raw("CONCAT(user_contact.first_name,' ',user_contact.last_name) AS full_name"));
            $LeadContact->whereIn('user_contact.id', $ContactIds);
            $LeadContact = $LeadContact->get();
            if (count($LeadContact) > 0) {
                foreach ($LeadContact as $User_key => $User_value) {
                    $UserResponse .= 'Contact - ' . $User_value['full_name'] . '<br>';
                }
            }
        }

        if (count($UsersId) > 0) {
            $User = User::select('users.id', 'users.type', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS full_name"));
            $User->whereIn('users.id', $UsersId);
            $User = $User->get();
            if (count($User) > 0) {
                foreach ($User as $User_key => $User_value) {
                    $UserResponse .= getAllUserTypes()[$User_value['type']]['short_name'] . ' - ' . $User_value['full_name'] . '<br>';
                }
            }
        }

        if ($UserResponse) {
            $UserMeetingClosed[$key]['meeting_participant'] = $UserResponse;
        } else {
            $UserMeetingClosed[$key]['meeting_participant'] = '';
        }
    }
    // ACTION CLOSE MEETING END

    // ACTION CLOSE TASK START
    $UserTaskClosed = UserTaskAction::query();
    $UserTaskClosed->select('user_task_action.*', 'users.first_name', 'users.last_name');
    $UserTaskClosed->where('user_task_action.user_id', $user_id);
    $UserTaskClosed->where('is_closed', 1);
    $UserTaskClosed->leftJoin('users', 'users.id', '=', 'user_task_action.user_id');
    $UserTaskClosed->orderBy('user_task_action.closed_date_time', 'desc');
    $UserTaskClosed = $UserTaskClosed->get();
    $UserTaskClosed = json_encode($UserTaskClosed);
    $UserTaskClosed = json_decode($UserTaskClosed, true);
    foreach ($UserTaskClosed as $key => $value) {
        $UserTaskClosed[$key]['date'] = convertDateAndTime($value['closed_date_time'], 'date');
        $UserTaskClosed[$key]['time'] = convertDateAndTime($value['closed_date_time'], 'time');

        $Taskowner = User::select('users.id', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS text"));
        $Taskowner->where('users.status', 1);
        $Taskowner->where('users.id', $value['assign_to']);
        $Taskowner = $Taskowner->first();

        if ($Taskowner) {
            $UserTaskClosed[$key]['task_owner'] = $Taskowner->text;
        } else {
            $UserTaskClosed[$key]['task_owner'] = ' ';
        }
    }
    // ACTION CLOSE TASK END

    $data = [];
    $data['calls_closed'] = $UserCallClosed;
    $data['meetings_closed'] = $UserMeetingClosed;
    $data['task_closed'] = $UserTaskClosed;
    $data['max_close_actions'] = max(count($UserCallClosed), count($UserMeetingClosed), count($UserTaskClosed));
    $data['user']['id'] = $user_id;
    $response['view'] = view('user_action/detail_tab/detail_close_action_tab', compact('data'))->render();
    $response['max_close_actions'] = max(count($UserCallClosed), count($UserMeetingClosed), count($UserTaskClosed));
    $response['close_call_data'] = $UserCallClosed;
    $response['close_meeting_data'] = $UserMeetingClosed;
    $response['close_task_data'] = $UserTaskClosed;
    return $response;
}

function getUserTimelineList($user_id)
{
    $UserLog = UserLog::select('user_log.*', 'users.first_name', 'users.last_name')
        ->leftJoin('users', 'users.id', '=', 'user_log.user_id')
        ->where('user_log.user_id', $user_id)
        ->orderBy('id', 'desc')
        ->get();

    $UserLog = json_encode($UserLog);
    $UserLog = json_decode($UserLog, true);

    $repeated_date = '';
    foreach ($UserLog as $key => $value) {
        $date = convertDateAndTime($value['created_at'], 'date');
        if ($repeated_date == $date) {
            $UserLog[$key]['date'] = 0;
        } else {
            $repeated_date = $date;
            $UserLog[$key]['date'] = convertDateAndTime($value['created_at'], 'date');
        }
        $UserLog[$key]['created_date'] = convertDateAndTime($value['created_at'], 'date');
        $UserLog[$key]['time'] = convertDateAndTime($value['created_at'], 'time');
        $UserLog[$key]['created_at'] = convertDateTime($value['created_at']);
        $UserLog[$key]['updated_at'] = convertDateTime($value['updated_at']);
    }

    $data['timeline'] = $UserLog;

    $data = [];
    $data['user']['id'] = $user_id;
    $data['timeline'] = $UserLog;
    $response['view'] = view('user_action/detail_tab/detail_user_timeline_tab', compact('data'))->render();
    $response['data'] = $UserLog;
    return $response;
}
function getUserPointLogList($user_id)
{
    $selectColumnsPointlog = ['crm_log.description', 'crm_log.created_at', 'crm_log.updated_at', 'users.first_name', 'users.last_name'];

    $QryPointLog = CRMLog::query();
    $QryPointLog->select($selectColumnsPointlog);
    $QryPointLog->leftJoin('users', 'users.id', '=', 'crm_log.user_id');
    $QryPointLog->where('crm_log.for_user_id', $user_id);
    $QryPointLog->whereIn('crm_log.name', ['point-gain', 'point-redeem', 'point-back', 'point-lose']);
    $QryPointLog = $QryPointLog->get();

    $QryPointLog = json_encode($QryPointLog);
    $QryPointLog = json_decode($QryPointLog, true);

    $repeated_date = '';
    foreach ($QryPointLog as $key => $value) {
        $date = convertDateAndTime($value['created_at'], 'date');
        if ($repeated_date == $date) {
            $QryPointLog[$key]['date'] = 0;
        } else {
            $repeated_date = $date;
            $QryPointLog[$key]['date'] = convertDateAndTime($value['created_at'], 'date');
        }
        $QryPointLog[$key]['created_date'] = convertDateAndTime($value['created_at'], 'date');
        $QryPointLog[$key]['time'] = convertDateAndTime($value['created_at'], 'time');
        $QryPointLog[$key]['created_at'] = convertDateTime($value['created_at']);
        $QryPointLog[$key]['updated_at'] = convertDateTime($value['updated_at']);
    }

    $data['timeline'] = $QryPointLog;

    $data = [];
    $data['user']['id'] = $user_id;
    $data['timeline'] = $QryPointLog;
    $response['view'] = view('user_action/detail_tab/detail_user_point_log_tab', compact('data'))->render();
    $response['data'] = $QryPointLog;
    return $response;
}

function getUserLogList($user_id)
{
    $selectColumnsPointlog = ['user_log.description', 'user_log.created_at', 'user_log.updated_at', 'users.first_name', 'users.last_name'];

    $QryUserLog = UserLog::query();
    $QryUserLog->select($selectColumnsPointlog);
    $QryUserLog->leftJoin('users', 'users.id', '=', 'user_log.entryby');
    // $QryUserLog->leftJoin('users', 'users.id', '=', 'user_log.user_id');
    $QryUserLog->where('user_log.reference_id', $user_id);
    $QryUserLog->orderBy('user_log.id', 'DESC');
    $QryUserLog = $QryUserLog->get();

    $QryUserLog = json_encode($QryUserLog);
    $QryUserLog = json_decode($QryUserLog, true);

    $repeated_date = '';
    foreach ($QryUserLog as $key => $value) {
        $date = convertDateAndTime($value['created_at'], 'date');
        if ($repeated_date == $date) {
            $QryUserLog[$key]['date'] = 0;
        } else {
            $repeated_date = $date;
            $QryUserLog[$key]['date'] = convertDateAndTime($value['created_at'], 'date');
        }
        $QryUserLog[$key]['created_date'] = convertDateAndTime($value['created_at'], 'date');
        $QryUserLog[$key]['time'] = convertDateAndTime($value['created_at'], 'time');
        $QryUserLog[$key]['created_at'] = convertDateTime($value['created_at']);
        $QryUserLog[$key]['updated_at'] = convertDateTime($value['updated_at']);
    }

    $data['timeline'] = $QryUserLog;

    $data = [];
    $data['user']['id'] = $user_id;
    $data['timeline'] = $QryUserLog;
    $response['view'] = view('user_action/detail_tab/detail_user_user_log_tab', compact('data'))->render();
    $response['data'] = $QryUserLog;
    return $response;
}

// FOR USER ACTION START

function saveUserLog($params)
{
    $UserLog = new UserLog();
    $UserLog->user_id = $params['user_id'];
    $UserLog->log_type = $params['log_type'];
    $UserLog->field_name = $params['field_name'];
    $UserLog->old_value = $params['old_value'];
    $UserLog->new_value = $params['new_value'];
    $UserLog->reference_type = $params['reference_type'];
    $UserLog->reference_id = $params['reference_id'];
    $UserLog->transaction_type = $params['transaction_type'];
    $UserLog->description = $params['description'];
    $UserLog->source = $params['source'];
    $UserLog->entryby = Auth::user()->id;
    $UserLog->entryip = $params['ip'];
    $UserLog->save();
}


function getTimeSlot()
{
    $timeSlot = [];
    $strtotimeStart = strtotime(date('h:00:00'));
    $latestDateTime = date('h:00:00', $strtotimeStart);
    $i = 0;
    $timeSlot[$i] = date('h:i A', strtotime($latestDateTime));
    for ($i = 1; $i < 48; $i++) {
        $timeSlot[$i] = date('h:i A', strtotime($latestDateTime . ' +30 minutes'));
        $latestDateTime = $timeSlot[$i];
    }
    return $timeSlot;
}

function getReminderTimeSlot($dateTime = '')
{
    if ($dateTime != 0 && $dateTime != '') {
        $dateTime = $dateTime;
    } else {
        $dateTime = date('Y-m-d H:i:s');
    }

    $reminderTimeSlot = [];
    $reminderTimeSlot[1]['id'] = 1;
    $reminderTimeSlot[1]['name'] = '15 Min Before';
    $reminderTimeSlot[1]['datetime'] = date('Y-m-d H:i:s', strtotime($dateTime . ' -15 minutes'));
    $reminderTimeSlot[1]['minute'] = 15;

    $reminderTimeSlot[2]['id'] = 2;
    $reminderTimeSlot[2]['name'] = '30 Min Before';
    $reminderTimeSlot[2]['datetime'] = date('Y-m-d H:i:s', strtotime($dateTime . ' -30 minutes'));
    $reminderTimeSlot[2]['minute'] = 30;

    $reminderTimeSlot[3]['id'] = 3;
    $reminderTimeSlot[3]['name'] = '1 Hour Before';
    $reminderTimeSlot[3]['datetime'] = date('Y-m-d H:i:s', strtotime($dateTime . ' -1 hours'));
    $reminderTimeSlot[3]['minute'] = 60;

    $reminderTimeSlot[4]['id'] = 4;
    $reminderTimeSlot[4]['name'] = '1 Day Before';
    $reminderTimeSlot[4]['datetime'] = date('Y-m-d H:i:s', strtotime($dateTime . ' -1 days'));
    $reminderTimeSlot[4]['minute'] = 1440; // (24*60)

    return $reminderTimeSlot;
}


function getIntervalTime()
{
    $intervalTime = [];
    $intervalTime[1]['id'] = 1;
    $intervalTime[1]['name'] = '30 Min';
    $intervalTime[1]['code'] = ' +30 minutes ';
    $intervalTime[1]['minute'] = 30;

    $intervalTime[2]['id'] = 2;
    $intervalTime[2]['name'] = '1 Hour';
    $intervalTime[2]['code'] = ' +1 hours ';
    $intervalTime[2]['minute'] = 60;

    $intervalTime[3]['id'] = 3;
    $intervalTime[3]['name'] = '1.5 Hours';
    $intervalTime[3]['code'] = ' +1 hours +30 minutes ';
    $intervalTime[3]['minute'] = 90;

    $intervalTime[4]['id'] = 4;
    $intervalTime[4]['name'] = '2 Hours';
    $intervalTime[4]['code'] = ' +2 hours ';
    $intervalTime[4]['minute'] = 120;

    $intervalTime[5]['id'] = 5;
    $intervalTime[5]['name'] = '2.5 Hours';
    $intervalTime[5]['code'] = ' +2 hours +30 minutes ';
    $intervalTime[5]['minute'] = 150;

    $intervalTime[6]['id'] = 6;
    $intervalTime[6]['name'] = '3 Hours';
    $intervalTime[6]['code'] = ' +3 hours ';
    $intervalTime[6]['minute'] = 180;

    $intervalTime[7]['id'] = 7;
    $intervalTime[7]['name'] = '3.5 Hours';
    $intervalTime[7]['code'] = ' +3 hours +30 minutes ';
    $intervalTime[7]['minute'] = 210;

    $intervalTime[8]['id'] = 8;
    $intervalTime[8]['name'] = '4 Hours';
    $intervalTime[8]['code'] = ' +4 hours ';
    $intervalTime[8]['minute'] = 240;

    $intervalTime[9]['id'] = 9;
    $intervalTime[9]['name'] = '4.5 Hours';
    $intervalTime[9]['code'] = ' +4 hours +30 minutes ';
    $intervalTime[8]['minute'] = 270;

    $intervalTime[10]['id'] = 10;
    $intervalTime[10]['name'] = '5 Hours';
    $intervalTime[10]['code'] = ' +5 hours ';
    $intervalTime[10]['minute'] = 300;

    $intervalTime[11]['id'] = 11;
    $intervalTime[11]['name'] = '5.5 Hours';
    $intervalTime[11]['code'] = ' +5 hours +30 minutes ';
    $intervalTime[11]['minute'] = 330;

    $intervalTime[12]['id'] = 12;
    $intervalTime[12]['name'] = '6 Hours';
    $intervalTime[12]['code'] = ' +6 hours ';
    $intervalTime[12]['minute'] = 360;

    // $intervalTime[13]['id'] = 13;
    // $intervalTime[13]['name'] = "1.5 Hours";
    // $intervalTime[13]['code'] = " +1 hours +30 minute ";

    // $intervalTime[14]['id'] = 14;
    // $intervalTime[14]['name'] = "7 Hours";
    // $intervalTime[14]['code'] = " +1 hours +30 minute ";

    // $intervalTime[15]['id'] = 15;
    // $intervalTime[15]['name'] = "1.5 Hours";
    // $intervalTime[15]['code'] = " +1 hours +30 minute ";

    // $intervalTime[16]['id'] = 16;
    // $intervalTime[16]['name'] = "8 Hours";
    // $intervalTime[16]['code'] = " +1 hours +30 minute ";

    // $intervalTime[17]['id'] = 17;
    // $intervalTime[17]['name'] = "1.5 Hours";
    // $intervalTime[17]['code'] = " +1 hours +30 minute ";

    // $intervalTime[3]['id'] = 3;
    // $intervalTime[3]['name'] = "9 Hours";
    // $intervalTime[3]['code'] = " +1 hours +30 minute ";

    // $intervalTime[3]['id'] = 3;
    // $intervalTime[3]['name'] = "1.5 Hours";
    // $intervalTime[3]['code'] = " +1 hours +30 minute ";

    // $intervalTime[3]['id'] = 3;
    // $intervalTime[3]['name'] = "10 Hours";
    // $intervalTime[3]['code'] = " +1 hours +30 minute ";

    return $intervalTime;
}



function getTaskOutComeType()
{
    $outcome_value = [];

    $outcome_value[101]['id'] = 101;
    $outcome_value[101]['name'] = 'Installation Done';

    $outcome_value[102]['id'] = 102;
    $outcome_value[102]['name'] = 'Installation Request Not Received';

    return $outcome_value;
}

function getNotificationUserType()
{
    $userTypes = [];
    $userTypes[0]['id'] = 0;
    $userTypes[0]['name'] = 'Admin';
    $userTypes[0]['user_type'] = 0;

    $userTypes[1]['id'] = 1;
    $userTypes[1]['name'] = 'Company Admin';
    $userTypes[1]['user_type'] = 1;

    $userTypes[2]['id'] = 2;
    $userTypes[2]['name'] = 'Sales';
    $userTypes[2]['user_type'] = 2;

    $userTypes[201]['id'] = 201;
    $userTypes[201]['name'] = 'Architect(Non Prime)';
    $userTypes[201]['user_type'] = 201;

    $userTypes[202]['id'] = 202;
    $userTypes[202]['name'] = 'Architect(Prime)';
    $userTypes[202]['user_type'] = 202;

    $userTypes[301]['id'] = 301;
    $userTypes[301]['name'] = 'Electrician(Non Prime)';
    $userTypes[301]['user_type'] = 301;

    $userTypes[302]['id'] = 302;
    $userTypes[302]['name'] = 'Electrician(Prime)';
    $userTypes[302]['user_type'] = 302;

    $userTypes[101]['id'] = 101;
    $userTypes[101]['name'] = 'ASM(Autorise Stockist Merchantize)';
    $userTypes[101]['user_type'] = 101;

    $userTypes[102]['id'] = 102;
    $userTypes[102]['name'] = 'ADM(Authorize Distributor Merchantize)';
    $userTypes[102]['user_type'] = 102;

    $userTypes[103]['id'] = 103;
    $userTypes[103]['name'] = 'APM(Authorize Project Merchantize)';
    $userTypes[103]['user_type'] = 103;

    $userTypes[104]['id'] = 104;
    $userTypes[104]['name'] = 'AD(Authorised Dealer)';
    $userTypes[104]['user_type'] = 104;

    $userTypes[105]['id'] = 105;
    $userTypes[105]['name'] = 'Retailer';
    $userTypes[105]['user_type'] = 105;

    return $userTypes;
}

function UsersTypeNotificationTokens($userType)
{
    $notificationTokens = [];
    $Users = User::select('fcm_token')
        ->whereIn('type', $userType)
        ->get();
    if (count($Users) > 0) {
        foreach ($Users as $keyPush => $valuePush) {
            if ($valuePush->fcm_token != '' || $valuePush->fcm_token != null) {
                $notificationTokens[] = $valuePush->fcm_token;
            }
        }
    }
    return $notificationTokens;
}

function highlightString($str, $search_term)
{
    if (empty($search_term)) {
        return $str;
    }

    $pos = strpos(strtolower($str), strtolower($search_term));

    if ($pos !== false) {
        $replaced = substr($str, 0, $pos);
        $replaced .= '<span class="highlight">' . substr($str, $pos, strlen($search_term)) . '</span>';
        $replaced .= substr($str, $pos + strlen($search_term));
    } else {
        $replaced = $str;
    }

    return $replaced;
}

function saveNotificationScheduler($params)
{
    // return $params;
    $NotificationScheduler = new NotificationScheduler();
    $NotificationScheduler->from_mail = $params['from_email'];
    $NotificationScheduler->from_name = $params['from_name'];
    $NotificationScheduler->to_email = $params['to_email'];
    $NotificationScheduler->to_name = $params['to_name'];
    $NotificationScheduler->bcc_mail = $params['bcc_email'];
    $NotificationScheduler->cc_mail = $params['cc_email'];
    $NotificationScheduler->subject = $params['subject'];
    $NotificationScheduler->transaction_id = $params['transaction_id'];
    $NotificationScheduler->transaction_name = $params['transaction_name'];
    $NotificationScheduler->transaction_type = $params['transaction_type'];
    $NotificationScheduler->transaction_detail = $params['transaction_detail'];
    $NotificationScheduler->attachment = $params['attachment'];
    $NotificationScheduler->remark = $params['remark'];
    $NotificationScheduler->source = $params['source'];
    $NotificationScheduler->entryby = Auth::user()->id;
    $NotificationScheduler->entryip = $params['entryip'];
    $NotificationScheduler->save();
}
