<?php

use App\Http\Controllers\CRMHelpDocumentController;
use App\Http\Controllers\CRMUserHelpDocumentController;
use App\Http\Controllers\CRMUserLogController;
use App\Http\Controllers\CRM\Contact\LeadAccountContactController;
use App\Http\Controllers\CRM\Accounts\LeadAccountController;
use App\Http\Controllers\CRM\Lead\LeadContactController;
use App\Http\Controllers\CRM\SettingController as CRMSettingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\DebugLogController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterLocationCityController;
use App\Http\Controllers\MasterLocationCountryController;
use App\Http\Controllers\MasterLocationStateController;
use App\Http\Controllers\Service\MasterServiceHierarchyController;
use App\Http\Controllers\UsersAdminController;
use App\Http\Controllers\Service\UsersServiceExecutiveController;

use App\Http\Controllers\UsersCompanyAdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersUpdateController;
use App\Http\Controllers\VersionUpdateController;
use App\Http\Controllers\Whatsapp\WhatsappApiContoller;
use App\Http\Controllers\Ai\AiChatContoller;
use App\Http\Controllers\UserActionDetail\UserMeetingController;
use App\Http\Controllers\UserActionDetail\UserCallController;
use App\Http\Controllers\UserActionDetail\UserTaskController;
use App\Http\Controllers\UserActionDetail\UserNoteController;
use App\Http\Controllers\UserActionDetail\UserFileController;
use App\Http\Controllers\UserActionDetail\UserContactController;
use App\Http\Controllers\UserActionDetail\UserAllDetailController;
use App\Http\Controllers\UserActionDetail\CommanUserDetailController;
use App\Http\Controllers\MasterSearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Quotation\QuotationMasterController;
use App\Http\Controllers\CRM\Lead\LeadTaskController;
use App\Http\Controllers\CRM\Lead\LeadMeetingController;
use App\Http\Controllers\CRM\Lead\LeadCallController;
use App\Http\Controllers\CRM\Lead\LeadController;
use App\Http\Controllers\Request\RequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//Login
Route::get('/', [LoginController::class, "index"])->name('login');
Route::get('/login-with-otp', [LoginController::class, "loginWithOTP"])->name('login.otp');
Route::post('login-process', [LoginController::class, "loginProcess"])->name('login.process');
Route::post('login-otp-process', [LoginController::class, "loginWithOTPProcess"])->name('login.otp.process');
Route::get('logout', [LoginController::class, "logout"])->name('logout');
Route::get('forgot-password', [ForgotPasswordController::class, "index"])->name('forgot.password');
Route::post('resed-passswrod-link', [ForgotPasswordController::class, "resetPasswordLink"])->name('forgot.password.reset');
Route::get('reset-passswrod/{token}', [ForgotPasswordController::class, "resetPassword"])->name('reset.password');
Route::post('reset-passswrod-process', [ForgotPasswordController::class, "resetPasswordProcess"])->name('reset.password.process');

/// CRON
Route::get('ai-chat', [AiChatContoller::class, "getResponse"])->name('ai.chat');
Route::get('migrate', function () {
	$msg = Artisan::call('migrate');
	return 'Database migration : ' . $msg;
});

Route::get('/computer-id', [LoginController::class, "getComputerId"]);
/// END CRON

//Route::get('/migration-process', [MigrationProcessController::class, "index"]);

Route::group(["middleware" => "auth"], function () {

	// Route::get('test-pdf', [InvoiceManagementController::class, "test"]);

	Route::get('/version-update', [VersionUpdateController::class, "index"]);

	/// START GENERAL FUNCTIONS

	Route::get('/search-country', [GeneralController::class, "searchCountry"])->name('search.country');
	Route::get('/search-city', [GeneralController::class, "searchCity"])->name('search.city');
	Route::get('/search-city-state-country', [GeneralController::class, "searchCityStateCountry"])->name('search.city.state.country');
	Route::get('/search-state-from-country', [GeneralController::class, "searchStateFromCountry"])->name('search.state.from.country');
	Route::get('/search-city-from-state', [GeneralController::class, "searchCityFromState"])->name('search.city.from.state');
	Route::get('/search-courier', [GeneralController::class, "searchCourier"])->name('search.courier');
	Route::get('/auto-notification-scheduler', [GeneralController::class, "notificationScheduler"])->name('auto.notification.scheduler');

	/// END GENERAL FUNCTIONS

	Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard');

	Route::get('/profile', [DashboardController::class, "profile"])->name('profile');
	Route::get('/change-password', [DashboardController::class, "changePassword"])->name('changepassword');
	Route::get('/change-password-otp', [DashboardController::class, "sendOTPForChangePassword"])->name('changepassword.otp');
	Route::post('/do-change-password', [DashboardController::class, "doChangePassword"])->name('do.changepassword');
	Route::get('/dashboard-search-channel-partner', [DashboardController::class, "searchChannelPartner"])->name('dashboard.search.channel.partner');
	Route::get('/dashboard-search-sale-user', [DashboardController::class, "searchUser"])->name('dashboard.search.sale.user');

	/// START USER GENERAL FUNCTIONS

	Route::get('/users-search-state', [UsersController::class, "searchState"])->name('users.search.state');
	Route::get('/users-search-city', [UsersController::class, "searchCity"])->name('users.search.city');
	Route::get('/users-search-company', [UsersController::class, "searchCompany"])->name('users.search.company');
	Route::get('/users-search-saleperson-type', [UsersController::class, "searchSalePersonType"])->name('users.search.saleperson.type');
	Route::get('/users-search-purchaseperson-type', [UsersController::class, "searchPurchasePersonType"])->name('users.search.purcheperson.type');
	Route::get('/users-state-cities', [UsersController::class, "stateCities"])->name('users.state.cities');
	Route::get('/users-reporting-manager-sales', [UsersController::class, "salesReportingManager"])->name('users.reporting.manager');
	Route::get('/users-reporting-manager-purchase', [UsersController::class, "purchaseReportingManager"])->name('users.reporting.manager.purchase');
	Route::get('/users-search-state-cities', [UsersController::class, "searchStateCities"])->name('users.search.state.cities');
	Route::post('/user-phone-number-check', [UsersController::class, "checkUserPhoneNumberAndEmail"])->name('user.phone.number.check');

	// AXONE WORK START

	Route::get('/users-search-service-executive-type', [UsersController::class, "searchServiceExecutiveType"])->name('users.search.service.executive.type');
	Route::get('/users-search-service-executive-reporting-manager', [UsersController::class, "searchServiceExecutiveReportingManager"])->name('users.search.service.executive.reporting.manager');

	// AXONE WORK END

	Route::post('/users-save', [UsersController::class, "save"])->name('users.save');
	Route::get('/users-detail', [UsersController::class, "detail"])->name('users.detail');

	/// END USER GENERAL FUNCTIONS

	/// START USERS - ADMIN

	Route::get('/users-admin', [UsersAdminController::class, "index"])->name('users.admin');
	Route::post('/users-admin-ajax', [UsersAdminController::class, "ajax"])->name('users.admin.ajax');
	Route::get('/users-admin-export', [UsersAdminController::class, "export"])->name('users.admin.export');

	/// END USERS - ADMIN

	/// START USERS - COMPANY ADMIN

	Route::get('/users-company-admin', [UsersCompanyAdminController::class, "index"])->name('users.company.admin');
	Route::post('/users-company-admin-ajax', [UsersCompanyAdminController::class, "ajax"])->name('users.company.admin.ajax');
	Route::get('/users-company-admin-export', [UsersCompanyAdminController::class, "export"])->name('users.company.admin.export');

	/// END USERS - COMPANY ADMIN

	Route::get('/get-user-update', [UsersUpdateController::class, "detail"])->name('users.update.detail');
	Route::post('/get-user-save-update', [UsersUpdateController::class, "save"])->name('users.update.save');
	Route::get('/get-user-update-seen', [UsersUpdateController::class, "updateSeen"])->name('users.update.seen');

	/// USER ACTION ROUTES START

	Route::get('/user-action-search-contact-tag', [UserContactController::class, "searchTag"])->name('user.action.search.contact.tag');
	Route::post('/user-action-contact-save', [UserContactController::class, "save"])->name('user.action.contact.save');
	Route::get('/user-action-contact-detail', [UserContactController::class, "detail"])->name('user.action.contact.detail');

	Route::post('/user-action-file-save', [UserFileController::class, "save"])->name('user.action.file.save');
	Route::get('/user-action-file-delete', [UserFileController::class, "delete"])->name('user.action.file.delete');

	Route::post('/user-action-update-save', [UserNoteController::class, "save"])->name('user.action.update.save');

	Route::get('/user-action-search-call-type', [UserCallController::class, "searchCallType"])->name('user.action.search.call.type');
	Route::get('/user-action-search-contact', [UserCallController::class, "searchContact"])->name('user.action.search.contact');
	Route::post('/user-action-call-save', [UserCallController::class, "save"])->name('user.action.call.save');
	Route::get('/user-action-call-detail', [UserCallController::class, "detail"])->name('user.action.call.detail');
	Route::get('/user-action-search-call-outcome-type', [UserCallController::class, "searchCallOutcomeType"])->name('user.action.search.call.outcome.type');

	Route::get('/user-action-search-task-assign-to', [UserTaskController::class, "searchAssignedTo"])->name('user.action.search.task.assign');
	Route::post('/user-action-task-save', [UserTaskController::class, "save"])->name('user.action.task.save');
	Route::get('/user-action-task-detail', [UserTaskController::class, "detail"])->name('user.action.task.detail');
	Route::get('/user-action-search-task-outcome-type', [UserTaskController::class, "searchTaskOutcomeType"])->name('user.action.search.task.outcome.type');

	Route::get('/user-action-search-meeting-title', [UserMeetingController::class, "searchTitle"])->name('user.action.search.meeting.title');
	Route::get('/user-action-search-meeting-type', [UserMeetingController::class, "searchMeetingType"])->name('user.action.search.meeting.type');
	Route::get('/user-action-search-meeting-participants', [UserMeetingController::class, "searchParticipants"])->name('user.action.search.meeting.participants');
	Route::post('/user-action-meeting-save', [UserMeetingController::class, "save"])->name('user.action.meeting.save');
	Route::get('/user-action-meeting-detail', [UserMeetingController::class, "detail"])->name('user.action.meeting.detail');
	Route::get('/user-action-search-meeting-outcome-type', [UserMeetingController::class, "searchMeetingOutcomeType"])->name('user.action.search.meeting.outcome.type');

	Route::get('/user-action-open-action-all', [UserAllDetailController::class, "allOpenAction"])->name('user.action.open.action.all');
	Route::get('/user-action-close-action-all', [UserAllDetailController::class, "allCloseAction"])->name('user.action.close.action.all');
	Route::get('/user-action-contact-all', [UserAllDetailController::class, "allContact"])->name('user.action.contact.all');
	Route::get('/user-action-files-all', [UserAllDetailController::class, "allFiles"])->name('user.action.files.all');
	Route::get('/user-action-notes-all', [UserAllDetailController::class, "allUpdates"])->name('user.action.notes.all');

	Route::get('/search-reminder-time-slot', [CommanUserDetailController::class, "searchReminderTimeSlot"])->name('search.reminder.time.slot');
	Route::get('/search-user-tag', [CommanUserDetailController::class, "searchUserTag"])->name('search.user.tag');
	Route::POST('/save-user-detail', [CommanUserDetailController::class, "saveUserDetail"])->name('save.user.detail');
	Route::post('/user-view-lead-data', [CommanUserDetailController::class, "viewLeadData"])->name('user.view.lead.data');
	Route::post('/user-view-deal-data', [CommanUserDetailController::class, "viewDealData"])->name('user.view.deal.data');
	Route::post('/user-status-change', [CommanUserDetailController::class, "userStatusChange"])->name('user.status.change');

	/// USER ACTION ROUTES END

	/// START DEBUG LOG

	Route::get('/debug-log', [DebugLogController::class, "index"])->name('debug.log');
	Route::post('/debug-log-ajax', [DebugLogController::class, "ajax"])->name('debug.log.ajax');

	/// END DEBUG LOG

	/// START Country List

	Route::get('/master-location-country', [MasterLocationCountryController::class, "index"])->name('countrylist');
	Route::post('/master-location-country-ajax', [MasterLocationCountryController::class, "ajax"])->name('countrylist.ajax');

	/// END Country List

	/// START State List

	Route::get('/master-location-state', [MasterLocationStateController::class, "index"])->name('statelist');
	Route::post('/master-location-state-ajax', [MasterLocationStateController::class, "ajax"])->name('statelist.ajax');

	/// END State List

	/// START CITY LIST

	Route::get('/master-location-city', [MasterLocationCityController::class, "index"])->name('citylist');
	Route::get('/master-location-city-search-state', [MasterLocationCityController::class, "searchState"])->name('citylist.search.state');
	Route::post('/master-location-city-ajax', [MasterLocationCityController::class, "ajax"])->name('citylist.ajax');
	Route::get('/master-location-city-search-country', [MasterLocationCityController::class, "searchCountry"])->name('citylist.search.country');
	Route::post('/master-location-city-save', [MasterLocationCityController::class, "save"])->name('citylist.save');
	Route::get('/master-location-city-detail', [MasterLocationCityController::class, "detail"])->name('citylist.detail');

	/// END CITY LIST

	/// START CRM

	/// START HELP DOCUMENT

	Route::get('/crm-help-document', [CRMHelpDocumentController::class, "index"])->name('crm.help.document');
	Route::post('/crm-help-document-ajax', [CRMHelpDocumentController::class, "ajax"])->name('crm.help.document.ajax');
	Route::get('/crm-help-document-detail', [CRMHelpDocumentController::class, "detail"])->name('crm.help.document.detail');
	Route::post('/crm-help-document-save', [CRMHelpDocumentController::class, "save"])->name('crm.help.document.save');

	///  END HELP DOCUMENT

	/// START ARCHITECT HELP DOCUMENT

	Route::get('/crm-user-help-document', [CRMUserHelpDocumentController::class, "index"])->name('architect.help.document');

	/// END ARCHITECT HELP DOCUMENT

	/// START ARCHITECT LOG

	Route::get('/crm-user-log', [CRMUserLogController::class, "index"])->name('architect.log');
	Route::post('/crm-user-log-ajax', [CRMUserLogController::class, "ajax"])->name('architect.log.ajax');

	/// END ARCHITECT LOG

	Route::get('/crm-account-table', [LeadAccountController::class, "index"])->name('crm.account.table');
	Route::post('/crm-account-table-ajax', [LeadAccountController::class, "ajax"])->name('crm.account.table.ajax');
	Route::get('/crm-account-detail', [LeadAccountController::class, "detail"])->name('crm.account.detail');
	Route::get('/crm-account-list', [LeadAccountController::class, "getList"])->name('crm.account.list');
	Route::get('/crm-account-detail-view', [LeadAccountController::class, "getDeatailView"])->name('crm.lead.account.detail.view');
	Route::post('/crm-account-list-ajax', [LeadAccountController::class, "getListAjax"])->name('crm.lead.account.list.ajax');

	/// CRM LEAD

	Route::get('/crm-lead-account-contact-table', [LeadAccountContactController::class, "index"])->name('crm.lead.account.contact.table');
	Route::get('/crm-account-contact-detail-view', [LeadAccountContactController::class, "getDeatailView"])->name('crm.lead.account.contact.detail.view');
	Route::get('/crm-account-contact-list', [LeadAccountContactController::class, "getList"])->name('crm.lead.account.contact.list');
	Route::get('/crm-lead-account-contact-table-detail', [LeadAccountContactController::class, "table"])->name('crm.lead.account.contact.table.detail');
	Route::post('/crm-lead-account-contact-table-ajax', [LeadAccountContactController::class, "ajax"])->name('crm.lead.account.contact.table.ajax');

	Route::get('/crm-lead-search-contact-tag', [LeadContactController::class, "searchTag"])->name('crm.lead.search.contact.tag');
	Route::post('/crm-lead-contact-save', [LeadContactController::class, "save"])->name('crm.lead.contact.save');
	Route::get('/crm-lead-contact-detail', [LeadContactController::class, "detail"])->name('crm.lead.contact.detail');

	// 2023-03-27

	Route::post('/crm-account-contact-save', [LeadAccountContactController::class, "save"])->name('crm.account.contact.save');
	Route::get('/crm-account-contact-detail', [LeadAccountContactController::class, "detail"])->name('crm.account.contact.detail');

	// START USERS - SERVICE EXECUTIVE

	Route::get('/users-service-executive', [UsersServiceExecutiveController::class, "index"])->name('users.service.executive');
	Route::post('/users-service-executive-ajax', [UsersServiceExecutiveController::class, "ajax"])->name('users.service.executive.ajax');
	Route::get('/users-service-executive-export', [UsersServiceExecutiveController::class, "export"])->name('users.service.executive.export');

	// END  USERS -  SERVICE EXECUTIVE

	Route::get('/crm-setting', [CRMSettingController::class, "index"])->name('crm.setting');
	Route::post('/crm-setting-stage-of-site-ajax', [CRMSettingController::class, "ajaxStageOfSite"])->name('crm.setting.stage.site');
	Route::get('/crm-setting-stage-of-site-detail', [CRMSettingController::class, "deailStageOfSite"])->name('crm.setting.stage.site.detail');
	Route::post('/crm-setting-stage-of-site-save', [CRMSettingController::class, "saveStageOfSite"])->name('crm.setting.stage.site.save');

	Route::post('/crm-setting-site-type-ajax', [CRMSettingController::class, "ajaxSiteType"])->name('crm.setting.site.type');
	Route::get('/crm-setting-site-type-detail', [CRMSettingController::class, "deailSiteType"])->name('crm.setting.site.type.detail');
	Route::post('/crm-setting-site-type-save', [CRMSettingController::class, "saveSiteType"])->name('crm.setting.site.type.save');

	Route::post('/crm-setting-bhk-ajax', [CRMSettingController::class, "ajaxBHK"])->name('crm.setting.bhk');
	Route::get('/crm-setting-bhk-detail', [CRMSettingController::class, "deailBHK"])->name('crm.setting.bhk.detail');
	Route::post('/crm-setting-bhk-save', [CRMSettingController::class, "saveBHK"])->name('crm.setting.bhk.save');

	Route::post('/crm-setting-want-to-cover-ajax', [CRMSettingController::class, "ajaxWantToCover"])->name('crm.setting.cover');
	Route::get('/crm-setting-want-to-cover-detail', [CRMSettingController::class, "deailWantToCover"])->name('crm.setting.cover.detail');
	Route::post('/crm-setting-want-to-cover-save', [CRMSettingController::class, "saveWantToCover"])->name('crm.setting.cover.save');

	Route::post('/crm-setting-source-type-ajax', [CRMSettingController::class, "ajaxSouceType"])->name('crm.setting.source.type');
	Route::get('/crm-setting-source-type-detail', [CRMSettingController::class, "deailSouceType"])->name('crm.setting.source.type.detail');
	Route::post('/crm-setting-source-type-save', [CRMSettingController::class, "saveSouceType"])->name('crm.setting.source.type.save');

	Route::get('/crm-setting-source-search', [CRMSettingController::class, "searchSourceType"])->name('crm.setting.source.type.search');
	Route::post('/crm-setting-source-ajax', [CRMSettingController::class, "ajaxSource"])->name('crm.setting.source');
	Route::get('/crm-setting-source-detail', [CRMSettingController::class, "deailSource"])->name('crm.setting.source.detail');
	Route::post('/crm-setting-source-save', [CRMSettingController::class, "saveSource"])->name('crm.setting.source.save');

	Route::post('/crm-setting-competitors-ajax', [CRMSettingController::class, "ajaxCompetitors"])->name('crm.setting.competitors');
	Route::get('/crm-setting-competitors-detail', [CRMSettingController::class, "deailCompetitors"])->name('crm.setting.competitors.detail');
	Route::post('/crm-setting-competitors-save', [CRMSettingController::class, "saveCompetitors"])->name('crm.setting.competitors.save');

	Route::get('/crm-setting-status-search', [CRMSettingController::class, "searchStatus"])->name('crm.setting.status.search');
	Route::post('/crm-setting-sub-status-ajax', [CRMSettingController::class, "ajaxSubStatus"])->name('crm.setting.sub.status.ajax');
	Route::get('/crm-setting-sub-status-detail', [CRMSettingController::class, "deailSubStatus"])->name('crm.setting.sub.status.detail');
	Route::post('/crm-setting-sub-status-save', [CRMSettingController::class, "saveSubStatus"])->name('crm.setting.sub.status.save');

	Route::post('/crm-setting-contact-tag-ajax', [CRMSettingController::class, "ajaxContactTag"])->name('crm.setting.contact.tag');
	Route::get('/crm-setting-contact-tag-detail', [CRMSettingController::class, "deailContactTag"])->name('crm.setting.contact.tag.detail');
	Route::post('/crm-setting-contact-tag-save', [CRMSettingController::class, "saveContactTag"])->name('crm.setting.contact.tag.save');

	Route::post('/crm-setting-file-tag-ajax', [CRMSettingController::class, "ajaxFileTag"])->name('crm.setting.file.tag');
	Route::get('/crm-setting-file-tag-detail', [CRMSettingController::class, "deailFileTag"])->name('crm.setting.file.tag.detail');
	Route::post('/crm-setting-file-tag-save', [CRMSettingController::class, "saveFileTag"])->name('crm.setting.file.tag.save');

	Route::post('/crm-setting-meeting-title-ajax', [CRMSettingController::class, "ajaxMeetingTitle"])->name('crm.setting.meeting.title');
	Route::get('/crm-setting-meeting-title-detail', [CRMSettingController::class, "deailMeetingTitle"])->name('crm.setting.meeting.title.detail');
	Route::post('/crm-setting-meeting-title-save', [CRMSettingController::class, "saveMeetingTitle"])->name('crm.setting.meeting.title.save');

	Route::post('/crm-setting-schedule-type-ajax', [CRMSettingController::class, "ajaxScheduleType"])->name('crm.setting.schedule.type');
	Route::get('/crm-setting-schedule-type-detail', [CRMSettingController::class, "deailScheduleType"])->name('crm.setting.schedule.type.detail');
	Route::post('/crm-setting-schedule-type-save', [CRMSettingController::class, "saveScheduleType"])->name('crm.setting.schedule.type.save');

	Route::post('/crm-setting-schedule-type-meeting-ajax', [CRMSettingController::class, "ajaxScheduleMeetingType"])->name('crm.setting.schedule.meeting.type');
	Route::get('/crm-setting-schedule-type-meeting-detail', [CRMSettingController::class, "deailScheduleMeetingType"])->name('crm.setting.schedule.type.meeting.detail');
	Route::post('/crm-setting-schedule-type-meeting-save', [CRMSettingController::class, "saveScheduleMeetingType"])->name('crm.setting.schedule.type.meeting.save');


	Route::post('/crm-setting-call-outcome-type-ajax', [CRMSettingController::class, "ajaxCallOutcomeType"])->name('crm.setting.call.outcome.type.ajax');
	Route::post('/crm-setting-call-outcome-type-detail', [CRMSettingController::class, "saveCallOutcomeType"])->name('crm.setting.call.outcome.type.save');
	Route::get('/crm-setting-call-outcome-type-save', [CRMSettingController::class, "detailCallOutcomeType"])->name('crm.setting.call.outcome.type.detail');

	Route::post('/crm-setting-meeting-outcome-type-ajax', [CRMSettingController::class, "ajaxMeetingOutcomeType"])->name('crm.setting.meeting.outcome.type.ajax');
	Route::post('/crm-setting-meeting-outcome-type-detail', [CRMSettingController::class, "saveMeetingOutcomeType"])->name('crm.setting.meeting.outcome.type.save');
	Route::get('/crm-setting-meeting-outcome-type-save', [CRMSettingController::class, "detailMeetingOutcomeType"])->name('crm.setting.meeting.outcome.type.detail');

	Route::post('/crm-setting-task-outcome-type-ajax', [CRMSettingController::class, "ajaxTaskOutcomeType"])->name('crm.setting.task.outcome.type.ajax');
	Route::post('/crm-setting-task-outcome-type-detail', [CRMSettingController::class, "saveTaskOutcomeType"])->name('crm.setting.task.outcome.type.save');
	Route::get('/crm-setting-task-outcome-type-save', [CRMSettingController::class, "detailTaskOutcomeType"])->name('crm.setting.task.outcome.type.detail');

	Route::post('/crm-setting-lead-deal-tag-master-save', [CRMSettingController::class, "saveLeadDealTag"])->name('crm.setting.lead.deal.tag.master.save');
	Route::post('/crm-setting-lead-deal-tag-master-ajax', [CRMSettingController::class, "ajaxLeadDealTag"])->name('crm.setting.lead.deal.tag.master.ajax');
	Route::get('/crm-setting-lead-deal-tag-master-detail', [CRMSettingController::class, "detailLeadDealTag"])->name('crm.setting.lead.deal.tag.master.detail');

	Route::post('/crm-setting-user-tag-master-save', [CRMSettingController::class, "saveUserTag"])->name('crm.setting.user.tag.master.save');
	Route::post('/crm-setting-user-tag-master-ajax', [CRMSettingController::class, "ajaxUserTag"])->name('crm.setting.user.tag.master.ajax');
	Route::get('/crm-setting-user-tag-master-detail', [CRMSettingController::class, "detailUserTag"])->name('crm.setting.user.tag.master.detail');

	Route::post('/crm-setting-call-additional-info-ajax', [CRMSettingController::class, "ajaxCallAdditionalInfo"])->name('crm.setting.call.additional.info.ajax');
	Route::post('/crm-setting-call-additional-info-save', [CRMSettingController::class, "saveCallAdditionalInfo"])->name('crm.setting.call.additional.info.save');
	Route::get('/crm-setting-call-additional-info-detail', [CRMSettingController::class, "detailCallAdditionalInfo"])->name('crm.setting.call.additional.info.detail');

	/// CRM LEAD

	/////////////////////////////////////////////////////// START AXONE DEVELOPMENT ///////////////////////////////////////////////////////

	// START SERVICE HIERARCHY
	Route::get('/service-hierarchy', [MasterServiceHierarchyController::class, "index"])->name('service.hierarchy');
	Route::get('/service-hierarchy-search', [MasterServiceHierarchyController::class, "search"])->name('service.hierarchy.search');
	Route::post('/service-hierarchy-ajax', [MasterServiceHierarchyController::class, "ajax"])->name('service.hierarchy.ajax');
	Route::post('/service-hierarchy-save', [MasterServiceHierarchyController::class, "saveProcess"])->name('service.hierarchy.save');

	Route::get('/service-hierarchy-detail', [MasterServiceHierarchyController::class, "detail"])->name('service.hierarchy.detail');
	Route::get('/service-hierarchy-delete', [MasterServiceHierarchyController::class, "delete"])->name('service.hierarchy.delete');
	// END  SERVICE HIERARCHY

	// START WHATSAPP API
	Route::get('/search-whatsapp-template', [WhatsappApiContoller::class, "getMessageTemplate"])->name('search.whatsapp.template');
	Route::post('/send-whatsapp-template-message', [WhatsappApiContoller::class, "sendTemplateMessage"])->name('send.whatsapp.template.message');
	// END WHATSAPP API

	// Route::post('/master-search-ajax', [MasterSearchController::class, "ajax"])->name('master.search.ajax');
	Route::post('/master-search-sales-user-ajax', [MasterSearchController::class, "SalesUserAjax"])->name('master.search.sales.user.ajax');
	Route::post('/master-search-arc-ajax', [MasterSearchController::class, "ArchitectAjax"])->name('master.search.arc.ajax');
	Route::post('/master-search-ele-ajax', [MasterSearchController::class, "ElectricianAjax"])->name('master.search.ele.ajax');
	Route::post('/master-search-lead-ajax', [MasterSearchController::class, "LeadAjax"])->name('master.search.lead.ajax');
	Route::post('/master-search-deal-ajax', [MasterSearchController::class, "DealAjax"])->name('master.search.deal.ajax');

	/////////////////////////////////////////////////////// END AXONE DEVELOPMENT ///////////////////////////////////////////////////////


	Route::post('/crm-lead-task-save', [LeadTaskController::class, "save"])->name('crm.lead.task.save');
	Route::get('/crm-lead-task-detail', [LeadTaskController::class, "detail"])->name('crm.lead.task.detail');
	Route::get('/crm-lead-auto-task-detail', [LeadTaskController::class, 'getTaskDetail'])->name('crm.lead.auto.task.detail');
	Route::get('/crm-lead-search-task-assign-to', [LeadTaskController::class, "searchAssignedTo"])->name('crm.lead.search.task.assign');
	Route::get('/crm-lead-search-task-outcome-type', [LeadTaskController::class, "searchTaskOutcomeType"])->name('crm.lead.search.task.outcome.type');

	Route::post('/crm-lead-meeting-save', [LeadMeetingController::class, "save"])->name('crm.lead.meeting.save');
	Route::get('/crm-lead-meeting-detail', [LeadMeetingController::class, "detail"])->name('crm.lead.meeting.detail');
	Route::get('/crm-lead-search-meeting-title', [LeadMeetingController::class, "searchTitle"])->name('crm.lead.search.meeting.title');
	Route::post('/crm-lead-find-meeting-times', [LeadMeetingController::class, "findMeetingTimes"])->name('crm.lead.find.meeting.times');
	Route::get('/crm-lead-search-meeting-type', [LeadMeetingController::class, "searchMeetingType"])->name('crm.lead.search.meeting.type');
	Route::get('/crm-lead-search-meeting-participants', [LeadMeetingController::class, "searchParticipants"])->name('crm.lead.search.meeting.participants');
	Route::get('/crm-lead-search-meeting-outcome-type', [LeadMeetingController::class, "searchMeetingOutcomeType"])->name('crm.lead.search.meeting.outcome.type');

	Route::post('/crm-lead-call-save', [LeadCallController::class, "save"])->name('crm.lead.call.save');
	Route::get('/crm-lead-call-detail', [LeadCallController::class, "detail"])->name('crm.lead.call.detail');
	Route::get('/crm-lead-search-contact', [LeadCallController::class, "searchContact"])->name('crm.lead.search.contact');
	Route::get('/crm-lead-auto-call-detail', [LeadCallController::class, 'getCallDetail'])->name('crm.lead.auto.call.detail');
	Route::get('/crm-lead-search-call-type', [LeadCallController::class, "searchCallType"])->name('crm.lead.search.call.type');
	Route::get('/crm-lead-search-call-assign-to', [LeadCallController::class, "searchAssignedTo"])->name('crm.lead.search.call.assign');
	Route::get('/crm-lead-search-additional-info', [LeadCallController::class, "searchAdditionalInfo"])->name('crm.lead.search.additional.info');
	Route::get('/crm-lead-auto-task-and-call-list', [LeadCallController::class, 'getTaskAndCallList'])->name('crm.lead.auto.task.and.call.list');
	Route::get('/crm-lead-additional-info-detail', [LeadCallController::class, "getAdditionalInfoDetail"])->name('crm.lead.additional.info.detail');
	Route::get('/crm-lead-search-call-outcome-type', [LeadCallController::class, "searchCallOutcomeType"])->name('crm.lead.search.call.outcome.type');
	Route::get('/crm-lead-call-outcome-type-detail', [LeadCallController::class, "getCahelllOutcomeTypeDetail"])->name('crm.lead.call.outcome.type.detail');

	Route::get('/crm-lead-search-status-action', [LeadController::class, "searchStatusInAction"])->name('crm.lead.search.status.action');
	Route::get('/crm-lead-search-reminder-time-slot', [LeadController::class, "searchReminderTimeSlot"])->name('crm.lead.search.reminder.time.slot');

	Route::get('/quot-get-brand-list', [QuotationMasterController::class, "GetBrandList"])->name('quot.get.brand.list'); // new update
	Route::post('/quot-discount-approved-or-reject', [QuotationMasterController::class, "SaveDiscountApprovedOrReject"])->name('quot.discount.approved.or.reject'); // new update

	Route::get('/request-index', [RequestController::class, 'index'])->name('request.index');
	Route::get('/request-table', [RequestController::class, 'table'])->name('request.table');
	Route::post('/request-save', [RequestController::class, 'save'])->name('request.save');
	Route::post('/request-ajax', [RequestController::class, 'ajax'])->name('request.ajax');
	Route::get('/request-search-customer', [RequestController::class, 'searchCustomer'])->name('request.search.customer');
});
