<?php

function requireRole($roles = [])
{
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "auth/login");
        exit;
    }

    if (!in_array($_SESSION['user']['role'], $roles)) {
        echo "Bạn không có quyền truy cập!";
        exit;
    }
}
