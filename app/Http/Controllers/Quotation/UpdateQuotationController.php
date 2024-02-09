<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wltrn_QuotItemdetail;
use App\Models\WlmstItemSubgroup;
use App\Models\WlmstItem;
use Illuminate\Support\Facades\DB;

class UpdateQuotationController extends Controller
{

    function boardtable()
    {
        return view('quotation/master/quotation/updatequotation/board_table');
    }
    public function index(Request $request)
    {
        $data = array();
        $data['title'] = "Update Quotation Item";
        $columns = array(
            'wltrn_quot_itemdetails.room_name',
            'wltrn_quot_itemdetails.quot_id',
            'wltrn_quot_itemdetails.room_no',
            'wltrn_quot_itemdetails.isactiveroom',
        );


        $board_query = Wltrn_QuotItemdetail::query();
        $board_query->select($columns);
        $board_query->where('quot_id', $request->quotno);

        $room_data = $board_query->get();


        $room_data = json_decode(json_encode($room_data), true);
        $data['room_data'] = $room_data;
        $response = successRes('Quotation Details');
        $response['data'] = $room_data;
        return  $data['view'] = view('quotation/master/quotation/updatequotation/quotation_change_view', compact('data'))->render();

        //  return response()->json($response)->header('Content-Type', 'application/json');
    }

    public function boardDetil(Request $request)
    {
        $Quotation_no = $request->quot_id;
        $Room_no = $request->room_no;

        if ($Quotation_no !== null && $Room_no !== null) {

            $response = [];
            $columns = ['wltrn_quot_itemdetails.isactiveboard', 'wltrn_quot_itemdetails.board_image', 'wltrn_quot_itemdetails.quot_id', 'wltrn_quot_itemdetails.quotgroup_id', 'wltrn_quot_itemdetails.srno', 'wltrn_quot_itemdetails.isactiveboard', 'wltrn_quot_itemdetails.room_no', 'wltrn_quot_itemdetails.board_item_id', 'wltrn_quot_itemdetails.item_type', 'wltrn_quot_itemdetails.board_no', 'wltrn_quot_itemdetails.board_name'];

            $board_query = Wltrn_QuotItemdetail::query();
            $board_query->select($columns);
            $board_query->leftJoin('wlmst_items', 'wltrn_quot_itemdetails.board_item_id', '=', 'wlmst_items.id');
            $board_query->where('wltrn_quot_itemdetails.board_no', '!=', '0');
            $board_query->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id], ['wltrn_quot_itemdetails.room_no', $request->room_no]]);
            $board_query->orderBy('wltrn_quot_itemdetails.board_no', 'asc');
            $board_query->groupBy($columns);

            $boardlist = $board_query->get();
            $quot_array = [];
            foreach ($boardlist as $key => $board_value) {
                $quot_f_array = [];

                $board_range_query = Wltrn_QuotItemdetail::query();
                $board_range_query->select('wltrn_quot_itemdetails.board_range');
                $board_range_query->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id], ['wltrn_quot_itemdetails.room_no', $request->room_no], ['wltrn_quot_itemdetails.board_no', $board_value->board_no]]);
                $board_range_query = $board_range_query->first();

                if ($board_range_query->board_range != '' || $board_range_query->board_range != null) {
                    $Range_Subgroup = explode(',', $board_range_query->board_range);
                    $range_group = '';
                    $range_company = '';
                    for ($i = 0; $i < count($Range_Subgroup); $i++) {
                        $range_group .= WlmstItemSubgroup::find($Range_Subgroup[$i])->itemgroup_id . ',';
                        $range_company .= WlmstItemSubgroup::find($Range_Subgroup[$i])->company_id . ',';
                    }
                    $range_group_f = substr($range_group, 0, -1);
                    $range_company_f = explode(',', $range_company)[0];
                } else {
                    $range_group_f = ' ';
                    $range_company_f = ' ';
                }

                $quot_f_array = $board_value;
                if ($board_value->board_no == 0) {
                    $Board_Name = 'Room Addon';
                } else {
                    $Board_Name = $board_value->board_name;
                }
                $quot_f_array['board_name'] = $Board_Name; //Live
                $quot_f_array['image'] = getSpaceFilePath($board_value->board_image); //Live
                $quot_f_array['range_group'] = $range_group_f;
                $quot_f_array['range_company'] = $range_company_f;

                $board_items_column = ['wlmst_item_prices.id as priceid', 'wlmst_items.id as itemid', 'wlmst_items.itemname', 'wlmst_items.app_display_name', 'wlmst_item_prices.company_id', 'wlmst_companies.companyname', 'wlmst_items.itemcategory_id', 'wlmst_item_categories.itemcategoryname', 'wlmst_item_prices.itemgroup_id', 'wlmst_item_groups.itemgroupname', 'wlmst_item_prices.itemsubgroup_id', 'wlmst_item_subgroups.itemsubgroupname', 'wlmst_items.module', 'wltrn_quot_itemdetails.qty', 'wlmst_items.max_module', 'wlmst_items.is_special', 'wlmst_items.additional_remark', 'wltrn_quot_itemdetails.discper as discount', 'wlmst_items.remark', 'wlmst_item_prices.mrp', 'wlmst_item_prices.image', 'wlmst_item_prices.code', 'wlmst_item_prices.code AS product_code_name'];
                $board_items_qry = Wltrn_QuotItemdetail::query();
                $board_items_qry->select($board_items_column);
                $board_items_qry->leftJoin('wlmst_items', 'wltrn_quot_itemdetails.item_id', '=', 'wlmst_items.id');
                $board_items_qry->leftJoin('wlmst_item_prices', 'wltrn_quot_itemdetails.item_price_id', '=', 'wlmst_item_prices.id');
                $board_items_qry->leftJoin('wlmst_item_categories', 'wltrn_quot_itemdetails.itemcategory_id', '=', 'wlmst_item_categories.id');
                $board_items_qry->leftJoin('wlmst_companies', 'wltrn_quot_itemdetails.company_id', '=', 'wlmst_companies.id');
                $board_items_qry->leftJoin('wlmst_item_groups', 'wltrn_quot_itemdetails.itemgroup_id', '=', 'wlmst_item_groups.id');
                $board_items_qry->leftJoin('wlmst_item_subgroups', 'wltrn_quot_itemdetails.itemsubgroup_id', '=', 'wlmst_item_subgroups.id');
                $board_items_qry->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id], ['wltrn_quot_itemdetails.room_no', $request->room_no], ['wltrn_quot_itemdetails.board_no', $board_value->board_no]]);
                $range_group_new = [];
                $range_group = [];
                $item_name = '';
                foreach ($board_items_qry->get() as $key => $value) {
                    if ($value->image == null) {
                        $value['image'] = 'http://axoneerp.whitelion.in/assets/images/logo.svg';
                    } else {
                        $value['image'] = getSpaceFilePath($value->image);
                    }
                    $value['is_addons'] = $value->itemcategory_id == 6 ? 1 : 0;
                    if ($value->itemcategory_id == 6) {
                        $item_name .= $value->itemname . ',';
                    }
                    $range_group_new[$value->priceid] = $value;
                    $range_group = [];
                    array_push($range_group, $range_group_new);
                }
                $quot_f_array['itemname'] = rtrim($item_name, ',');
                if (!empty($range_group)) {
                    $quot_f_array['board_item'] = $range_group[0];
                } else {
                    $quot_f_array['board_item'] = 'null';
                }

                array_push($quot_array, $quot_f_array);
            }



            $data = $quot_array;
            $view = view('quotation/master/quotation/updatequotation/board_table', compact('data'))->render();
            $response = successRes('Board detail');
            $response['data'] = $data;
            $response['view'] = $view;
            return response()->json($response)->header('Content-Type', 'application/json');
        } else {

            $response = [];
            $columns = ['wltrn_quot_itemdetails.isactiveboard', 'wltrn_quot_itemdetails.board_image', 'wltrn_quot_itemdetails.quot_id', 'wltrn_quot_itemdetails.quotgroup_id', 'wltrn_quot_itemdetails.srno', 'wltrn_quot_itemdetails.isactiveboard', 'wltrn_quot_itemdetails.room_no', 'wltrn_quot_itemdetails.board_item_id', 'wltrn_quot_itemdetails.item_type', 'wltrn_quot_itemdetails.board_no', 'wltrn_quot_itemdetails.board_name'];

            $board_query = Wltrn_QuotItemdetail::query();
            $board_query->select($columns);
            $board_query->leftJoin('wlmst_items', 'wltrn_quot_itemdetails.board_item_id', '=', 'wlmst_items.id');
            $board_query->where('wltrn_quot_itemdetails.board_no', '!=', '0');
            $board_query->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id]]);
            $board_query->orderBy('wltrn_quot_itemdetails.board_no', 'asc');
            $board_query->groupBy($columns);

            $boardlist = $board_query->get();
            $quot_array = [];
            foreach ($boardlist as $key => $board_value) {
                $quot_f_array = [];

                $board_range_query = Wltrn_QuotItemdetail::query();
                $board_range_query->select('wltrn_quot_itemdetails.board_range');
                $board_range_query->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id], ['wltrn_quot_itemdetails.board_no', $board_value->board_no]]);
                $board_range_query = $board_range_query->first();

                if ($board_range_query->board_range != '' || $board_range_query->board_range != null) {
                    $Range_Subgroup = explode(',', $board_range_query->board_range);
                    $range_group = '';
                    $range_company = '';
                    for ($i = 0; $i < count($Range_Subgroup); $i++) {
                        $range_group .= WlmstItemSubgroup::find($Range_Subgroup[$i])->itemgroup_id . ',';
                        $range_company .= WlmstItemSubgroup::find($Range_Subgroup[$i])->company_id . ',';
                    }
                    $range_group_f = substr($range_group, 0, -1);
                    $range_company_f = explode(',', $range_company)[0];
                } else {
                    $range_group_f = ' ';
                    $range_company_f = ' ';
                }

                $quot_f_array = $board_value;
                if ($board_value->board_no == 0) {
                    $Board_Name = 'Room Addon';
                } else {
                    $Board_Name = $board_value->board_name;
                }
                $quot_f_array['board_name'] = $Board_Name; //Live
                $quot_f_array['image'] = getSpaceFilePath($board_value->board_image); //Live
                $quot_f_array['range_group'] = $range_group_f;
                $quot_f_array['range_company'] = $range_company_f;

                $board_items_column = ['wlmst_item_prices.id as priceid', 'wlmst_items.id as itemid', 'wlmst_items.itemname', 'wlmst_items.app_display_name', 'wlmst_item_prices.company_id', 'wlmst_companies.companyname', 'wlmst_items.itemcategory_id', 'wlmst_item_categories.itemcategoryname', 'wlmst_item_prices.itemgroup_id', 'wlmst_item_groups.itemgroupname', 'wlmst_item_prices.itemsubgroup_id', 'wlmst_item_subgroups.itemsubgroupname', 'wlmst_items.module', 'wltrn_quot_itemdetails.qty', 'wlmst_items.max_module', 'wlmst_items.is_special', 'wlmst_items.additional_remark', 'wltrn_quot_itemdetails.discper as discount', 'wlmst_items.remark', 'wlmst_item_prices.mrp', 'wlmst_item_prices.image', 'wlmst_item_prices.code', 'wlmst_item_prices.code AS product_code_name'];
                $board_items_qry = Wltrn_QuotItemdetail::query();
                $board_items_qry->select($board_items_column);
                $board_items_qry->leftJoin('wlmst_items', 'wltrn_quot_itemdetails.item_id', '=', 'wlmst_items.id');
                $board_items_qry->leftJoin('wlmst_item_prices', 'wltrn_quot_itemdetails.item_price_id', '=', 'wlmst_item_prices.id');
                $board_items_qry->leftJoin('wlmst_item_categories', 'wltrn_quot_itemdetails.itemcategory_id', '=', 'wlmst_item_categories.id');
                $board_items_qry->leftJoin('wlmst_companies', 'wltrn_quot_itemdetails.company_id', '=', 'wlmst_companies.id');
                $board_items_qry->leftJoin('wlmst_item_groups', 'wltrn_quot_itemdetails.itemgroup_id', '=', 'wlmst_item_groups.id');
                $board_items_qry->leftJoin('wlmst_item_subgroups', 'wltrn_quot_itemdetails.itemsubgroup_id', '=', 'wlmst_item_subgroups.id');
                $board_items_qry->where([['wltrn_quot_itemdetails.quot_id', $request->quot_id], ['wltrn_quot_itemdetails.board_no', $board_value->board_no]]);
                $range_group_new = [];
                $range_group = [];
                $item_name = '';
                foreach ($board_items_qry->get() as $key => $value) {
                    if ($value->image == null) {
                        $value['image'] = 'http://axoneerp.whitelion.in/assets/images/logo.svg';
                    } else {
                        $value['image'] = getSpaceFilePath($value->image);
                    }
                    $value['is_addons'] = $value->itemcategory_id == 6 ? 1 : 0;
                    if ($value->itemcategory_id == 6) {
                        $item_name .= $value->itemname . ',';
                    }
                    $range_group_new[$value->priceid] = $value;
                    $range_group = [];
                    array_push($range_group, $range_group_new);
                }
                $quot_f_array['itemname'] = rtrim($item_name, ',');
                if (!empty($range_group)) {
                    $quot_f_array['board_item'] = $range_group[0];
                } else {
                    $quot_f_array['board_item'] = 'null';
                }

                array_push($quot_array, $quot_f_array);
            }



            $data = $quot_array;
            $view = view('quotation/master/quotation/updatequotation/board_table', compact('data'))->render();
            $response = successRes('Board detail');
            $response['data'] = $data;
            $response['view'] = $view;
            return response()->json($response)->header('Content-Type', 'application/json');
        }
    }
}
