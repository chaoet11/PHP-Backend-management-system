<?php
session_start();

if (isset($_SESSION['admin'])) {
    include 'trip_calendar_list-admin.php';
} else {
    include 'trip_calendar_list-no-admin.php';
}
