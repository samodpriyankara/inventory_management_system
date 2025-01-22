<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db = new DB();
$conn = $db->connect();
session_start();
//--------------------------------
$output = [];
$date = date('Y-m-d');
$sessionId = session_id();
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $login_time_stamp = strtotime(date('Y-m-d H:i:s'));
    $checkSystem = $conn->query("SELECT check_system FROM tbl_system_check");
    if ($checkRs = $checkSystem->fetch_array()) {
        $systemStatus = $checkRs[0];
        if ($systemStatus == 0) {
            $output["result"] = false;
            $output["msg"] = "System has been deactivated. please contact distributor.";
        } else {
            $rs = $conn->query("SELECT * FROM tbl_user tu INNER JOIN tbl_user_has_routes tuhr ON tu.id=tuhr.user_id INNER JOIN tbl_route tr ON tuhr.route_id=tr.route_id INNER JOIN tbl_distributor_has_tbl_user tdhu ON tdhu.user_id = tu.id WHERE username='$username' AND password='$password' /*AND tuhr.date='$date'*/");
            if ($row = $rs->fetch_array()) {
                $userId = $row[0];
                $loginStatus = $row[8];
                if ($loginStatus == 1) {
                    $output["result"] = false;
                    $output["msg"] = "You are still logged on please logout first.";
                    $output["login_time_stamp"] = $login_time_stamp;
                } else {
                    $statusChange = $conn->query("UPDATE tbl_user SET login_status=1 WHERE id='$userId'");
                    if ($statusChange) {
                        $output["result"] = true;
                        $output["userId"] = $userId;
                        $output["name"] = $row[1];
                        $output["postalAddress"] = $row[5];
                        $output["login_time_stamp"] = $login_time_stamp;
                        $output["rep_mobile_no"] = $row[6];
                        $output["territory_category_id"] = 100;
                        $output["territoryId"] = 101;
                        $output["rep_type"] = 102;
                        $getLastOrder = $conn->query("SELECT order_id FROM tbl_order WHERE user_id='$userId' ORDER BY timestamp DESC LIMIT 1");
                        if ($gls = $getLastOrder->fetch_array()) {
                            $output["last_order_id"] = $gls[0];
                        } else {
                            $output["last_order_id"] = null;
                        }
                        //last return id
                        $getLastOrder = $conn->query("SELECT order_id FROM tbl_return_order WHERE user_id='$userId' ORDER BY timestamp DESC LIMIT 1");
                        if ($gls = $getLastOrder->fetch_array()) {
                            $output["last_return_id"] = $gls[0];
                        } else {
                            $output["last_return_id"] = null;
                        }
                        $output["last_sreturn_id"] = null;
                        $output["last_dreturn_id"] = null;
                        $output["is_blitz"] = 5;
                        $output["is_ambassador"] = 5;
                        $output["session"] = $sessionId;
                        $output["available_call_order_count"] = 500;
                        $output["is_ambassador_2"] = 7;
                        $output["routeId"] = $row[11];
                        $output["area_code"] = '111';
                        $output["area_name"] = $row[15];
                        $output["districtId"] = 25;
                        $output["distributor_id"] = $row[19];
                    } else {
                        $output["result"] = false;
                        $output["msg"] = "Could not complete signin function.";
                        $output["login_time_stamp"] = $login_time_stamp;
                    }
                }
            } else {
                $output["result"] = false;
                $output["msg"] = "Incorrect credentials or no ROUTE has been assigned.";
                $output["login_time_stamp"] = $login_time_stamp;
            }
        }
    } else {
        $output["result"] = false;
        $output["msg"] = "System has not been activated, please contact administrator.";
    }
} else {
    $output["result"] = false;
    $output["msg"] = "Something went wrong.Please try again.";
}
echo json_encode($output);
