<?php
session_start();

if (isset($_SESSION['admin'])) {
    include 'trip_saved_list-admin.php';
} else {
    include 'trip_saved_list-no-admin.php';
}
