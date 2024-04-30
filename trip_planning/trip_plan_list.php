<?php
session_start();

if (isset($_SESSION['admin'])) {
    include 'trip_plan_list-admin.php';
} else {
    include 'trip_plan_list-no-admin.php';
}
