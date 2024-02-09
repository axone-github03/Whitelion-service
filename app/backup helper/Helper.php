<?php

use App\Models\Architect;
use App\Models\ChannelPartner;
use App\Models\CityList;
use App\Models\CountryList;
use App\Models\CRMLog;
use App\Models\DebugLog;
use App\Models\Electrician;
use App\Models\Inquiry;
use App\Models\InquiryLog;
use App\Models\LeadTimeline;
use App\Models\MarketingProductLog;
use App\Models\ProductLog;
use App\Models\SalePerson;
use App\Models\StateList;
use App\Models\TeleSales;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\wlmst_appversion;
use App\Models\Wlmst_ServiceExecutive;
use App\Models\wlmst_user_created_board_log;
use Illuminate\Support\Facades\Storage;

function successRes($msg = "Success", $statusCode = 200)
{
	$return = array();
	$return['status'] = 1;
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}

function errorRes($msg = "Error", $statusCode = 400)
{

	$return = array();
	$return['status'] = 0;
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}

function getSpacesFolder()
{
	return $_SERVER['HTTP_HOST'];
}

function uploadFileOnSpaces($diskFilePath, $spaceFilePath)
{
	$spacesFolder = getSpacesFolder();
	return Storage::disk('spaces')->put($spacesFolder . "/" . $spaceFilePath, @file_get_contents($diskFilePath));
}

function getSpaceFilePath($filePath)
{
	$spacesFolder = getSpacesFolder();
	return "https://whitelion.sgp1.digitaloceanspaces.com/" . $spacesFolder . "" . $filePath;
}

function loadTextLimit($string, $limit)
{
	$string = htmlspecialchars_decode($string);
	if (strlen($string) > $limit) {
		return substr($string, 0, $limit - 3) . "...";
	} else {
		return $string;
	}
}

function randomString($stringType, $stringLenth)
{
	if ($stringType == "numeric") {
		$characters = '0123456789';
	} else if ($stringType == "alpha-numeric") {
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
	return "Asia/Kolkata";
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
		if ($type == "date") {
			return $dt->format('d/m/Y');
		} else if ($type == "time") {
			return $dt->format('h:i A');
		}
	} catch (Exception $e) {

		return $GMTDateTime;
	}
}

function convertOrderDateTime($GMTDateTime, $showType)
{

	$TIMEZONE = websiteTimeZone();
	try {

		$dt = new DateTime('@' . strtotime($GMTDateTime));
		$dt->setTimeZone(new DateTimeZone($TIMEZONE));

		if ($showType == "date") {
			return $dt->format('d M y');
		} else if ($showType == "time") {
			return $dt->format('h:i:s A');
		}
	} catch (Exception $e) {

		return $GMTDateTime;
	}
}

function saveLeadTimeline($params)
{

	$LeadTimeline = new LeadTimeline();
	$LeadTimeline->user_id = Auth::user()->id;
	$LeadTimeline->type = $params['type'];
	$LeadTimeline->lead_id = $params['lead_id'];
	$LeadTimeline->reffrance_id = $params['reffrance_id'];
	$LeadTimeline->description = $params['description'];
	$LeadTimeline->save();
}

function saveDebugLog($params)
{

	$DebugLog = new DebugLog();
	$DebugLog->user_id = Auth::user()->id;
	$DebugLog->name = $params['name'];
	$DebugLog->description = $params['description'];
	$DebugLog->save();
}

function saveProductLog($params)
{

	$DebugLog = new ProductLog();
	$DebugLog->product_inventory_id = $params['product_inventory_id'];
	$DebugLog->request_quantity = $params['request_quantity'];
	$DebugLog->quantity = $params['quantity'];
	//$DebugLog->user_id = 275;
	$DebugLog->user_id = Auth::user()->id;
	$DebugLog->name = $params['name'];
	$DebugLog->description = $params['description'];
	$DebugLog->save();
}

function saveMarketingProductLog($params)
{

	$DebugLog = new MarketingProductLog();
	$DebugLog->marketing_product_inventory_id = $params['marketing_product_inventory_id'];
	$DebugLog->request_quantity = $params['request_quantity'];
	$DebugLog->quantity = $params['quantity'];
	//$DebugLog->user_id = 275;
	$DebugLog->user_id = Auth::user()->id;
	$DebugLog->name = $params['name'];
	$DebugLog->description = $params['description'];
	$DebugLog->save();
}

function saveInquiryLog($params)
{

	$DebugLog = new InquiryLog();
	$DebugLog->inquiry_id = $params['inquiry_id'];
	$DebugLog->user_id = Auth::user()->id;
	$DebugLog->name = $params['name'];
	$DebugLog->description = $params['description'];
	$DebugLog->save();
}
function saveCRMUserLog($params)
{

	if (isset(Auth::user()->id) && Auth::user()->id != "") {

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

	$CityListName = "";

	$CityList = CityList::select('name')->find($cityId);
	if ($CityList) {
		$CityListName = $CityList->name;
	}

	return $CityListName;
}

function getStateName($stateId)
{

	$StateListName = "";

	$StateList = StateList::select('name')->find($stateId);
	if ($StateList) {
		$StateListName = $StateList->name;
	}

	return $StateListName;
}

function getCountryName($stateId)
{

	$CountryListName = "";

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

function productFeatureList()
{

	$featureList = array();
	$featureList[1]['id'] = 1;
	$featureList[1]['code'] = "ON-OFF";
	$featureList[1]['display_name'] = "On/Off";

	$featureList[2]['id'] = 2;
	$featureList[2]['code'] = "FAN";
	$featureList[2]['display_name'] = "Fan";

	$featureList[3]['id'] = 3;
	$featureList[3]['code'] = "MASTER";
	$featureList[3]['display_name'] = "Master";

	$featureList[4]['id'] = 4;
	$featureList[4]['code'] = "CURTAIN";
	$featureList[4]['display_name'] = "Curtain";

	$featureList[5]['id'] = 5;
	$featureList[5]['code'] = "DIMMER";
	$featureList[5]['display_name'] = "Dimmer";

	$featureList[6]['id'] = 5;
	$featureList[6]['code'] = "BELL";
	$featureList[6]['display_name'] = "Bell";

	$featureList[7]['id'] = 5;
	$featureList[7]['code'] = "HL";
	$featureList[7]['display_name'] = "HL";

	return $featureList;
}

function getCRMStageOfSiteStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMSiteTypeStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}
function getCRMBHKStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}
function getCRMWantToCoverStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMSouceTypeStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMCompetitorsStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMSourceStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMSSubStatusLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getCRMContactTagLable($setting)
{

	$setting = (int) $setting;

	if ($setting == 0) {
		$setting = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($setting == 1) {
		$setting = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $setting;
}

function getGiftOrderLable($OrderStatus)
{
	// code...
	$OrderStatus = (int) $OrderStatus;

	if ($OrderStatus == 0) {
		$OrderStatus = '<span class="badge badge-pill badge badge-soft-warning font-size-11"> PLACED / ON REVIEW</span>';
	} else if ($OrderStatus == 1) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-success font-size-11">  ACCEPTED</span>';
	} else if ($OrderStatus == 2) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-success font-size-11">  DISPATCHED</span>';
	} else if ($OrderStatus == 3) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-danger font-size-11">  REJECTED</span>';
	} else if ($OrderStatus == 4) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-primary font-size-11">  DELIEVERED</span>';
	} else if ($OrderStatus == 5) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-info font-size-11">  RECEIVED</span>';
	}

	return $OrderStatus;
}

function getOrderLable($OrderStatus)
{
	// code...
	$OrderStatus = (int) $OrderStatus;

	if ($OrderStatus == 0) {
		$OrderStatus = '<span class="badge badge-pill badge badge-soft-warning font-size-11"> PLACED</span>';
	} else if ($OrderStatus == 1) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-info font-size-11"> PROCESSING</span>';
	} else if ($OrderStatus == 2) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-orange font-size-11"> PARTIALLY DISPATCHED</span>';
	} else if ($OrderStatus == 3) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-success font-size-11"> FULLY DISPATCHED</span>';
	} else if ($OrderStatus == 4) {

		$OrderStatus = '<span class="badge badge-pill badge badge-soft-danger font-size-11"> CANCELLED</span>';
	}
	return $OrderStatus;
}

function getOrderStatus($OrderStatus)
{
	// code...
	$OrderStatus = (int) $OrderStatus;

	if ($OrderStatus == 0) {
		$OrderStatus = 'PLACED';
	} else if ($OrderStatus == 1) {

		$OrderStatus = 'PROCESSING';
	} else if ($OrderStatus == 2) {

		$OrderStatus = 'PARTIALLY DISPATCHED';
	} else if ($OrderStatus == 3) {

		$OrderStatus = 'FULLY DISPATCHED';
	} else if ($OrderStatus == 4) {

		$OrderStatus = 'CANCELLED';
	}
	return $OrderStatus;
}

function getMarketingRequestStatus($MarketingRequestStatus)
{
	// code...
	$MarketingRequestStatus = (int) $MarketingRequestStatus;

	if ($MarketingRequestStatus == 0) {
		$MarketingRequestStatus = 'REQUESTED';
	} else if ($MarketingRequestStatus == 1) {

		$MarketingRequestStatus = 'APPROVED';
	} else if ($MarketingRequestStatus == 2) {

		$MarketingRequestStatus = 'PARTIALLY APPROVED';
	} else if ($MarketingRequestStatus == 3) {

		$MarketingRequestStatus = 'REJECTED';
	}
	return $MarketingRequestStatus;
}

function getMarketingRequestDelieveryChallanStatus($MarketingDelieveryChallanStatus)
{
	// code...
	$MarketingDelieveryChallanStatus = (int) $MarketingDelieveryChallanStatus;

	if ($MarketingDelieveryChallanStatus == 0) {
		$MarketingDelieveryChallanStatus = 'CHALLAN RAISED';
	} else if ($MarketingDelieveryChallanStatus == 1) {

		$MarketingDelieveryChallanStatus = 'PACKED';
	} else if ($MarketingDelieveryChallanStatus == 2) {

		$MarketingDelieveryChallanStatus = 'DISPATCHED';
	}
	return $MarketingDelieveryChallanStatus;
}

function getInvoiceLable($invoiceStatus)
{

	if ($invoiceStatus == 0) {
		$invoiceStatus = '<span class="badge badge-pill badge bg-primary font-size-11">INVOICE RAISED</span>';
	} else if ($invoiceStatus == 1) {

		$invoiceStatus = '<span class="badge badge-pill badge-soft-info font-size-11"> PACKED</span>';
	} else if ($invoiceStatus == 2) {

		$invoiceStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> DISPATCHED</span>';
	} else if ($invoiceStatus == 3) {

		$invoiceStatus = '<span class="badge badge-pill badge-soft-dark font-size-11"> RECIEVED</span>';
	} else if ($invoiceStatus == 4) {

		$invoiceStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> CANCELLED</span>';
	}
	return $invoiceStatus;
	// code...
}

function getInvoiceStatus($invoiceStatus)
{

	if ($invoiceStatus == 0) {
		$invoiceStatus = 'INVOICE RAISED';
	} else if ($invoiceStatus == 1) {

		$invoiceStatus = 'PACKED';
	} else if ($invoiceStatus == 2) {

		$invoiceStatus = 'DISPATCHED';
	} else if ($invoiceStatus == 3) {

		$invoiceStatus = 'RECIEVED';
	} else if ($invoiceStatus == 4) {

		$invoiceStatus = 'CANCELLED';
	}
	return $invoiceStatus;
	// code...
}

function getPaymentModeName($paymentMode)
{

	if ($paymentMode == 0) {
		$paymentMode = "PDC";
	} else if ($paymentMode == 1) {
		$paymentMode = "ADVANCE";
	} else if ($paymentMode == 2) {
		$paymentMode = "CREDIT";
	}
	return $paymentMode;
}

function getPaymentLable($paymentMode)
{

	if ($paymentMode == 0) {
		$paymentMode = '<span class="badge badge-pill badge-soft-danger font-size-11">PDC</span>';
	} else if ($paymentMode == 1) {

		$paymentMode = '<span class="badge badge-pill badge-soft-danger font-size-11"> ADVANCE</span>';
	} else if ($paymentMode == 2) {

		$paymentMode = '<span class="badge badge-pill badge-soft-success font-size-11"> CREDIT</span>';
	}
	return $paymentMode;
}

function getProductGroupLable($ProductGroupStatus)
{
	// code...
	$ProductGroupStatus = (int) $ProductGroupStatus;

	if ($ProductGroupStatus == 0) {
		$ProductGroupStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($ProductGroupStatus == 1) {
		$ProductGroupStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $ProductGroupStatus;
}

function getDataMasterStatusLable($dataMasterStatus)
{
	// code...
	$dataMasterStatus = (int) $dataMasterStatus;

	if ($dataMasterStatus == 0) {
		$dataMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($dataMasterStatus == 1) {
		$dataMasterStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($dataMasterStatus == 2) {
		$dataMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $dataMasterStatus;
}

function getMainMasterStatusLable($mainMasterStatus)
{
	$mainMasterStatus = (int) $mainMasterStatus;

	if ($mainMasterStatus == 0) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($mainMasterStatus == 1) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($mainMasterStatus == 2) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $mainMasterStatus;
}

function getExhibitionStatusLable($exhibitionStatus)
{
	$exhibitionStatus = (int) $exhibitionStatus;

	if ($exhibitionStatus == 0) {
		$exhibitionStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Completed</span>';
	} else if ($exhibitionStatus == 1) {
		$exhibitionStatus = '<span class="badge badge-pill badge-soft-success font-size-11">Live/Upcoming</span>';
	}
	return $exhibitionStatus;
}

function getSalesHierarchyStatusLable($salesHierarchyStatus)
{

	$salesHierarchyStatus = (int) $salesHierarchyStatus;

	if ($salesHierarchyStatus == 0) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($salesHierarchyStatus == 1) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($salesHierarchyStatus == 2) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $salesHierarchyStatus;
}

function getPurchaseHierarchyStatusLable($salesHierarchyStatus)
{

	$salesHierarchyStatus = (int) $salesHierarchyStatus;

	if ($salesHierarchyStatus == 0) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($salesHierarchyStatus == 1) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($salesHierarchyStatus == 2) {
		$salesHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $salesHierarchyStatus;
}

function getCityStatusLable($cityStatus)
{

	$cityStatus = (int) $cityStatus;

	if ($cityStatus == 0) {
		$cityStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($cityStatus == 1) {
		$cityStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($cityStatus == 2) {
		$cityStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $cityStatus;
}

function getUserStatusLable($userStatus)
{

	$userStatus = (int) $userStatus;
	if ($userStatus == 0) {
		$userStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($userStatus == 1) {
		$userStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($userStatus == 2) {
		$userStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Pending</span>';
	}
	return $userStatus;
}

function getUserStatus($userStatus)
{

	$userStatus = (int) $userStatus;
	if ($userStatus == 0) {
		$userStatus = 'Inactive';
	} else if ($userStatus == 1) {
		$userStatus = 'Active';
	} else if ($userStatus == 2) {
		$userStatus = 'Pending';
	}
	return $userStatus;
}

function getCompanyStatusLable($companyStatus)
{

	$companyStatus = (int) $companyStatus;
	if ($companyStatus == 0) {
		$companyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($companyStatus == 1) {
		$companyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($companyStatus == 2) {
		$companyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $companyStatus;
}

function getGiftCategoryStatusLable($GiftCategoryStatus)
{

	$GiftCategoryStatus = (int) $GiftCategoryStatus;
	if ($GiftCategoryStatus == 0) {
		$GiftCategoryStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($GiftCategoryStatus == 1) {
		$GiftCategoryStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $GiftCategoryStatus;
}

function getGiftProductStatusLable($GiftCategoryStatus)
{

	$GiftCategoryStatus = (int) $GiftCategoryStatus;
	if ($GiftCategoryStatus == 0) {
		$GiftCategoryStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($GiftCategoryStatus == 1) {
		$GiftCategoryStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $GiftCategoryStatus;
}

function getCRMHelpDocumentStatusLable($HelpDocumentStatus)
{

	$HelpDocumentStatus = (int) $HelpDocumentStatus;
	if ($HelpDocumentStatus == 0) {
		$HelpDocumentStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Inactive</span>';
	} else if ($HelpDocumentStatus == 1) {
		$HelpDocumentStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	}
	return $HelpDocumentStatus;
	// code...
}

/////

function getUserTypes()
{

	$userTypes = array();
	$userTypes[0]['id'] = 0;
	$userTypes[0]['name'] = "Admin";
	$userTypes[0]['short_name'] = "ADMIN";
	$userTypes[0]['lable'] = "user-admin";
	$userTypes[0]['key'] = "t-user-admin";
	$userTypes[0]['url'] = route('users.admin');
	$userTypes[0]['can_login'] = 1;

	$userTypes[1]['id'] = 1;
	$userTypes[1]['name'] = "Company Admin";
	$userTypes[1]['short_name'] = "COMPANY ADMIN";
	$userTypes[1]['lable'] = "user-company-admin";
	$userTypes[1]['key'] = "t-user-company-admin";
	$userTypes[1]['url'] = route('users.company.admin');
	$userTypes[1]['can_login'] = 1;

	$userTypes[2]['id'] = 2;
	$userTypes[2]['name'] = "Sales";
	$userTypes[2]['short_name'] = "SALES";
	$userTypes[2]['lable'] = "user-sales";
	$userTypes[2]['key'] = "t-user-sale-person";
	$userTypes[2]['url'] = route('users.sale.person');
	$userTypes[2]['can_login'] = 1;

	$userTypes[3]['id'] = 3;
	$userTypes[3]['name'] = "Account";
	$userTypes[3]['short_name'] = "ACCOUNT";
	$userTypes[3]['lable'] = "user-account";
	$userTypes[3]['key'] = "t-user-account-user";
	$userTypes[3]['url'] = route('users.account');
	$userTypes[3]['can_login'] = 1;

	$userTypes[4]['id'] = 4;
	$userTypes[4]['name'] = "Dispatcher";
	$userTypes[4]['short_name'] = "DISPATCHER";
	$userTypes[4]['lable'] = "user-dispatcher";
	$userTypes[4]['key'] = "t-user-dispatcher-user";
	$userTypes[4]['url'] = route('users.dispatcher');
	$userTypes[4]['can_login'] = 1;

	$userTypes[5]['id'] = 5;
	$userTypes[5]['name'] = "Production";
	$userTypes[5]['short_name'] = "PRODUCTION";
	$userTypes[5]['lable'] = "user-production";
	$userTypes[5]['key'] = "t-user-production-user";
	$userTypes[5]['url'] = route('users.production');
	$userTypes[5]['can_login'] = 1;

	$userTypes[6]['id'] = 6;
	$userTypes[6]['name'] = "Marketing";
	$userTypes[6]['short_name'] = "MARKETING";
	$userTypes[6]['lable'] = "user-marketing";
	$userTypes[6]['key'] = "t-user-marketing-user";
	$userTypes[6]['url'] = route('users.marketing');
	$userTypes[6]['can_login'] = 1;

	$userTypes[7]['id'] = 7;
	$userTypes[7]['name'] = "Marketing - Dispatcher ";
	$userTypes[7]['short_name'] = "MARKETING - DISPATCHER";
	$userTypes[7]['lable'] = "user-marketing-dispatcher";
	$userTypes[7]['key'] = "t-user-marketing-user-dispatcher";
	$userTypes[7]['url'] = route('users.marketing.dispatcher');
	$userTypes[7]['can_login'] = 1;

	$userTypes[8]['id'] = 8;
	$userTypes[8]['name'] = "Third Party";
	$userTypes[8]['short_name'] = "THIRD PARTY";
	$userTypes[8]['lable'] = "user-third-party";
	$userTypes[8]['key'] = "t-user-third-party";
	$userTypes[8]['url'] = route('users.thirdparty');
	$userTypes[8]['can_login'] = 1;

	$userTypes[9]['id'] = 9;
	$userTypes[9]['name'] = "Tele Sales";
	$userTypes[9]['short_name'] = "TELE SALE";
	$userTypes[9]['lable'] = "user-tele-sale";
	$userTypes[9]['key'] = "t-user-tele-tele";
	$userTypes[9]['url'] = route('users.tele.sale');
	$userTypes[9]['can_login'] = 1;

	$userTypes[10]['id'] = 10;
	$userTypes[10]['name'] = "Purchase";
	$userTypes[10]['short_name'] = "PURCHASE";
	$userTypes[10]['lable'] = "user-purchase";
	$userTypes[10]['key'] = "t-user-purchase-person";
	$userTypes[10]['url'] = route('users.purchase.person');
	$userTypes[10]['can_login'] = 1;

	$userTypes[11]['id'] = 11;
	$userTypes[11]['name'] = "Service User";
	$userTypes[11]['short_name'] = "SERVICE USER";
	$userTypes[11]['lable'] = "user-service-executive";
	$userTypes[11]['key'] = "t-user-service-executive";
	$userTypes[11]['url'] = route('users.service.executive');
	$userTypes[11]['can_login'] = 1;

	return $userTypes;
}

function getChannelPartners()
{
	$userTypes = array();
	$userTypes[101]['id'] = 101;
	$userTypes[101]['name'] = "ASM(Autorise Stockist Merchantize)";
	$userTypes[101]['lable'] = "channel-partner-asm";
	$userTypes[101]['short_name'] = "ASM";
	$userTypes[101]['key'] = "t-channel-partner-stockist";
	$userTypes[101]['url'] = route('channel.partners.stockist');
	$userTypes[101]['url_view'] = route('channel.partners.stockist.view');
	$userTypes[101]['url_sub_orders'] = route('orders.sub.asm');
	$userTypes[101]['can_login'] = 1;
	$userTypes[101]['inquiry_tab'] = 1;

	$userTypes[102]['id'] = 102;
	$userTypes[102]['name'] = "ADM(Authorize Distributor Merchantize)";
	$userTypes[102]['lable'] = "channel-partner-adm";
	$userTypes[102]['short_name'] = "ADM";
	$userTypes[102]['key'] = "t-channel-partner-adm";
	$userTypes[102]['url'] = route('channel.partners.adm');
	$userTypes[102]['url_view'] = route('channel.partners.adm.view');
	$userTypes[102]['url_sub_orders'] = route('orders.sub.adm');
	$userTypes[102]['can_login'] = 1;
	$userTypes[102]['inquiry_tab'] = 1;

	$userTypes[103]['id'] = 103;
	$userTypes[103]['name'] = "APM(Authorize Project Merchantize)";
	$userTypes[103]['lable'] = "channel-partner-apm";
	$userTypes[103]['short_name'] = "APM";
	$userTypes[103]['key'] = "t-channel-partner-apm";
	$userTypes[103]['url'] = route('channel.partners.apm');
	$userTypes[103]['url_view'] = route('channel.partners.apm.view');
	$userTypes[103]['url_sub_orders'] = route('orders.sub.apm');
	$userTypes[103]['can_login'] = 1;
	$userTypes[103]['inquiry_tab'] = 1;

	$userTypes[104]['id'] = 104;
	$userTypes[104]['name'] = "AD(Authorised Dealer)";
	$userTypes[104]['lable'] = "channel-partner-ad";
	$userTypes[104]['short_name'] = "AD";
	$userTypes[104]['key'] = "t-channel-partner-ad";
	$userTypes[104]['url'] = route('channel.partners.ad');
	$userTypes[104]['url_view'] = route('channel.partners.ad.view');
	$userTypes[104]['url_sub_orders'] = route('orders.sub.ad');
	$userTypes[104]['can_login'] = 1;
	$userTypes[104]['inquiry_tab'] = 1;

	$userTypes[105]['id'] = 105;
	$userTypes[105]['name'] = "Retailer";
	$userTypes[105]['lable'] = "channel-partner-retailer";
	$userTypes[105]['short_name'] = "Retailer";
	$userTypes[105]['key'] = "t-channel-partner-retailer";
	$userTypes[105]['url'] = route('channel.partners.retailer');
	$userTypes[105]['url_view'] = route('channel.partners.retailer.view');
	$userTypes[105]['url_sub_orders'] = route('orders.sub.retailer');
	$userTypes[105]['can_login'] = 1;
	$userTypes[105]['inquiry_tab'] = 1;

	return $userTypes;
}

function getArchitects()
{

	$userTypes = array();
	$userTypes[201]['id'] = 201;
	$userTypes[201]['name'] = "Architect(Non Prime)";
	$userTypes[201]['lable'] = "architect-non-prime";
	$userTypes[201]['short_name'] = "NON PRIME";
	$userTypes[201]['another_name'] = "ARCHITECH";
	$userTypes[201]['url'] = route('architects.prime');
	//$userTypes[201]['url'] = route('architects.non.prime');
	$userTypes[201]['can_login'] = 0;

	$userTypes[202]['id'] = 202;
	$userTypes[202]['name'] = "Architect(Prime)";
	$userTypes[202]['lable'] = "architect-prime";
	$userTypes[202]['short_name'] = "PRIME";
	$userTypes[202]['another_name'] = "ARCHITECH";
	$userTypes[202]['url'] = route('architects.prime');
	$userTypes[202]['can_login'] = 1;

	return $userTypes;
}

function getElectricians()
{

	$userTypes = array();
	$userTypes[301]['id'] = 301;
	$userTypes[301]['name'] = "Electrician(Non Prime)";
	$userTypes[301]['lable'] = "electrician-non-prime";
	$userTypes[301]['short_name'] = "NON PRIME";
	$userTypes[301]['another_name'] = "ELECTRICIAN";
	//$userTypes[301]['url'] = route('electricians.non.prime');
	$userTypes[301]['url'] = route('electricians.prime');
	$userTypes[301]['can_login'] = 0;

	$userTypes[302]['id'] = 302;
	$userTypes[302]['name'] = "Electrician(Prime)";
	$userTypes[302]['lable'] = "electrician-prime";
	$userTypes[302]['short_name'] = "PRIME";
	$userTypes[302]['another_name'] = "ELECTRICIAN";
	$userTypes[302]['url'] = route('electricians.prime');
	$userTypes[302]['can_login'] = 1;

	return $userTypes;
}

function CRMUserType()
{

	$userTypes = array();

	$userTypes[202]['id'] = 202;
	$userTypes[202]['name'] = "Architect(Prime)";
	$userTypes[202]['lable'] = "architect-prime";
	$userTypes[202]['short_name'] = "PRIME";
	$userTypes[202]['another_name'] = "ARCHITECH";

	$userTypes[302]['can_login'] = 1;
	$userTypes[302]['id'] = 302;
	$userTypes[302]['name'] = "Electrician(Prime)";
	$userTypes[302]['lable'] = "electrician-prime";
	$userTypes[302]['short_name'] = "PRIME";
	$userTypes[302]['another_name'] = "ELECTRICIAN";

	return $userTypes;
}

function getCustomers()
{

	$userTypes = array();
	$userTypes[10000]['id'] = 10000;
	$userTypes[10000]['name'] = "User";
	$userTypes[10000]['lable'] = "user";
	$userTypes[10000]['short_name'] = "USER";
	$userTypes[10000]['another_name'] = "USER";
	$userTypes[10000]['can_login'] = 0;
	return $userTypes;
}

function getInquiryStatus()
{

	//Inquiry status type
	$inquiryStatus = array();
	$inquiryStatus[202]['id'] = 202;
	$inquiryStatus[202]['name'] = "Focus";
	$inquiryStatus[202]['key'] = "t-inquiry-focus";
	$inquiryStatus[202]['for_architect_ids'] = array(0);
	$inquiryStatus[202]['for_electrician_ids'] = array(0);
	$inquiryStatus[202]['for_user_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[202]['for_third_party_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[202]['for_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[202]['for_tele_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[202]['for_channel_partner_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[202]['can_move_user'] = array();
	$inquiryStatus[202]['can_move_third_party'] = array();
	$inquiryStatus[202]['can_move_sales'] = array();
	$inquiryStatus[202]['can_move_tele_sales'] = array();
	$inquiryStatus[202]['can_move_channel_partner'] = array();
	$inquiryStatus[202]['only_id_question'] = 0;
	$inquiryStatus[202]['need_followup'] = 0;
	$inquiryStatus[202]['has_question'] = 0;
	$inquiryStatus[202]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[202]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[202]['can_display_on_inquiry_tele_sales'] = 1;
	$inquiryStatus[202]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[202]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[202]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[202]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[202]['background'] = "#0d0d0d";
	$inquiryStatus[202]['color'] = "#ffffff";
	$inquiryStatus[202]['index'] = -1;

	$inquiryStatus[201]['id'] = 201;
	$inquiryStatus[201]['name'] = "Running";
	$inquiryStatus[201]['key'] = "t-inquiry-running";
	$inquiryStatus[201]['for_architect_ids'] = array(0);
	$inquiryStatus[201]['for_electrician_ids'] = array(0);
	$inquiryStatus[201]['for_user_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[201]['for_third_party_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[201]['for_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[201]['for_tele_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[201]['for_channel_partner_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[201]['can_move_user'] = array();
	$inquiryStatus[201]['can_move_third_party'] = array();
	$inquiryStatus[201]['can_move_sales'] = array();
	$inquiryStatus[201]['can_move_tele_sales'] = array();
	$inquiryStatus[201]['can_move_channel_partner'] = array();
	$inquiryStatus[201]['only_id_question'] = 0;
	$inquiryStatus[201]['need_followup'] = 0;
	$inquiryStatus[201]['has_question'] = 0;
	$inquiryStatus[201]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[201]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[201]['can_display_on_inquiry_tele_sales'] = 1;
	$inquiryStatus[201]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[201]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[201]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[201]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[201]['background'] = "#0d0d0d";
	$inquiryStatus[201]['color'] = "#ffffff";
	$inquiryStatus[201]['index'] = 0;

	$inquiryStatus[1]['id'] = 1;
	$inquiryStatus[1]['name'] = "Inquiry";
	$inquiryStatus[1]['key'] = "t-inquiry";
	$inquiryStatus[1]['for_architect_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[1]['for_electrician_ids'] = array(1, 42, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[1]['for_user_ids'] = array(1);
	$inquiryStatus[1]['for_third_party_ids'] = array(1);
	$inquiryStatus[1]['for_sales_ids'] = array(1);
	$inquiryStatus[1]['for_tele_sales_ids'] = array(1);
	$inquiryStatus[1]['for_channel_partner_ids'] = array(1);
	$inquiryStatus[1]['can_move_user'] = array(1, 2, 3, 4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[1]['can_move_third_party'] = array(1, 2, 3, 4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[1]['can_move_sales'] = array(1, 2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[1]['can_move_tele_sales'] = array(1, 2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[1]['can_move_channel_partner'] = array(1, 2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[1]['only_id_question'] = 0;
	$inquiryStatus[1]['need_followup'] = 1;
	$inquiryStatus[1]['has_question'] = 0;
	$inquiryStatus[1]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[1]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[1]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[1]['can_display_on_inquiry_architect'] = 1;
	$inquiryStatus[1]['can_display_on_inquiry_electrician'] = 1;
	$inquiryStatus[1]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[1]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[1]['background'] = "#0d0d0d";
	$inquiryStatus[1]['color'] = "#ffffff";
	$inquiryStatus[1]['highlight_deadend_followup'] = 1;
	$inquiryStatus[1]['index'] = 1;

	$inquiryStatus[2]['id'] = 2;
	$inquiryStatus[2]['name'] = "Potential Inquiry";
	$inquiryStatus[2]['key'] = "t-potential-inquiry";
	$inquiryStatus[2]['for_architect_ids'] = array(2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[2]['for_electrician_ids'] = array(2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[2]['for_user_ids'] = array(2);
	$inquiryStatus[2]['for_third_party_ids'] = array(2);
	$inquiryStatus[2]['for_sales_ids'] = array(2);
	$inquiryStatus[2]['for_tele_sales_ids'] = array(2);
	$inquiryStatus[2]['for_channel_partner_ids'] = array(2);
	$inquiryStatus[2]['can_move_user'] = array(2, 3, 4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[2]['can_move_third_party'] = array(2, 3, 4, 5, 6, 7, 9, 10, 102);
	$inquiryStatus[2]['can_move_sales'] = array(2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[2]['can_move_tele_sales'] = array(2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[2]['can_move_channel_partner'] = array(2, 3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[2]['only_id_question'] = 0;
	$inquiryStatus[2]['need_followup'] = 1;
	$inquiryStatus[2]['has_question'] = 1;
	$inquiryStatus[2]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[2]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[2]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[2]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[2]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[2]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[2]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[2]['background'] = "#f19e06";
	$inquiryStatus[2]['color'] = "#ffffff";
	$inquiryStatus[2]['highlight_deadend_followup'] = 1;
	$inquiryStatus[2]['index'] = 2;

	$inquiryStatus[3]['id'] = 3;
	$inquiryStatus[3]['name'] = "Demo Done";
	$inquiryStatus[3]['key'] = "t-demo-done";
	$inquiryStatus[3]['for_architect_ids'] = array(2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[3]['for_electrician_ids'] = array(2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[3]['for_user_ids'] = array(3);
	$inquiryStatus[3]['for_third_party_ids'] = array(3);
	$inquiryStatus[3]['for_sales_ids'] = array(3);
	$inquiryStatus[3]['for_tele_sales_ids'] = array(3);
	$inquiryStatus[3]['for_channel_partner_ids'] = array(3);
	$inquiryStatus[3]['can_move_user'] = array(3, 4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[3]['can_move_third_party'] = array(3, 4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[3]['can_move_sales'] = array(3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[3]['can_move_tele_sales'] = array(3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[3]['can_move_channel_partner'] = array(3, 4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[3]['only_id_question'] = 0;
	$inquiryStatus[3]['need_followup'] = 1;
	$inquiryStatus[3]['has_question'] = 1;
	$inquiryStatus[3]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[3]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[3]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[3]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[3]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[3]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[3]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[3]['background'] = "#f5b3be";
	$inquiryStatus[3]['color'] = "#ffffff";
	$inquiryStatus[3]['highlight_deadend_followup'] = 1;
	$inquiryStatus[3]['index'] = 3;

	$inquiryStatus[4]['id'] = 4;
	$inquiryStatus[4]['name'] = "Site Visit";
	$inquiryStatus[4]['key'] = "t-site-visit";
	$inquiryStatus[4]['for_architect_ids'] = array(0);
	$inquiryStatus[4]['for_electrician_ids'] = array(0);
	$inquiryStatus[4]['for_user_ids'] = array(4);
	$inquiryStatus[4]['for_third_party_ids'] = array(4);
	$inquiryStatus[4]['for_sales_ids'] = array(4);
	$inquiryStatus[4]['for_tele_sales_ids'] = array(4);
	$inquiryStatus[4]['for_channel_partner_ids'] = array(4);
	$inquiryStatus[4]['can_move_user'] = array(4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[4]['can_move_third_party'] = array(4, 5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[4]['can_move_sales'] = array(4, 5, 6, 7, 13, 9, 101);
	$inquiryStatus[4]['can_move_tele_sales'] = array(4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[4]['can_move_channel_partner'] = array(4, 5, 6, 7, 13, 9, 102);
	$inquiryStatus[4]['only_id_question'] = 0;
	$inquiryStatus[4]['need_followup'] = 1;
	$inquiryStatus[4]['has_question'] = 1;
	$inquiryStatus[4]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[4]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[4]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[4]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[4]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[4]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[4]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[4]['background'] = "#b12d2d";
	$inquiryStatus[4]['color'] = "#ffffff";
	$inquiryStatus[4]['highlight_deadend_followup'] = 1;
	$inquiryStatus[4]['index'] = 4;

	$inquiryStatus[5]['id'] = 5;
	$inquiryStatus[5]['name'] = "Quotation";
	$inquiryStatus[5]['key'] = "t-quotation";
	$inquiryStatus[5]['for_architect_ids'] = array(0);
	$inquiryStatus[5]['for_electrician_ids'] = array(0);
	$inquiryStatus[5]['for_user_ids'] = array(5);
	$inquiryStatus[5]['for_third_party_ids'] = array(5);
	$inquiryStatus[5]['for_sales_ids'] = array(5);
	$inquiryStatus[5]['for_tele_sales_ids'] = array(5);
	$inquiryStatus[5]['for_channel_partner_ids'] = array(5);
	$inquiryStatus[5]['can_move_user'] = array(5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[5]['can_move_third_party'] = array(5, 6, 7, 13, 9, 10, 102);
	$inquiryStatus[5]['can_move_sales'] = array(5, 6, 7, 13, 9, 102);
	$inquiryStatus[5]['can_move_tele_sales'] = array(5, 6, 7, 13, 9, 102);
	$inquiryStatus[5]['can_move_channel_partner'] = array(5, 6, 7, 13, 9, 102);
	$inquiryStatus[5]['only_id_question'] = 0;
	$inquiryStatus[5]['need_followup'] = 1;
	$inquiryStatus[5]['has_question'] = 1;
	$inquiryStatus[5]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[5]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[5]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[5]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[5]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[5]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[5]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[5]['background'] = "#750375";
	$inquiryStatus[5]['color'] = "#ffffff";
	$inquiryStatus[5]['highlight_deadend_followup'] = 1;
	$inquiryStatus[5]['index'] = 5;

	$inquiryStatus[6]['id'] = 6;
	$inquiryStatus[6]['name'] = "Negotiation";
	$inquiryStatus[6]['key'] = "t-negotiation";
	$inquiryStatus[6]['for_architect_ids'] = array(0);
	$inquiryStatus[6]['for_electrician_ids'] = array(0);
	$inquiryStatus[6]['for_user_ids'] = array(6);
	$inquiryStatus[6]['for_third_party_ids'] = array(6);
	$inquiryStatus[6]['for_sales_ids'] = array(6);
	$inquiryStatus[6]['for_tele_sales_ids'] = array(6);
	$inquiryStatus[6]['for_channel_partner_ids'] = array(6);
	$inquiryStatus[6]['can_move_user'] = array(6, 7, 13, 9, 10, 102);
	$inquiryStatus[6]['can_move_third_party'] = array(6, 7, 13, 9, 10, 102);
	$inquiryStatus[6]['can_move_sales'] = array(6, 7, 13, 9, 102);
	$inquiryStatus[6]['can_move_tele_sales'] = array(6, 7, 13, 9, 102);
	$inquiryStatus[6]['can_move_channel_partner'] = array(6, 7, 13, 9, 102);
	$inquiryStatus[6]['only_id_question'] = 0;
	$inquiryStatus[6]['need_followup'] = 1;
	$inquiryStatus[6]['has_question'] = 1;
	$inquiryStatus[6]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[6]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[6]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[6]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[6]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[6]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[6]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[6]['background'] = "#e70e0e";
	$inquiryStatus[6]['color'] = "#ffffff";
	$inquiryStatus[6]['highlight_deadend_followup'] = 1;
	$inquiryStatus[6]['index'] = 6;

	$inquiryStatus[7]['id'] = 7;
	$inquiryStatus[7]['name'] = "Token Received";
	$inquiryStatus[7]['key'] = "t-token-received";
	$inquiryStatus[7]['for_architect_ids'] = array(0);
	$inquiryStatus[7]['for_electrician_ids'] = array(0);
	$inquiryStatus[7]['for_user_ids'] = array(7);
	$inquiryStatus[7]['for_third_party_ids'] = array(7);
	$inquiryStatus[7]['for_sales_ids'] = array(7);
	$inquiryStatus[7]['for_tele_sales_ids'] = array(7);
	$inquiryStatus[7]['for_channel_partner_ids'] = array(7);
	$inquiryStatus[7]['can_move_user'] = array(7, 13, 9, 10, 102);
	$inquiryStatus[7]['can_move_third_party'] = array(7, 13, 9, 10, 102);
	$inquiryStatus[7]['can_move_sales'] = array(7, 13, 9, 102);
	$inquiryStatus[7]['can_move_tele_sales'] = array(7, 13, 9, 102);
	$inquiryStatus[7]['can_move_channel_partner'] = array(7, 13, 9, 102);
	$inquiryStatus[7]['only_id_question'] = 0;
	$inquiryStatus[7]['need_followup'] = 1;
	$inquiryStatus[7]['has_question'] = 1;
	$inquiryStatus[7]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[7]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[7]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[7]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[7]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[7]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[7]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[7]['background'] = "#418107";
	$inquiryStatus[7]['color'] = "#ffffff";
	$inquiryStatus[7]['highlight_deadend_followup'] = 1;
	$inquiryStatus[7]['index'] = 7;

	$inquiryStatus[8]['id'] = 8;
	$inquiryStatus[8]['name'] = "Prediction";
	$inquiryStatus[8]['key'] = "t-predication";
	$inquiryStatus[8]['for_architect_ids'] = array(0);
	$inquiryStatus[8]['for_electrician_ids'] = array(0);
	$inquiryStatus[8]['for_user_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[8]['for_third_party_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[8]['for_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[8]['for_tele_sales_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[8]['for_channel_partner_ids'] = array(1, 2, 3, 4, 5, 6, 7, 8);
	$inquiryStatus[8]['can_move_user'] = array(8, 9, 10, 102);
	$inquiryStatus[8]['can_move_third_party'] = array(8, 9, 10, 102);
	$inquiryStatus[8]['can_move_sales'] = array(8, 9, 102);
	$inquiryStatus[8]['can_move_tele_sales'] = array(8, 9, 102);
	$inquiryStatus[8]['can_move_channel_partner'] = array(8, 9, 102);
	$inquiryStatus[8]['only_id_question'] = 0;
	$inquiryStatus[8]['need_followup'] = 1;
	$inquiryStatus[8]['has_question'] = 1;
	$inquiryStatus[8]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[8]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[8]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[8]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[8]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[8]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[8]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[8]['background'] = "#e70e0e";
	$inquiryStatus[8]['color'] = "#ffffff";
	$inquiryStatus[8]['highlight_deadend_followup'] = 1;
	$inquiryStatus[8]['index'] = 8;

	$inquiryStatus[13]['id'] = 13;
	$inquiryStatus[13]['name'] = "Material Ordered";
	$inquiryStatus[13]['key'] = "t-lapsed";
	$inquiryStatus[13]['for_architect_ids'] = array(13);
	$inquiryStatus[13]['for_electrician_ids'] = array(13);
	$inquiryStatus[13]['for_user_ids'] = array(13);
	$inquiryStatus[13]['for_third_party_ids'] = array(13);
	$inquiryStatus[13]['for_sales_ids'] = array(13);
	$inquiryStatus[13]['for_tele_sales_ids'] = array(13);
	$inquiryStatus[13]['for_channel_partner_ids'] = array(12);
	$inquiryStatus[13]['can_move_user'] = array(13, 9, 10, 102);
	$inquiryStatus[13]['can_move_third_party'] = array(13, 9, 10, 102);
	$inquiryStatus[13]['can_move_sales'] = array(13, 9, 10, 102);
	$inquiryStatus[13]['can_move_tele_sales'] = array(13, 9, 10, 102);
	$inquiryStatus[13]['can_move_channel_partner'] = array(13, 9, 10, 102);
	$inquiryStatus[13]['only_id_question'] = 0;
	$inquiryStatus[13]['need_followup'] = 1;
	$inquiryStatus[13]['has_question'] = 1;
	$inquiryStatus[13]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[13]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[13]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[13]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[13]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[13]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[13]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[13]['background'] = "#e70e0e";
	$inquiryStatus[13]['color'] = "#ffffff";
	$inquiryStatus[13]['highlight_deadend_followup'] = 0;
	$inquiryStatus[13]['index'] = 9;

	$inquiryStatus[9]['id'] = 9;
	$inquiryStatus[9]['name'] = "Material Sent";
	$inquiryStatus[9]['key'] = "t-material-sent";
	$inquiryStatus[9]['for_architect_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_electrician_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_user_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_third_party_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_sales_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_tele_sales_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['for_channel_partner_ids'] = array(9, 11, 10);
	$inquiryStatus[9]['can_move_user'] = array(9, 10, 12, 14, 102);
	$inquiryStatus[9]['can_move_third_party'] = array(9, 10, 12, 14, 102);
	$inquiryStatus[9]['can_move_sales'] = array(9, 12, 102);
	$inquiryStatus[9]['can_move_tele_sales'] = array(9, 12, 102);
	$inquiryStatus[9]['can_move_channel_partner'] = array(9, 102);
	$inquiryStatus[9]['only_id_question'] = 0;
	$inquiryStatus[9]['need_followup'] = 0;
	$inquiryStatus[9]['has_question'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[9]['can_display_on_inquiry_architect'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_electrician'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[9]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[9]['background'] = "#e70e0e";
	$inquiryStatus[9]['color'] = "#ffffff";
	$inquiryStatus[9]['highlight_deadend_followup'] = 0;
	$inquiryStatus[9]['index'] = 10;

	$inquiryStatus[11]['id'] = 11;
	$inquiryStatus[11]['name'] = "Direct Material Sent";
	$inquiryStatus[11]['key'] = "t-direct-material-sent";
	$inquiryStatus[11]['for_architect_ids'] = array(9, 10);
	$inquiryStatus[11]['for_electrician_ids'] = array(9, 10);
	$inquiryStatus[11]['for_user_ids'] = array(10);
	$inquiryStatus[11]['for_third_party_ids'] = array(10);
	$inquiryStatus[11]['for_sales_ids'] = array(10);
	$inquiryStatus[11]['for_tele_sales_ids'] = array(10);
	$inquiryStatus[11]['for_channel_partner_ids'] = array(10);
	$inquiryStatus[11]['can_move_user'] = array(11, 10, 102);
	$inquiryStatus[11]['can_move_third_party'] = array(11, 10, 102);
	$inquiryStatus[11]['can_move_sales'] = array(11, 102);
	$inquiryStatus[11]['can_move_tele_sales'] = array(11, 102);
	$inquiryStatus[11]['can_move_channel_partner'] = array(11, 102);
	$inquiryStatus[11]['only_id_question'] = 1;
	$inquiryStatus[11]['need_followup'] = 0;
	$inquiryStatus[11]['has_question'] = 1;
	$inquiryStatus[11]['can_display_on_inquiry_user'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_third_party'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_sales_person'] = 0;
	$inquiryStatus[11]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[11]['background'] = "#e70e0e";
	$inquiryStatus[11]['color'] = "#ffffff";
	$inquiryStatus[11]['highlight_deadend_followup'] = 0;
	$inquiryStatus[11]['index'] = 11;

	$inquiryStatus[14]['id'] = 14;
	$inquiryStatus[14]['name'] = "Points Query";
	$inquiryStatus[14]['key'] = "t-point-query";
	$inquiryStatus[14]['for_architect_ids'] = array(14);
	$inquiryStatus[14]['for_electrician_ids'] = array(14);
	$inquiryStatus[14]['for_user_ids'] = array(14);
	$inquiryStatus[14]['for_third_party_ids'] = array(14);
	$inquiryStatus[14]['for_sales_ids'] = array(14);
	$inquiryStatus[14]['for_tele_sales_ids'] = array(14);
	$inquiryStatus[14]['for_channel_partner_ids'] = array(14);
	$inquiryStatus[14]['can_move_user'] = array(14, 12, 10);
	$inquiryStatus[14]['can_move_third_party'] = array();
	$inquiryStatus[14]['can_move_sales'] = array();
	$inquiryStatus[14]['can_move_tele_sales'] = array();
	$inquiryStatus[14]['can_move_channel_partner'] = array();
	$inquiryStatus[14]['only_id_question'] = 1;
	$inquiryStatus[14]['need_followup'] = 0;
	$inquiryStatus[14]['has_question'] = 1;
	$inquiryStatus[14]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[14]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[14]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[14]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[14]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[14]['can_display_on_inquiry_sales_person'] = 0;
	$inquiryStatus[14]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[14]['background'] = "#e70e0e";
	$inquiryStatus[14]['color'] = "#ffffff";
	$inquiryStatus[14]['highlight_deadend_followup'] = 0;
	$inquiryStatus[14]['index'] = 11;

	$inquiryStatus[12]['id'] = 12;
	$inquiryStatus[12]['name'] = "Points Lapsed";
	$inquiryStatus[12]['key'] = "t-lapsed";
	$inquiryStatus[12]['for_architect_ids'] = array(12);
	$inquiryStatus[12]['for_electrician_ids'] = array(12);
	$inquiryStatus[12]['for_user_ids'] = array(12);
	$inquiryStatus[12]['for_third_party_ids'] = array(12);
	$inquiryStatus[12]['for_sales_ids'] = array(12);
	$inquiryStatus[12]['for_tele_sales_ids'] = array(12);
	$inquiryStatus[12]['for_channel_partner_ids'] = array(12);
	$inquiryStatus[12]['can_move_user'] = array(12, 10);
	$inquiryStatus[12]['can_move_third_party'] = array();
	$inquiryStatus[12]['can_move_sales'] = array();
	$inquiryStatus[12]['can_move_tele_sales'] = array();
	$inquiryStatus[12]['can_move_channel_partner'] = array();
	$inquiryStatus[12]['only_id_question'] = 1;
	$inquiryStatus[12]['need_followup'] = 0;
	$inquiryStatus[12]['has_question'] = 1;
	$inquiryStatus[12]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[12]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[12]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[12]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[12]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[12]['can_display_on_inquiry_sales_person'] = 0;
	$inquiryStatus[12]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[12]['background'] = "#e70e0e";
	$inquiryStatus[12]['color'] = "#ffffff";
	$inquiryStatus[12]['highlight_deadend_followup'] = 0;
	$inquiryStatus[12]['index'] = 12;

	$inquiryStatus[10]['id'] = 10;
	$inquiryStatus[10]['name'] = "Claimed";
	$inquiryStatus[10]['key'] = "t-claimed";
	$inquiryStatus[10]['for_architect_ids'] = array(9, 10);
	$inquiryStatus[10]['for_electrician_ids'] = array(9, 10);
	$inquiryStatus[10]['for_user_ids'] = array(9, 10);
	$inquiryStatus[10]['for_third_party_ids'] = array(10);
	$inquiryStatus[10]['for_sales_ids'] = array(10);
	$inquiryStatus[10]['for_tele_sales_ids'] = array(10);
	$inquiryStatus[10]['for_channel_partner_ids'] = array(10);
	$inquiryStatus[10]['can_move_user'] = array(10);
	$inquiryStatus[10]['can_move_third_party'] = array(10);
	$inquiryStatus[10]['can_move_sales'] = array(10);
	$inquiryStatus[10]['can_move_tele_sales'] = array(10);
	$inquiryStatus[10]['can_move_channel_partner'] = array(10);
	$inquiryStatus[10]['only_id_question'] = 0;
	$inquiryStatus[10]['need_followup'] = 0;
	$inquiryStatus[10]['has_question'] = 1;
	$inquiryStatus[10]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[10]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[10]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[10]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[10]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[10]['can_display_on_inquiry_sales_person'] = 0;
	$inquiryStatus[10]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[10]['background'] = "#e70e0e";
	$inquiryStatus[10]['color'] = "#ffffff";
	$inquiryStatus[10]['highlight_deadend_followup'] = 0;
	$inquiryStatus[10]['index'] = 13;

	$inquiryStatus[101]['id'] = 101;
	$inquiryStatus[101]['name'] = "Non Potential";
	$inquiryStatus[101]['key'] = "t-non-potential";
	$inquiryStatus[101]['for_architect_ids'] = array();
	$inquiryStatus[101]['for_electrician_ids'] = array();
	$inquiryStatus[101]['for_user_ids'] = array();
	$inquiryStatus[101]['for_third_party_ids'] = array();
	$inquiryStatus[101]['for_sales_ids'] = array();
	$inquiryStatus[101]['for_tele_sales_ids'] = array();
	$inquiryStatus[101]['for_channel_partner_ids'] = array();
	$inquiryStatus[101]['can_move_user'] = array();
	$inquiryStatus[101]['can_move_third_party'] = array();
	$inquiryStatus[101]['can_move_sales'] = array();
	$inquiryStatus[101]['can_move_tele_sales'] = array();
	$inquiryStatus[101]['can_move_channel_partner'] = array();
	$inquiryStatus[101]['only_id_question'] = 1;
	$inquiryStatus[101]['need_followup'] = 0;
	$inquiryStatus[101]['has_question'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_user'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_third_party'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_sales_person'] = 0;
	$inquiryStatus[101]['can_display_on_inquiry_channel_partner'] = 0;
	$inquiryStatus[101]['background'] = "#88cbe6";
	$inquiryStatus[101]['color'] = "#ffffff";
	$inquiryStatus[101]['highlight_deadend_followup'] = 0;
	$inquiryStatus[101]['index'] = 4;

	$inquiryStatus[102]['id'] = 102;
	$inquiryStatus[102]['name'] = "Rejected";
	$inquiryStatus[102]['key'] = "t-rejected";
	$inquiryStatus[102]['for_architect_ids'] = array(102);
	$inquiryStatus[102]['for_electrician_ids'] = array(102);
	$inquiryStatus[102]['for_user_ids'] = array(102);
	$inquiryStatus[102]['for_third_party_ids'] = array(102);
	$inquiryStatus[102]['for_sales_ids'] = array(102);
	$inquiryStatus[102]['for_tele_sales_ids'] = array(102);
	$inquiryStatus[102]['for_channel_partner_ids'] = array(102);
	$inquiryStatus[102]['can_move_user'] = array(102);
	$inquiryStatus[102]['can_move_third_party'] = array(102);
	$inquiryStatus[102]['can_move_sales'] = array(102);
	$inquiryStatus[102]['can_move_tele_sales'] = array(102);
	$inquiryStatus[102]['can_move_channel_partner'] = array(102);
	$inquiryStatus[102]['only_id_question'] = 1;
	$inquiryStatus[102]['need_followup'] = 0;
	$inquiryStatus[102]['has_question'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[102]['can_display_on_inquiry_architect'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_electrician'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[102]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[102]['background'] = "#88cbe6";
	$inquiryStatus[102]['color'] = "#ffffff";
	$inquiryStatus[102]['highlight_deadend_followup'] = 0;
	$inquiryStatus[102]['index'] = 15;

	$inquiryStatus[0]['id'] = 0;
	$inquiryStatus[0]['name'] = "All";
	$inquiryStatus[0]['key'] = "t-all";
	$inquiryStatus[0]['for_architect_ids'] = array(0);
	$inquiryStatus[0]['for_electrician_ids'] = array(0);
	$inquiryStatus[0]['for_user_ids'] = array(0);
	$inquiryStatus[0]['for_third_party_ids'] = array(0);
	$inquiryStatus[0]['for_sales_ids'] = array(0);
	$inquiryStatus[0]['for_tele_sales_ids'] = array(0);
	$inquiryStatus[0]['for_channel_partner_ids'] = array(0);
	$inquiryStatus[0]['can_move_user'] = array(0);
	$inquiryStatus[0]['can_move_third_party'] = array();
	$inquiryStatus[0]['can_move_sales'] = array();
	$inquiryStatus[0]['can_move_tele_sales'] = array();
	$inquiryStatus[0]['can_move_channel_partner'] = array();
	$inquiryStatus[0]['only_id_question'] = 0;
	$inquiryStatus[0]['need_followup'] = 0;
	$inquiryStatus[0]['has_question'] = 0;
	$inquiryStatus[0]['can_display_on_inquiry_user'] = 1;
	$inquiryStatus[0]['can_display_on_inquiry_third_party'] = 1;
	$inquiryStatus[0]['can_display_on_inquiry_tele_sales'] = 0;
	$inquiryStatus[0]['can_display_on_inquiry_architect'] = 0;
	$inquiryStatus[0]['can_display_on_inquiry_electrician'] = 0;
	$inquiryStatus[0]['can_display_on_inquiry_sales_person'] = 1;
	$inquiryStatus[0]['can_display_on_inquiry_channel_partner'] = 1;
	$inquiryStatus[0]['background'] = "#0d0d0d";
	$inquiryStatus[0]['color'] = "#ffffff";
	$inquiryStatus[0]['highlight_deadend_followup'] = 1;
	$inquiryStatus[0]['index'] = 16;

	//	$inquiryStatus[0]['sub_ids'] = array(0);
	// $inquiryStatus[0]['is_last_status'] = 0;
	// $inquiryStatus[0]['need_followup'] = 0;

	// $inquiryStatus[1]['id'] = 1;
	// $inquiryStatus[1]['sub_ids'] = array(1);
	// $inquiryStatus[1]['name'] = "Inquiry";
	// $inquiryStatus[1]['key'] = "t-inquiry";
	// $inquiryStatus[1]['can_move_user'] = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 101);
	// $inquiryStatus[1]['is_last_status'] = 0;
	// $inquiryStatus[1]['need_followup'] = 1;

	// $inquiryStatus[2]['id'] = 2;
	// $inquiryStatus[2]['sub_ids'] = array(2, 3, 4, 5, 6, 7, 8);
	// $inquiryStatus[2]['name'] = "Potential Inquiry";
	// $inquiryStatus[2]['key'] = "t-potential-inquiry";
	// $inquiryStatus[2]['can_move_user'] = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 101);
	// $inquiryStatus[2]['is_last_status'] = 0;
	// $inquiryStatus[2]['need_followup'] = 1;

	// $inquiryStatus[3]['id'] = 3;
	// $inquiryStatus[3]['sub_ids'] = array(0);
	// $inquiryStatus[3]['name'] = "Demo Done";
	// $inquiryStatus[3]['key'] = "t-demo-done";
	// $inquiryStatus[3]['can_move_user'] = array(3, 4, 5, 6, 7, 8, 9, 10, 101);
	// $inquiryStatus[3]['is_last_status'] = 0;
	// $inquiryStatus[3]['need_followup'] = 1;

	// $inquiryStatus[4]['id'] = 4;
	// $inquiryStatus[4]['sub_ids'] = array(0);
	// $inquiryStatus[4]['name'] = "Site Visit";
	// $inquiryStatus[4]['key'] = "t-site-visit";
	// $inquiryStatus[4]['can_move_user'] = array(4, 5, 6, 7, 8, 9, 10, 101);
	// $inquiryStatus[4]['is_last_status'] = 0;
	// $inquiryStatus[4]['need_followup'] = 1;

	// $inquiryStatus[5]['id'] = 5;
	// $inquiryStatus[5]['sub_ids'] = array(0);
	// $inquiryStatus[5]['name'] = "Quotation";
	// $inquiryStatus[5]['key'] = "t-quotation";
	// $inquiryStatus[5]['can_move_user'] = array(5, 6, 7, 8, 9, 10, 102);
	// $inquiryStatus[5]['is_last_status'] = 0;
	// $inquiryStatus[5]['need_followup'] = 1;

	// $inquiryStatus[6]['id'] = 6;
	// $inquiryStatus[6]['sub_ids'] = array(0);
	// $inquiryStatus[6]['name'] = "Negotiation";
	// $inquiryStatus[6]['key'] = "t-negotiation";
	// $inquiryStatus[6]['can_move_user'] = array(6, 7, 8, 9, 10, 102);
	// $inquiryStatus[6]['is_last_status'] = 0;
	// $inquiryStatus[6]['need_followup'] = 1;

	// $inquiryStatus[7]['id'] = 7;
	// $inquiryStatus[7]['sub_ids'] = array(0);
	// $inquiryStatus[7]['name'] = "Order Confrimed";
	// $inquiryStatus[7]['key'] = "t-order-confrimed";
	// $inquiryStatus[7]['can_move_user'] = array(7, 8, 9, 10, 102);
	// $inquiryStatus[7]['is_last_status'] = 0;
	// $inquiryStatus[7]['need_followup'] = 1;

	// $inquiryStatus[8]['id'] = 8;
	// $inquiryStatus[8]['sub_ids'] = array(0);
	// $inquiryStatus[8]['name'] = "Closing";
	// $inquiryStatus[8]['key'] = "t-closing";
	// $inquiryStatus[8]['can_move_user'] = array(8, 9, 10, 102);
	// $inquiryStatus[8]['is_last_status'] = 0;
	// $inquiryStatus[8]['need_followup'] = 1;

	// $inquiryStatus[9]['id'] = 9;
	// $inquiryStatus[9]['sub_ids'] = array(9, 10);
	// $inquiryStatus[9]['name'] = "Material Sent";
	// $inquiryStatus[9]['key'] = "t-material-sent";
	// $inquiryStatus[9]['can_move_user'] = array(9, 10, 102);
	// $inquiryStatus[9]['is_last_status'] = 0;
	// $inquiryStatus[9]['need_followup'] = 0;

	// $inquiryStatus[10]['id'] = 10;
	// $inquiryStatus[10]['sub_ids'] = array(10);
	// $inquiryStatus[10]['name'] = "Claimed";
	// $inquiryStatus[10]['key'] = "t-climed";
	// $inquiryStatus[10]['can_move_user'] = array(10);
	// $inquiryStatus[10]['is_last_status'] = 0;
	// $inquiryStatus[10]['need_followup'] = 0;

	// $inquiryStatus[101]['id'] = 101;
	// $inquiryStatus[101]['sub_ids'] = array(101);
	// $inquiryStatus[101]['name'] = "Non Potential";
	// $inquiryStatus[101]['key'] = "t-non-potential";
	// $inquiryStatus[101]['can_move_user'] = array(101);
	// $inquiryStatus[101]['is_last_status'] = 1;
	// $inquiryStatus[101]['need_followup'] = 0;

	// $inquiryStatus[102]['id'] = 102;
	// $inquiryStatus[102]['sub_ids'] = array(102);
	// $inquiryStatus[102]['name'] = "Rejected";
	// $inquiryStatus[102]['key'] = "t-rejected";
	// $inquiryStatus[102]['can_move_user'] = array(102);
	// $inquiryStatus[102]['is_last_status'] = 1;
	// $inquiryStatus[102]['need_followup'] = 0;

	return $inquiryStatus;
}

function getLeadStatus()
{

	$leadStatus = array();
	$leadStatus[1]['id'] = 1;
	$leadStatus[1]['name'] = "Entry";
	$leadStatus[1]['type'] = 0;
	$leadStatus[1]['index'] = 1;

	$leadStatus[2]['id'] = 2;
	$leadStatus[2]['name'] = "Call";
	$leadStatus[2]['type'] = 0;
	$leadStatus[2]['index'] = 2;

	$leadStatus[3]['id'] = 3;
	$leadStatus[3]['name'] = "Qualified";
	$leadStatus[3]['type'] = 0;
	$leadStatus[3]['index'] = 3;

	$leadStatus[4]['id'] = 4;
	$leadStatus[4]['name'] = "Demo Meeting Done";
	$leadStatus[4]['type'] = 0;
	$leadStatus[4]['index'] = 4;

	$leadStatus[5]['id'] = 5;
	$leadStatus[5]['name'] = "Not Qualified";
	$leadStatus[5]['type'] = 0;
	$leadStatus[5]['index'] = 5;

	$leadStatus[6]['id'] = 6;
	$leadStatus[6]['name'] = "Cold";
	$leadStatus[6]['type'] = 0;
	$leadStatus[6]['index'] = 6;

	// $leadStatus[7]['id'] = 7;
	// $leadStatus[7]['name'] = "Demo Meeting Done";
	// $leadStatus[7]['type'] = 0;
	// $leadStatus[7]['index'] = 7;

	$leadStatus[100]['id'] = 100;
	$leadStatus[100]['name'] = "Quotation";
	$leadStatus[100]['type'] = 1;
	$leadStatus[100]['index'] = 7;

	$leadStatus[101]['id'] = 101;
	$leadStatus[101]['name'] = "Negotiation";
	$leadStatus[101]['type'] = 1;
	$leadStatus[101]['index'] = 8;

	$leadStatus[102]['id'] = 102;
	// $leadStatus[102]['name'] = "Order Confirm";
	$leadStatus[102]['name'] = "Token Received";
	$leadStatus[102]['type'] = 1;
	$leadStatus[102]['index'] = 9;

	$leadStatus[103]['id'] = 103;
	$leadStatus[103]['name'] = "Won";
	$leadStatus[103]['type'] = 1;
	$leadStatus[103]['index'] = 10;

	$leadStatus[104]['id'] = 104;
	$leadStatus[104]['name'] = "Lost";
	$leadStatus[104]['type'] = 1;
	$leadStatus[104]['index'] = 11;

	$leadStatus[105]['id'] = 105;
	$leadStatus[105]['name'] = "Cold";
	$leadStatus[105]['type'] = 1;
	$leadStatus[105]['index'] = 12;

	return $leadStatus;
}

function getLeadNextStatus($status_id){
	$status_id = (int) $status_id;
	$next_status_index = (int)getLeadStatus()[$status_id]['index'] + 1;
	$nextstatus = "";
	foreach (getLeadStatus() as $key => $value) {
		if($value['index'] == $next_status_index){
			$nextstatus = $value;
		}
	}	

	return $nextstatus;
}
function getArchitectsSourceTypes()
{

	$sourceTypes = array();

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "None";
	$sourceTypeObject['type'] = "fix";
	$sourceTypeObject['id'] = 50;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Electrician(Non Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 301;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Electrician(Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 302;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "ASM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 101;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "ADM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 102;
	$sourceTypes[$cSourceType] = $sourceTypeObject;
	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "APM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 103;
	$sourceTypes[$cSourceType] = $sourceTypeObject;
	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "AD";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 104;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	// $cSourceType = count($sourceTypes);
	// $sourceTypeObject = array();
	// $sourceTypeObject['lable'] = "Retailer";
	// $sourceTypeObject['type'] = "textrequired";
	// $sourceTypeObject['id'] = 51;
	// $sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Retailer";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 105;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Whitelion HO";
	$sourceTypeObject['type'] = "textnotrequired";
	$sourceTypeObject['id'] = 52;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Cold call";
	$sourceTypeObject['type'] = "fix";
	$sourceTypeObject['id'] = 53;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Marketing activities";
	$sourceTypeObject['type'] = "fix";
	$sourceTypeObject['id'] = 54;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Other";
	$sourceTypeObject['type'] = "textrequired";
	$sourceTypeObject['id'] = 55;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Existing Client";
	$sourceTypeObject['type'] = "textnotrequired";
	$sourceTypeObject['id'] = 56;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	return $sourceTypes;
}

function getInquirySourceTypes()
{

	$sourceTypes = array();
	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Architect(Non Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 201;
	$sourceTypes[$cSourceType] = $sourceTypeObject;
	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Architect(Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 202;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Electrician(Non Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 301;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Electrician(Prime)";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 302;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "ASM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 101;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "ADM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 102;
	$sourceTypes[$cSourceType] = $sourceTypeObject;
	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "APM";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 103;
	$sourceTypes[$cSourceType] = $sourceTypeObject;
	$cSourceType = count($sourceTypes);

	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "AD";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 104;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Retailer";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 105;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	// $cSourceType = count($sourceTypes);
	// $sourceTypeObject = array();
	// $sourceTypeObject['lable'] = "Retailer";
	// $sourceTypeObject['type'] = "textrequired";
	// $sourceTypeObject['id'] = 1;
	// $sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Whitelion HO";
	$sourceTypeObject['type'] = "textnotrequired";

	$sourceTypeObject['id'] = 2;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Cold call";
	$sourceTypeObject['type'] = "fix";
	$sourceTypeObject['id'] = 3;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Marketing activities";
	// $sourceTypeObject['type'] = "fix";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 4;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Other";
	$sourceTypeObject['type'] = "textrequired";
	$sourceTypeObject['id'] = 5;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Existing Client";
	$sourceTypeObject['type'] = "textnotrequired";
	$sourceTypeObject['id'] = 6;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Third Party";
	$sourceTypeObject['type'] = "user";
	$sourceTypeObject['id'] = 8;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$cSourceType = count($sourceTypes);
	$sourceTypeObject = array();
	$sourceTypeObject['lable'] = "Exhibition";
	$sourceTypeObject['type'] = "exhibition";
	$sourceTypeObject['id'] = 9;
	$sourceTypes[$cSourceType] = $sourceTypeObject;

	$isAdminOrCompanyAdmin = isAdminOrCompanyAdmin();
	if ($isAdminOrCompanyAdmin == 1) {

		$cSourceType = count($sourceTypes);
		$sourceTypeObject = array();
		$sourceTypeObject['lable'] = "None";
		$sourceTypeObject['type'] = "fix";
		$sourceTypeObject['id'] = 0;
		$sourceTypes[$cSourceType] = $sourceTypeObject;
	}

	return $sourceTypes;
}

function getAllUserTypes()
{

	$userTypes = getUserTypes();
	$channelPartners = getChannelPartners();
	$architects = getArchitects();
	$electricians = getElectricians();
	$customers = getCustomers();
	foreach ($channelPartners as $key => $value) {
		$userTypes[$key] = $value;
	}
	foreach ($architects as $key => $value) {
		$userTypes[$key] = $value;
	}
	foreach ($electricians as $key => $value) {
		$userTypes[$key] = $value;
	}
	foreach ($customers as $key => $value) {
		$userTypes[$key] = $value;
	}

	return $userTypes;
}

function getUserTypeName($userType)
{

	$userType = (int) $userType;
	$userTypeLable = "";
	if (isset(getUserTypes()[$userType]['short_name'])) {
		$userTypeLable = getUserTypes()[$userType]['short_name'];
	} else if (isset(getChannelPartners()[$userType]['short_name'])) {
		$userTypeLable = getChannelPartners()[$userType]['short_name'];
	} else if (isset(getArchitects()[$userType]['short_name'])) {
		$userTypeLable = getArchitects()[$userType]['short_name'];
	} else if (isset(getElectricians()[$userType]['short_name'])) {
		$userTypeLable = getElectricians()[$userType]['short_name'];
	} else if (isset(getCustomers()[$userType]['short_name'])) {
		$userTypeLable = getCustomers()[$userType]['short_name'];
	}

	return $userTypeLable;
}

function getUserTypeNameForLeadTag($userType)
{

	$userType = (int) $userType;
	$userTypeLable = "";
	if (isset(getUserTypes()[$userType]['short_name'])) {
		$userTypeLable = getUserTypes()[$userType]['short_name'];
	} else if (isset(getChannelPartners()[$userType]['short_name'])) {
		$userTypeLable = getChannelPartners()[$userType]['short_name'];
	} else if (isset(getArchitects()[$userType]['short_name'])) {
		$userTypeLable = "ARCHITECT ".getArchitects()[$userType]['short_name'];
	} else if (isset(getElectricians()[$userType]['short_name'])) {
		$userTypeLable = "ELECTRICIAN ".getElectricians()[$userType]['short_name'];
	} else if (isset(getCustomers()[$userType]['short_name'])) {
		$userTypeLable = getCustomers()[$userType]['short_name'];
	}

	return $userTypeLable;
}

function getChannelPartnersForAccount()
{

	if (Auth::user()->parent_id != 0) {

		$ChannelPartner = ChannelPartner::where('user_id', Auth::user()->parent_id)->first();
		$viewAccountOFChannelPartner = array();
		if ($ChannelPartner) {
			$viewAccountOFChannelPartner[] = getChannelPartners()[$ChannelPartner->type];
		}
	} else {
		$viewAccountOFChannelPartner = getChannelPartners();
	}

	return $viewAccountOFChannelPartner;
}

function isChannelPartner($userType)
{

	$isChannelPartner = 0;
	if (isset(getChannelPartners()[$userType]['id'])) {
		$isChannelPartner = getChannelPartners()[$userType]['id'];
	}
	return $isChannelPartner;
}

function isAdminOrCompanyAdmin()
{
	return (Auth::user()->type == 0 || Auth::user()->type == 1) ? 1 : 0;
}
function isSalePerson()
{
	return (Auth::user()->type == 2) ? 1 : 0;
}

function isPurchasePerson()
{
	return (Auth::user()->type == 10) ? 1 : 0;
}
function isAccountUser()
{
	return (Auth::user()->type == 3) ? 1 : 0;
}

function isDispatcherUser()
{
	return (Auth::user()->type == 4) ? 1 : 0;
}
function isArchitect()
{
	return (Auth::user()->type == 202) ? 1 : 0;
}

function isElectrician()
{
	return (Auth::user()->type == 302) ? 1 : 0;
}

function isMarketingUser()
{
	return (Auth::user()->type == 6) ? 1 : 0;
}

function isMarketingDispatcherUser()
{
	return (Auth::user()->type == 7) ? 1 : 0;
}

function isThirdPartyUser()
{
	return (Auth::user()->type == 8) ? 1 : 0;
}

function isTaleSalesUser()
{
	return (Auth::user()->type == 9) ? 1 : 0;
}

function userHasAcccess($userType)
{

	$accessTypes = getUsersAccess(Auth::user()->type);

	$accessTypesList = array();
	foreach ($accessTypes as $key => $value) {
		$accessTypesList[] = $value['id'];
	}

	if (in_array($userType, $accessTypesList)) {

		return true;
	} else {

		return false;
	}
}

function TeleSalesCity($userId)
{
	$TeleSales = TeleSales::where('user_id', $userId)->first();
	$cities = array(0);
	if ($TeleSales) {

		if ($TeleSales->cities != "") {
			$cities = explode(",", $TeleSales->cities);
		}
	}
	return $cities;
}

function SalesCity($userId)
{
	$SalePerson = SalePerson::where('user_id', $userId)->first();
	$cities = array(0);
	if ($SalePerson) {

		if ($SalePerson->cities != "") {
			$cities = explode(",", $SalePerson->cities);
		}
	}
	return $cities;
}

// function getInquiryStatusTabs($inquiryStatus) {

// 	$list = array();
// 	if ($inquiryStatus == "all") {

// 		$list = getInquiryStatus();

// 	} else {
// 		$inquiryStatus = explode(",", $inquiryStatus);
// 		foreach ($inquiryStatus as $key => $value) {
// 			$list[$key] = getInquiryStatus()[$value];
// 		}
// 	}

// 	return $list;

// }

function getUsersAccess($userType)
{

	$accessArray = array();

	$AllUserTypes = getUserTypes();

	if ($userType == 0) {

		$accessIds = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 1) {

		$accessIds = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 2) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 3) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 4) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 5) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 6) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 7) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 101) {

		$accessIds = array(3, 4);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 102) {

		$accessIds = array(3, 4);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 103) {

		$accessIds = array(3, 4);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 104) {

		$accessIds = array();
		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 105) {

		$accessIds = array();

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 11) {

		$accessIds = array(0, 1, 11);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	}
	return $accessArray;
}

function getChannelPartnersAccess($userType)
{

	$accessArray = array();

	$AllUserTypes = getChannelPartners();

	if ($userType == 0) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 1) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 2) {

		$accessIds = array(104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 6) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	} else if ($userType == 9) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllUserTypes[$value];
		}
	}

	return $accessArray;
}

function getsubOrdersTabs($userType)
{

	$accessArray = array();

	$AllChannelPartners = getChannelPartners();

	if ($userType == 0) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllChannelPartners[$value];
		}
	} else if ($userType == 1) {

		$accessIds = array(101, 102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllChannelPartners[$value];
		}
	} else if ($userType == 101) {

		$accessIds = array(102, 103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllChannelPartners[$value];
		}
	} else if ($userType == 102) {

		$accessIds = array(103, 104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllChannelPartners[$value];
		}
	} else if ($userType == 103) {

		$accessIds = array(104, 105);

		foreach ($accessIds as $key => $value) {
			$accessArray[$key] = $AllChannelPartners[$value];
		}
	}

	return $accessArray;
}
function createThumbs($sourceFilePath, $destinationFilePath, $maxWidth)
{

	/////////// CREATE THUMB

	$quality = 100;
	$imgsize = getimagesize($sourceFilePath);
	$width = $imgsize[0];
	$height = $imgsize[1];
	$mime = $imgsize['mime'];

	switch ($mime) {
		case 'image/gif':
			$imageCreate = "imagecreatefromgif";
			$image = "imagegif";
			break;

		case 'image/png':
			$imageCreate = "imagecreatefrompng";
			$image = "imagepng";
			$quality = 7;
			break;

		case 'image/jpeg':
			$imageCreate = "imagecreatefromjpeg";
			$image = "imagejpeg";
			$quality = 80;
			break;
		default:
			return false;
			break;
	}

	$scalRatio = ($maxWidth / $width);
	$maxHeight = round($scalRatio * $height);
	$dstImg = imagecreatetruecolor($maxWidth, $maxHeight);
	///////////////
	imagealphablending($dstImg, false);
	imagesavealpha($dstImg, true);
	///IF IMAGE IS TRANSPERANT THEN THUMBNAI RESIZABLE IMAGE WILL TRANSPERANT ,,IF NOT USE THIS FUNCTION GET IMAGE BACKGROUD WHITE
	$transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
	imagefilledrectangle($dstImg, 0, 0, $maxWidth, $maxHeight, $transparent);
	/////////////
	$srcImg = $imageCreate($sourceFilePath);
	imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);

	$image($dstImg, $destinationFilePath, $quality);
	if ($dstImg) {
		imagedestroy($dstImg);
	}

	if ($srcImg) {
		imagedestroy($srcImg);
	}
}

function getSalesPersonReportingManager($userId)
{

	$SalePerson = SalePerson::select('reporting_manager_id')->where('user_id', $userId)->first();
	return $SalePerson;
}

function getChannelPartnerSalesPersonsIds($userId)
{

	$ChannelPartner = ChannelPartner::select('sale_persons')->where('user_id', $userId)->first();
	$salesPersons = array();
	if ($ChannelPartner) {
		$salePersons = $ChannelPartner->sale_persons;
		$salePersons = explode(",", $salePersons);
	}

	return $salePersons;
}

function getChildSalePersonsIds($userId)
{

	$SalePersons = SalePerson::select('user_id')->where('reporting_manager_id', $userId)->get();
	$SalePersonsIds = array();
	$SalePersonsIds[] = $userId;

	foreach ($SalePersons as $key => $value) {
		$SalePersonsIds[] = $value['user_id'];
		$getChildSalePersonsIds = getChildSalePersonsIds($value['user_id']);
		$SalePersonsIds = array_merge($SalePersonsIds, $getChildSalePersonsIds);
	}
	$SalePersonsIds = array_unique($SalePersonsIds);
	$SalePersonsIds = array_values($SalePersonsIds);
	return $SalePersonsIds;
}

function getParentSalePersonsIds($userId)
{
	$SalePersons = SalePerson::select('reporting_manager_id')->where('user_id', $userId)->first();
	$SalePersonsIds = array();
	if ($SalePersons) {

		if ($SalePersons->reporting_manager_id == 0) {
			return array(0);
		} else {
			$SalePersonsIds[] = $SalePersons->reporting_manager_id;

			$getParentsSalePersonsIds = getParentSalePersonsIds($SalePersons->reporting_manager_id);

			$SalePersonsIds = array_merge($SalePersonsIds, $getParentsSalePersonsIds);
		}
	} else {
		return array(0);
	}
	$SalePersonsIds = array_unique($SalePersonsIds);
	$SalePersonsIds = array_values($SalePersonsIds);
	return $SalePersonsIds;
}

function UsersNotificationTokens($userId)
{

	$notificationTokens = array();
	$Users = User::select('fcm_token')->whereIn('id', $userId)->orWhere('type', 0)->get();
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
	} else if ($serviceHierarchyStatus == 1) {
		$serviceHierarchyStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> Active</span>';
	} else if ($serviceHierarchyStatus == 2) {
		$serviceHierarchyStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Blocked</span>';
	}
	return $serviceHierarchyStatus;
}

function isServiceExecutiveUser()
{
	return (Auth::user()->type == 10) ? 1 : 0;
}

function getParentServiceExecutivesIds($userId)
{
	$ServiceExecutives = Wlmst_ServiceExecutive::select('reporting_manager_id')->where('user_id', $userId)->first();
	$ServiceExecutivessIds = array();
	if ($ServiceExecutives) {

		if ($ServiceExecutives->reporting_manager_id == 0) {
			return array(0);
		} else {
			$ServiceExecutivessIds[] = $ServiceExecutives->reporting_manager_id;

			$getParentsServiceExecutivessIds = getParentServiceExecutivesIds($ServiceExecutives->reporting_manager_id);

			$ServiceExecutivessIds = array_merge($ServiceExecutivessIds, $getParentsServiceExecutivessIds);
		}
	} else {
		return array(0);
	}
	$ServiceExecutivessIds = array_unique($ServiceExecutivessIds);
	$ServiceExecutivessIds = array_values($ServiceExecutivessIds);
	return $ServiceExecutivessIds;
}

function getChildChannelPartners($userId, $type)
{

	$childChannelPartnersId = array();

	if ($type == 0) {

		$ChannelPartners102 = ChannelPartner::select('user_id')->where('type', 102)->where('reporting_manager_id', $userId)->get();

		if (count($ChannelPartners102) > 0) {

			$ChannelPartners102Id = array();

			foreach ($ChannelPartners102 as $key => $value) {
				$ChannelPartners102Id[] = $value->user_id;
			}

			$childChannelPartnersId = array_merge($childChannelPartnersId, $ChannelPartners102Id);

			$ChannelPartners103 = ChannelPartner::select('user_id')->where('type', 103)->whereIn('reporting_manager_id', $ChannelPartners102Id)->get();

			if (count($ChannelPartners103) > 0) {

				$ChannelPartners103Id = array();

				foreach ($ChannelPartners103 as $key => $value) {
					$ChannelPartners103Id[] = $value->user_id;
				}

				$childChannelPartnersId = array_merge($childChannelPartnersId, $ChannelPartners103Id);

				$ChannelPartners104 = ChannelPartner::select('user_id')->where('type', 104)->whereIn('reporting_manager_id', $ChannelPartners103Id)->get();

				if (count($ChannelPartners104) > 0) {

					foreach ($ChannelPartners104 as $key => $value) {

						$childChannelPartnersId[] = $value->user_id;
					}
				}
			}
		}
	} else if ($type == 102) {

		$ChannelPartners102 = ChannelPartner::select('user_id')->where('type', 102)->where('reporting_manager_id', $userId)->get();

		if (count($ChannelPartners102) > 0) {

			foreach ($ChannelPartners102 as $key => $value) {

				$childChannelPartnersId[] = $value->user_id;
			}
		}
	} else if ($type == 103) {

		$ChannelPartners102 = ChannelPartner::select('user_id')->where('type', 102)->where('reporting_manager_id', $userId)->get();

		if (count($ChannelPartners102) > 0) {
			$ChannelPartners102Id = array();

			foreach ($ChannelPartners102 as $key => $value) {
				$ChannelPartners102Id[] = $value->user_id;
			}

			$ChannelPartners103 = ChannelPartner::select('user_id')->where('type', 103)->whereIn('reporting_manager_id', $ChannelPartners102Id)->get();

			if (count($ChannelPartners103) > 0) {

				foreach ($ChannelPartners103 as $key => $value) {

					$childChannelPartnersId[] = $value->user_id;
				}
			}
		}
	} else if ($type == 104) {

		$ChannelPartners102 = ChannelPartner::select('user_id')->where('type', 102)->where('reporting_manager_id', $userId)->get();

		if (count($ChannelPartners102) > 0) {

			$ChannelPartners102Id = array();

			foreach ($ChannelPartners102 as $key => $value) {
				$ChannelPartners102Id[] = $value->user_id;
			}

			$ChannelPartners103 = ChannelPartner::select('user_id')->where('type', 103)->whereIn('reporting_manager_id', $ChannelPartners102Id)->get();

			if (count($ChannelPartners103) > 0) {

				$ChannelPartners103Id = array();

				foreach ($ChannelPartners103 as $key => $value) {
					$ChannelPartners103Id[] = $value->user_id;
				}

				$ChannelPartners104 = ChannelPartner::select('user_id')->where('type', 104)->whereIn('reporting_manager_id', $ChannelPartners103Id)->get();

				if (count($ChannelPartners104) > 0) {

					foreach ($ChannelPartners104 as $key => $value) {

						$childChannelPartnersId[] = $value->user_id;
					}
				}
			}
		}
	} else if ($type == 105) {

		$ChannelPartners102 = ChannelPartner::select('user_id')->where('type', 102)->where('reporting_manager_id', $userId)->get();

		if (count($ChannelPartners102) > 0) {

			$ChannelPartners102Id = array();

			foreach ($ChannelPartners102 as $key => $value) {
				$ChannelPartners102Id[] = $value->user_id;
			}

			$ChannelPartners103 = ChannelPartner::select('user_id')->where('type', 103)->whereIn('reporting_manager_id', $ChannelPartners102Id)->get();

			if (count($ChannelPartners103) > 0) {

				$ChannelPartners103Id = array();

				foreach ($ChannelPartners103 as $key => $value) {
					$ChannelPartners103Id[] = $value->user_id;
				}

				$ChannelPartners104 = ChannelPartner::select('user_id')->where('type', 104)->whereIn('reporting_manager_id', $ChannelPartners103Id)->get();

				if (count($ChannelPartners104) > 0) {

					$ChannelPartners104Id = array();
					foreach ($ChannelPartners104 as $key => $value) {

						$ChannelPartners104Id[] = $value->user_id;
					}

					$ChannelPartners105 = ChannelPartner::select('user_id')->where('type', 105)->whereIn('reporting_manager_id', $ChannelPartners104Id)->get();

					if (count($ChannelPartners105) > 0) {

						foreach ($ChannelPartners105 as $key => $value) {
	
							$childChannelPartnersId[] = $value->user_id;
						}
					}
				}
			}
		}
	}

	return $childChannelPartnersId;
}

function GSTPercentage()
{
	return 18;
}

function calculationProcessOfOrder($orderItems, $GSTPercentage, $shippingCost)
{

	$order = array();
	$order['total_qty'] = 0;
	$order['total_weight'] = 0;
	$order['total_mrp'] = 0;
	$order['total_discount'] = 0;
	$order['total_mrp_minus_disocunt'] = 0;
	$order['gst_percentage'] = floatval($GSTPercentage);
	$order['gst_tax'] = 0;
	$order['shipping_cost'] = floatval($shippingCost);
	$order['delievery_charge'] = 0;
	$order['total_payable'] = 0;
	$order['created_dt'] = date('Y-m-d H:i:s');

	foreach ($orderItems as $key => $value) {

		$orderItems[$key]['id'] = $value['id'];
		if (isset($value['info'])) {
			$orderItems[$key]['info'] = $value['info'];
		}

		//
		$productPrice = floatval($value['mrp']);
		$orderItems[$key]['mrp'] = $productPrice;
		//

		//
		$orderItemQTY = intval($value['qty']);
		$orderItems[$key]['qty'] = $orderItemQTY;
		$order['total_qty'] = $order['total_qty'] + $orderItemQTY;
		//

		//
		$OrderItemsMRP = ($orderItemQTY * $productPrice);
		$orderItems[$key]['total_mrp'] = $OrderItemsMRP;
		$order['total_mrp'] = $order['total_mrp'] + $OrderItemsMRP;
		//

		//
		$discountPercentage = floatval($value['discount_percentage']);
		$orderItems[$key]['discount_percentage'] = $discountPercentage;

		$totalDiscount = 0;
		if ($discountPercentage > 0) {
			$totalDiscount = round(($discountPercentage / 100) * $OrderItemsMRP, 2);
		}

		$discount = 0;

		if ($discountPercentage > 0) {
			$discount = round(($discountPercentage / 100) * $productPrice, 2);
		}

		//
		$orderItems[$key]['discount'] = $discount;
		$orderItems[$key]['total_discount'] = $totalDiscount;
		$order['total_discount'] = round($order['total_discount'] + $totalDiscount, 2);
		//

		//
		$mrpMinusDiscount = round($OrderItemsMRP - $totalDiscount, 2);
		$orderItems[$key]['mrp_minus_disocunt'] = $mrpMinusDiscount;
		$order['total_mrp_minus_disocunt'] = $order['total_mrp_minus_disocunt'] + $mrpMinusDiscount;

		//
		$productWeight = floatval($value['weight']);
		$orderItemTotalWeight = $productWeight * $orderItemQTY;
		$orderItems[$key]['weight'] = $productWeight;
		$orderItems[$key]['total_weight'] = $orderItemTotalWeight;
		$order['total_weight'] = $order['total_weight'] + $orderItemTotalWeight;
	}

	$order['total_mrp_minus_disocunt'] = round($order['total_mrp_minus_disocunt'], 2);

	if ($order['gst_percentage'] != 0) {
		$order['gst_tax'] = round(($order['gst_percentage'] / 100) * $order['total_mrp_minus_disocunt'], 2);
	}

	$order['weightInKG'] = $order['total_weight'] / 1000;
	$order['delievery_charge'] = (round($order['weightInKG'] * $order['shipping_cost'], 2));

	$order['total_payable'] = round($order['total_mrp_minus_disocunt'] + $order['gst_tax'] + $order['delievery_charge'], 2);
	$order['items'] = $orderItems;

	return $order;
}

function calculationProcessOfMarketingRequest($orderItems)
{

	$order = array();
	$order['total_qty'] = 0;
	$order['total_weight'] = 0;
	$order['total_mrp'] = 0;
	$order['total_discount'] = 0;
	$order['total_mrp_minus_disocunt'] = 0;
	// $order['gst_percentage'] = floatval($GSTPercentage);
	$order['gst_tax'] = 0;
	$order['shipping_cost'] = 0;
	$order['delievery_charge'] = 0;
	$order['total_payable'] = 0;
	$order['created_dt'] = date('Y-m-d H:i:s');

	foreach ($orderItems as $key => $value) {

		$orderItems[$key]['id'] = $value['id'];
		if (isset($value['info'])) {
			$orderItems[$key]['info'] = $value['info'];
		}

		//
		$productPrice = floatval($value['mrp']);
		$orderItems[$key]['mrp'] = $productPrice;
		$GSTPercentage = floatval($value['gst_percentage']);

		$orderItems[$key]['gst_percentage'] = $GSTPercentage;
		//

		//
		$orderItemQTY = intval($value['qty']);
		$orderItems[$key]['qty'] = $orderItemQTY;
		$order['total_qty'] = $order['total_qty'] + $orderItemQTY;
		//

		//
		$OrderItemsMRP = ($orderItemQTY * $productPrice);
		$orderItems[$key]['total_mrp'] = $OrderItemsMRP;
		$order['total_mrp'] = $order['total_mrp'] + $OrderItemsMRP;
		//

		$orderItems[$key]['gst_percentage'] = $value['gst_percentage'];

		//
		$GSTTax = ($productPrice * $GSTPercentage) / 100;
		$orderItems[$key]['gst_tax'] = $GSTTax;
		$GSTTaxTotal = $orderItemQTY * $GSTTax;
		$orderItems[$key]['total_gst_tax'] = $GSTTaxTotal;
		$order['gst_tax'] = $order['gst_tax'] + $GSTTaxTotal;
		//

		//
		$discountPercentage = floatval($value['discount_percentage']);
		$orderItems[$key]['discount_percentage'] = $discountPercentage;

		$totalDiscount = 0;
		if ($discountPercentage > 0) {
			$totalDiscount = round(($discountPercentage / 100) * $OrderItemsMRP, 2);
		}

		$discount = 0;

		if ($discountPercentage > 0) {
			$discount = round(($discountPercentage / 100) * $productPrice, 2);
		}

		//
		$orderItems[$key]['discount'] = $discount;
		$orderItems[$key]['total_discount'] = $totalDiscount;
		$order['total_discount'] = round($order['total_discount'] + $totalDiscount, 2);
		//

		//
		$mrpMinusDiscount = round($OrderItemsMRP - $totalDiscount, 2);
		$orderItems[$key]['mrp_minus_disocunt'] = $mrpMinusDiscount;
		$order['total_mrp_minus_disocunt'] = $order['total_mrp_minus_disocunt'] + $mrpMinusDiscount;

		//	$order['total_mrp_minus_disocunt'] = $order['total_mrp_minus_disocunt'] + $mrpMinusDiscount;

		//
		$productWeight = floatval($value['weight']);
		$orderItemTotalWeight = $productWeight * $orderItemQTY;
		$orderItems[$key]['weight'] = $productWeight;
		$orderItems[$key]['total_weight'] = $orderItemTotalWeight;
		$order['total_weight'] = $order['total_weight'] + $orderItemTotalWeight;
	}

	$order['total_mrp_minus_disocunt'] = round($order['total_mrp_minus_disocunt'], 2);
	$order['gst_tax'] = round($order['gst_tax'], 2);

	// if ($order['gst_percentage'] != 0) {
	// 	$order['gst_tax'] = round(($order['gst_percentage'] / 100) * $order['total_mrp_minus_disocunt'], 2);
	// }

	// $order['weightInKG'] = $order['total_weight'] / 1000;
	// $order['delievery_charge'] = (round($order['weightInKG'] * $order['shipping_cost'], 2));

	$order['total_payable'] = round($order['total_mrp_minus_disocunt'] + $order['gst_tax'], 2);
	$order['items'] = $orderItems;

	// echo '<pre>';
	// print_r($order);
	// die;

	return $order;
}

function acceptFileTypes($type, $systemType)
{

	if ($type == "order.invoice") {

		if ($systemType == "client") {

			return [
				"application/pdf", "application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"image/*",
			];
		} else if ($systemType == "server") {
			return ['pdf', 'doc', 'xlsx', 'xls', 'png', 'jpeg', 'jpg'];
		}
	} else if ($type == "order.eway.bill") {

		if ($systemType == "client") {

			return [
				"application/pdf", "application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"image/*",
			];
		} else if ($systemType == "server") {
			return ['pdf', 'doc', 'xlsx', 'xls', 'png', 'jpeg', 'jpg'];
		}
	} else if ($type == "order.dispatch.detail") {

		if ($systemType == "client") {

			return [
				"application/pdf", "application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"image/*",
			];
		} else if ($systemType == "server") {
			return ['pdf', 'doc', 'xls', 'xls', 'png', 'jpeg', 'jpg'];
		}
	} else if ($type == "gift.order.dispatch.detail") {

		if ($systemType == "client") {

			return [
				"application/pdf", "application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"image/*",
			];
		} else if ($systemType == "server") {
			return ['pdf', 'doc', 'xls', 'xls', 'png', 'jpeg', 'jpg'];
		}
	} else if ($type == "marketing.challan") {

		if ($systemType == "client") {

			return [
				"application/pdf", "application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"image/*",
			];
		} else if ($systemType == "server") {
			return ['pdf', 'doc', 'xls', 'xls', 'png', 'jpeg', 'jpg'];
		}
	}
}

function getPreviousMonths($noOfMonth)
{

	$GMTDateTime = date('Y-m-d H:i:s');
	$TIMEZONE = websiteTimeZone();
	$dt = new DateTime('@' . strtotime($GMTDateTime));
	$dt->setTimeZone(new DateTimeZone($TIMEZONE));
	$myCurrentDate = $dt->format('Y-m-d H:i:s');

	$r = array();

	for ($i = 0; $i < $noOfMonth; $i++) {

		if ($i != 0) {

			$myCurrentDate = date('Y-m-d H:i:s', strtotime($myCurrentDate . " -1 month"));
		}

		$r[$i]['start'] = date('Y-m-1 00:00:00', strtotime($myCurrentDate));
		$r[$i]['end'] = date('Y-m-t 23:59:59', strtotime($myCurrentDate));
		$r[$i]['name'] = date('Y-F', strtotime($myCurrentDate));

		$start = new DateTime($r[$i]['start'], new DateTimeZone($TIMEZONE));
		$start->setTimeZone(new DateTimeZone("GMT"));

		$end = new DateTime($r[$i]['end'], new DateTimeZone($TIMEZONE));
		$end->setTimeZone(new DateTimeZone("GMT"));

		$r[$i]['start_gmt'] = $start->format('Y-m-d H:i:s');
		$r[$i]['end_gmt'] = $end->format('Y-m-d H:i:s');
	}

	return $r;
}

function displayStringLenth($string, $maxLength)
{

	$totalStringLenth = strlen($string);
	if ($totalStringLenth > $maxLength) {
		$stringCrop = substr($string, 0, ($maxLength - 3));
		$string = $stringCrop . "...";
	}
	return $string;
}

function getUserNotificationTypes()
{

	$userTypes = array();
	$userTypes[1]['id'] = 1;
	$userTypes[1]['description'] = "Inquiry Update";
	$userTypes[1]['assigned'] = 0;
	$userTypes[1]['mentioned'] = 0;

	$userTypes[2]['id'] = 2;
	$userTypes[2]['description'] = "Inquiry Update Reply";
	$userTypes[2]['assigned'] = 0;
	$userTypes[2]['mentioned'] = 0;

	$userTypes[3]['id'] = 3;
	$userTypes[3]['description'] = "Inquiry change assigned";
	$userTypes[3]['assigned'] = 1;
	$userTypes[3]['mentioned'] = 0;

	$userTypes[4]['id'] = 4;
	$userTypes[4]['description'] = "Inquiry mentioned ";
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

function architectInquiryCalculation($userId)
{

	$query = Inquiry::query();
	$query->where(function ($query2) use ($userId) {

		$query2->where(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type', array("user-201", "user-202"));
			$query3->where('inquiry.source_type_value', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_1', array("user-201", "user-202"));
			$query3->where('inquiry.source_type_value_1', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_2', array("user-201", "user-202"));
			$query3->where('inquiry.source_type_value_2', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_3', array("user-201", "user-202"));
			$query3->where('inquiry.source_type_value_3', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_4', array("user-201", "user-202"));
			$query3->where('inquiry.source_type_value_4', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->where('inquiry.architect', $userId);
		});
	});
	$recordsTotal = $query->count();
	$Architect = Architect::where('user_id', $userId)->first();
	if ($Architect) {
		$Architect->total_inquiry = $recordsTotal;
		$Architect->save();
	}
}
function elecricianInquiryCalculation($userId)
{

	$query = Inquiry::query();
	$query->where(function ($query2) use ($userId) {

		$query2->where(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type', array("user-301", "user-302"));
			$query3->where('inquiry.source_type_value', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_1', array("user-301", "user-302"));
			$query3->where('inquiry.source_type_value_1', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_2', array("user-301", "user-302"));
			$query3->where('inquiry.source_type_value_2', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_3', array("user-301", "user-302"));
			$query3->where('inquiry.source_type_value_3', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->whereIn('inquiry.source_type_4', array("user-301", "user-302"));
			$query3->where('inquiry.source_type_value_4', $userId);
		});

		$query2->orWhere(function ($query3) use ($userId) {

			$query3->where('inquiry.electrician', $userId);
		});
	});
	$recordsTotal = $query->count();

	$Electrician = Electrician::where('user_id', $userId)->first();
	if ($Electrician) {
		$Electrician->total_inquiry = $recordsTotal;
		$Electrician->save();
	}
}

function getMyPrivilege($code)
{

	$hasPrivilege = 0;
	if (Auth::user()->privilege != "") {

		$privilege = json_decode(Auth::user()->privilege, true);
		if (isset($privilege[$code]) && $privilege[$code] == 1) {
			$hasPrivilege = 1;
		}
	}
	return $hasPrivilege;
}

function configrationForNotify()
{
	$response = array();
	$response['from_email'] = "noreply@whitelion.in";
	$response['from_name'] = "Whitelion";
	$response['to_name'] = "Whitelion";

	////TESTING
	$response['test_email'] = "meetshekhaliya2002@gmail.com";
	// $response['test_email'] = "ankit.in1184@gmail.com";
	// $response['test_phone_number'] = "9824717656";
	$response['test_phone_number'] = "9510532543";
	$response['test_email_bcc'] = array("meetshekhaliya2002@gmail.com");
	// $response['test_email_bcc'] = array("ankit.in1184@gmail.com");
	$response['test_email_cc'] = array("meetshekhaliya2002@gmail.com");
	// $response['test_email_cc'] = array("ankit.in1184@gmail.com");
	return $response;
}

// function fromEmailDetail() {
// 	$fromEmailDetail = array();
// 	$fromEmailDetail['email'] = "developer@whitelion.in";
// 	$fromEmailDetail['name'] = "Whitelion";
// 	return $fromEmailDetail;
// }

function getMainMasterPrivilege($userType)
{

	$MainMasterPrivilege = array();

	if ($userType == 0) {
		$MainMasterPrivilege[] = "PRODUCT_BRAND";
		$MainMasterPrivilege[] = "PRODUCT_CODE";
		$MainMasterPrivilege[] = "INCENTIVE_QUARTER";
		$MainMasterPrivilege[] = "COURIER_SERVICE";
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_CODE";
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_GROUP";
	} else if ($userType == 1) {

		$MainMasterPrivilege[] = "PRODUCT_BRAND";
		$MainMasterPrivilege[] = "PRODUCT_CODE";
		$MainMasterPrivilege[] = "INCENTIVE_QUARTER";
		$MainMasterPrivilege[] = "COURIER_SERVICE";
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_CODE";
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_GROUP";
	} else if ($userType == 6) {
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_CODE";
		$MainMasterPrivilege[] = "MARKETING_PRODUCT_GROUP";
	}
	return $MainMasterPrivilege;
}

function sendOTPToMobile($mobileNumber, $otp)
{

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=624fe2f9427ab2782b2fae2b&mobile=" . $mobileNumber . "&authkey=124116Awe37ib8e57e66f9b&otp=" . $otp,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "",
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
		//echo "cURL Error #:" . $err;
		$return = errorRes("");
		$return['response'] = $err;
		return $return;
	} else {
		$return = successRes("");
		$return['response'] = $response;
		return $return;
	}
}

function sendNotificationTOAndroid($title, $message, $FcmToken, $screenName, $data_value)
{

	if (count($FcmToken) > 0) {

		$url = 'https://fcm.googleapis.com/fcm/send';

		$serverKey = 'AAAAjO_9mB8:APA91bFUg5s0ou4vzSmuf6EqTLNu3bLpOXJa-v8GwW9HHzC-27ZtEUFloHiMx0Itc6ZhuN3MOitsjG1eRaV5RjDInoSqT4veSXu-TqnyGL_bFkSIH0hIYUmxB6YA77vVenEWPraVR1ma';

		$data = [
			"registration_ids" => $FcmToken,
			"notification" => [
				"title" => $title,
				"body" => $message,
			],
			"data" => [
				"priority" => "high",
				"sound" => "default",
				"content_available" => true,
				"screen" => $screenName,
				"data_value" => json_encode($data_value)
			]
		];
		$encodedData = json_encode($data);

		$headers = [
			'Authorization:key=' . $serverKey,
			'Content-Type: application/json',
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		// Close connection
		curl_close($ch);
		// FCM response
		// dd($result);
		// return $result;
		$noti_responce = json_decode($result);
		$response = array();
		$response['status'] = $noti_responce->success;
		$response['status_code'] = $noti_responce->success == 1 ? 200 : 400;
		$response['msg'] = $noti_responce->success == 1 ? "Notification Send Successfully" : "Notification Failed";
		$response['noti_msg'] = $noti_responce;
	} else {
		$response = array();
		$response['status'] = 0;
		$response['status_code'] = 400;
		$response['msg'] = "No Token";
		$response['noti_msg'] = "";
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

function quoterrorRes($status = 0, $statusCode = 400, $msg = "Error")
{
	$return = array();
	$return['status'] = $status; // 1=Success; 0=error; 2=appupdate
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}
function quotsuccessRes($status = 1, $statusCode = 200, $msg = "Success")
{
	$return = array();
	$return['status'] = $status; // 1=Success; 0=error; 2=appupdate
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}

function saveBoardSaveLog($params)
{
	$BoardSaveLog = new wlmst_user_created_board_log();
	// $BoardSaveLog->user_id = Auth::user()->id;
	$BoardSaveLog->user_id = '1';
	$BoardSaveLog->quot_id = $params['quot_id'];
	$BoardSaveLog->quotgroup_id = $params['quotgroup_id'];
	$BoardSaveLog->room_no = $params['room_no'];
	$BoardSaveLog->board_no = $params['board_no'];
	$BoardSaveLog->description = $params['description'];
	$BoardSaveLog->source = $params['source'];
	$BoardSaveLog->entryby = '1';
	// $BoardSaveLog->entryby = Auth::user()->id;
	$BoardSaveLog->entryip = $params['entryip'];
	$BoardSaveLog->save();
}

function getQuotationMasterStatusLable($mainMasterStatus)
{
	$mainMasterStatus = (int) $mainMasterStatus;

	if ($mainMasterStatus == 0) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-running font-size-11"> Running</span>';
	} else if ($mainMasterStatus == 1) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-success font-size-11"> New Request</span>';
	} else if ($mainMasterStatus == 2) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-change-request font-size-11"> Change Request</span>';
	} else if ($mainMasterStatus == 3) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-confirm font-size-11"> Confirm Quotation</span>';
	} else if ($mainMasterStatus == 4) {
		$mainMasterStatus = '<span class="badge badge-pill badge-soft-danger font-size-11"> Rejected Quotation</span>';
	}
	return $mainMasterStatus;
}
function getQuotationMasterStatusLableText($mainMasterStatus)
{
	$mainMasterStatus = (int) $mainMasterStatus;

	if ($mainMasterStatus == 0) {
		$mainMasterStatus = 'Running';
	} else if ($mainMasterStatus == 1) {
		$mainMasterStatus = 'New Request';
	} else if ($mainMasterStatus == 2) {
		$mainMasterStatus = 'Change Request';
	} else if ($mainMasterStatus == 3) {
		$mainMasterStatus = 'Confirm';
	} else if ($mainMasterStatus == 4) {
		$mainMasterStatus = 'Rejected';
	}
	return $mainMasterStatus;
}

function getQuotationStatus()
{

	$QuotStatusTypes = array();
	$QuotStatusTypes[0]['id'] = 0;
	$QuotStatusTypes[0]['name'] = "Running";
	$QuotStatusTypes[0]['short_name'] = "RUNNING";
	$QuotStatusTypes[0]['sequence'] = 5;

	$QuotStatusTypes[1]['id'] = 1;
	$QuotStatusTypes[1]['name'] = "New Request";
	$QuotStatusTypes[1]['short_name'] = "NEW REQUEST";
	$QuotStatusTypes[1]['sequence'] = 1;

	$QuotStatusTypes[2]['id'] = 2;
	$QuotStatusTypes[2]['name'] = "Change Request";
	$QuotStatusTypes[2]['short_name'] = "CHANGE REQUEST";
	$QuotStatusTypes[2]['sequence'] = 2;

	$QuotStatusTypes[3]['id'] = 3;
	$QuotStatusTypes[3]['name'] = "Confirm";
	$QuotStatusTypes[3]['short_name'] = "CONFIRM";
	$QuotStatusTypes[3]['sequence'] = 3;

	$QuotStatusTypes[4]['id'] = 4;
	$QuotStatusTypes[4]['name'] = "Rejected";
	$QuotStatusTypes[4]['short_name'] = "REJECTED";
	$QuotStatusTypes[4]['sequence'] = 4;

	return $QuotStatusTypes;
}

function formatbBytes($bytes, $precision = 2)
{
	$units = array('B', 'KB', 'MB', 'GB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1000));
	$pow = min($pow, count($units) - 1);

	$bytes /= pow(1000, $pow);

	return round($bytes, $precision) . ' ' . $units[$pow];
}

// -------------------- QUOTATION GLOBLE API CREATED END --------------------
function getpercentage($base_amt, $new_amt)
{
	if ($base_amt == 0) {
		return 0.00;
	} else {
		return number_format((floatval($new_amt) * 100 / $base_amt), 2, '.', '');
	}
}
function getQuaterFromMonth($monthNumber)
{
	// START FROM JAN = Q1
	// return floor(($monthNumber - 1) / 3) + 1;

	// START FROM APRIL = Q1
	$quarter = floor(($monthNumber - 1) / 3);
	return ($quarter == 0) ? 4 : $quarter;
}
function getDateFromFinancialYear($financialyear)
{
	$start_year = explode("-", $financialyear)[0];
	$end_year = explode("-", $financialyear)[1];

	$start_date = $start_year . '-04-01' . ' 00:00:00';
	$end_date = $end_year . '-03-31' . ' 23:59:59';

	$start = date('Y-m-d 00:00:00', strtotime($start_date));
	$end = date('Y-m-d 23:59:59', strtotime($end_date));

	$return = array();
	$return['start'] = $start;
	$return['end'] = $end;
	return $return;
}

function getDatesFromQuarter($quarter, $financialyear)
{
	$start_year = explode("-", $financialyear)[0];
	$end_year = explode("-", $financialyear)[1];
	$start_year = $quarter == 4 ? $end_year : $start_year;
	$end_year = $quarter == 4 ? $end_year : $start_year;

	$start_date = $quarter == 4 ? "01" : (3 * $quarter + 1) . "-01 00:00:00";
	$start_date = $start_year . '-' . $start_date;
	$end_date = $quarter == 4 ? '03' : (3 * $quarter + 3) . '-' . ($quarter == 3 || $quarter == 4 ? 31 : 30) . ' 23:59:59';
	$end_date = $end_year . '-' . $end_date;

	$start = date('Y-m-d 00:00:00', strtotime($start_date));
	$end = date('Y-m-d 23:59:59', strtotime($end_date));

	$return = array();
	$return['start'] = $start;
	$return['end'] = $end;
	return $return;
}

function getDatesFromMonth($month, $financialyear)
{
	$quarter = getQuaterFromMonth($month);
	$start_year = explode("-", $financialyear)[0];
	$end_year = explode("-", $financialyear)[1];
	$start_year = $quarter == 4 ? $end_year : $start_year;
	$end_year = $quarter == 4 ? $end_year : $start_year;

	$expected_date = $start_year . '-' . $month . '-02';

	$start = date('Y-m-01 00:00:00', strtotime($expected_date));

	$end = date('Y-m-t 23:59:59', strtotime($expected_date));

	$return = array();
	$return['start'] = $start;
	$return['end'] = $end;
	return $return;
}

function numCommaFormat($number, $decimals = 0)
{

	// $number = 555;
	// $decimals=0;
	// $number = 555.000;
	// $number = 555.123456;

	if (strpos($number, '.') != null) {
		$decimalNumbers = substr($number, strpos($number, '.'));
		$decimalNumbers = substr($decimalNumbers, 1, $decimals);
	} else {
		$decimalNumbers = 0;
		for ($i = 2; $i <= $decimals; $i++) {
			$decimalNumbers = $decimalNumbers . '0';
		}
	}
	// return $decimalNumbers;



	$number = (int) $number;
	// reverse
	$number = strrev($number);

	$n = '';
	$stringlength = strlen($number);

	for ($i = 0; $i < $stringlength; $i++) {
		if ($i % 2 == 0 && $i != $stringlength - 1 && $i > 1) {
			$n = $n . $number[$i] . ',';
		} else {
			$n = $n . $number[$i];
		}
	}

	$number = $n;
	// reverse
	$number = strrev($number);

	($decimals != 0) ? $number = $number . '.' . $decimalNumbers : $number;

	return $number;
}
