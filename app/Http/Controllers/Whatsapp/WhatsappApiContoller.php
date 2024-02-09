<?php

namespace App\Http\Controllers\Whatsapp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class WhatsappApiContoller extends Controller
{
    public function getMessageTemplate(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://live-server-103607.wati.io/api/v1/getMessageTemplates',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzM2E2MWYyZC1hZmIxLTQyMGYtYWVjZC02ODcwZjRlYzMwODYiLCJ1bmlxdWVfbmFtZSI6ImFua2l0LmluMTE4NEBnbWFpbC5jb20iLCJuYW1laWQiOiJhbmtpdC5pbjExODRAZ21haWwuY29tIiwiZW1haWwiOiJhbmtpdC5pbjExODRAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDMvMTQvMjAyMyAwNToyODozNCIsImRiX25hbWUiOiIxMDM2MDciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.jY183d74h21Vp__STwlNIocxRLLceRSAtsljly_fS1I',
                'Cookie: affinity=1678877442.691.200912.393887|60582e1a1417c00ce6f9b2b83948e1d1'
            ),
        ));

        $templateList = curl_exec($curl);
        curl_close($curl);

        $templateList = json_decode($templateList);

        if ($templateList->result == 'success') {

            $templatedata = $templateList->messageTemplates;

            $templateselectdata = array();
            foreach ($templatedata as $value) {
                if ($value->status == 'APPROVED') {
                    $templateselectdata[] = array("id" => $value->elementName, "text" => $value->elementName);
                }
            }

            $response = array();
            $response = successRes("Template List Get Successfull");
            $response['results'] = $templateselectdata;
            $response['pagination']['more'] = true;
        } else {
            $response = errorRes("Please Contact To Admin");
        }


       
        return response()->json($response)->header('Content-Type', 'application/json');
    }
    // public function getplanet(Request $request)
    // {
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://auth.qa-tax.planetpayment.ae/auth/realms/planet/protocol/openid-connect/token',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => 'client_id=2ae6f4c8-07fb-497d-adff-1bcfa0da6773&client_secret=ieexuMNDD2HOYPtTNXya9STsXEErpTxt&grant_type=client_credentials',
    //         CURLOPT_HTTPHEADER => array(
    //             'Content-Type: application/x-www-form-urlencoded'
    //         ),
    //     )
    //     );

    //     $response = curl_exec($curl);

    //     curl_close($curl);
       
    //     return response()->json($response)->header('Content-Type', 'application/json');
    // }

    public function sendTemplateMessage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'q_whatsapp_massage_mobileno' => ['required'],
            'q_whatsapp_massage_template' => ['required'],
            'q_broadcast_name' => ['required'],
        ]);

        if ($validator->fails()) {

            $response = array();
            $response['status'] = 0;
            $response['msg'] = "The request could not be understood by the server due to malformed syntax";
            $response['statuscode'] = 400;
            $response['data'] = $validator->errors();

            return response()->json($response)->header('Content-Type', 'application/json');
        } else {
            $curl = curl_init();

            // if (isset($request->q_whatsapp_massage_username)) {
            //     $postfield = array(
            //         "template_name" => $request->q_whatsapp_massage_template,
            //         "broadcast_name" => $request->q_whatsapp_massage_name,
            //         "parameters" => [array(
            //             "name" => "name",
            //             "value" => $request->q_whatsapp_massage_name
            //         ), array(
            //             "name" => "username",
            //             "value" => $request->q_whatsapp_massage_username
            //         ), array(
            //             "name" => "password",
            //             "value" => $request->q_whatsapp_massage_password
            //         )]
            //     );
            // } else {
            //     $postfield = array(
            //         "template_name" => $request->q_whatsapp_massage_template,
            //         "broadcast_name" => $request->q_whatsapp_massage_name,
            //         "parameters" => [array(
            //             "name" => "name",
            //             "value" => $request->q_whatsapp_massage_name
            //         )]
            //     );
            // }
            if (isset($request->q_whatsapp_massage_parameters)) {
                $postfield = array(
                    "template_name" => $request->q_whatsapp_massage_template,
                    "broadcast_name" => $request->q_broadcast_name,
                    "parameters" => $request->q_whatsapp_massage_parameters
                );
            } else {
                $postfield = array(
                    "template_name" => $request->q_whatsapp_massage_template,
                    "broadcast_name" => $request->q_broadcast_name,
                    "parameters" => [
                        array(
                            "name" => "name",
                            "value" => $request->q_broadcast_name
                        )
                    ]
                );
            }
            $mobileNO = $request->q_whatsapp_massage_mobileno;
            $configrationForNotify = configrationForNotify();
            if (Config::get('app.env') == "local") { // SEND MAIL
                $mobileNO = $configrationForNotify['test_phone_number'];
            }
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://live-server-103607.wati.io/api/v1/sendTemplateMessage?whatsappNumber=' . $mobileNO,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($postfield),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzM2E2MWYyZC1hZmIxLTQyMGYtYWVjZC02ODcwZjRlYzMwODYiLCJ1bmlxdWVfbmFtZSI6ImFua2l0LmluMTE4NEBnbWFpbC5jb20iLCJuYW1laWQiOiJhbmtpdC5pbjExODRAZ21haWwuY29tIiwiZW1haWwiOiJhbmtpdC5pbjExODRAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDMvMTQvMjAyMyAwNToyODozNCIsImRiX25hbWUiOiIxMDM2MDciLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.jY183d74h21Vp__STwlNIocxRLLceRSAtsljly_fS1I',
                    'Content-Type: application/json',
                    'Cookie: affinity=1678941179.214.196049.405042|60582e1a1417c00ce6f9b2b83948e1d1'
                ),
            )
            );

            $res_send_message = curl_exec($curl);

            curl_close($curl);
            $res_send_message = json_decode($res_send_message);
            ;

            if ($res_send_message->result == true) {

                $response = successRes("Whatsapp Message Sent Successfull");
            } else {
                $response = errorRes("Please Contact To Admin");
                $response['data'] = $res_send_message->info;
            }
        }
        return response()->json($response)->header('Content-Type', 'application/json');
    }
}