<?php
session_start();

if (isset($_SESSION['admin'])) {
    include 'trip_detail_list-admin.php';
} else {
    include 'trip_detail_list-no-admin.php';
}
