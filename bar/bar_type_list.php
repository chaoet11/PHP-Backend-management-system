<?php
session_start();

if (isset($_SESSION['admin'])) {
    include 'bar_type_list-admin.php';
} else {
    include 'bar_type_list-no-admin.php';
}
