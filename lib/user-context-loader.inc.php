<?php

$contextKey = $_GET['contextKey'] ?? '';
$employee = vendorApi()->context($contextKey);

if ($employee) {
    if (@$employee?->errors) {

        print '<div style="font-family: Sans-Serif; text-align: center;">Перезагрузите страницу с приложением.</div>';
        exit;

    }
}

$accountId = $employee->accountId;
$isAdmin = $employee->permissions->admin->view;

