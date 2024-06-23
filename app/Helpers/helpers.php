<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('isActiveKegiatan')) {
    function isActiveKegiatan()
    {
        return Request::is('jadwal') || Request::is('kegiatanDosen') || Request::is('konfirmasi');
    }
}

if (!function_exists('isActiveDashboard')) {
    function isActiveDashboard()
    {
        return Request::is('home');
    }
}

if (!function_exists('isActiveDosen')) {
    function isActiveDosen()
    {
        return Request::is('dosen');
    }
}

if (!function_exists('isActiveLogout')) {
    function isActiveLogout()
    {
        return Request::is('logout');
    }
}

if (!function_exists('purify_phone_number')) {
    function purify_phone_number($phone)
    {
        if ($phone == "") {
            return null;
        }

        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);

        if (substr($phone, 0, 1) === "+") {
            $phone = substr($phone, 1);
        }

        if (substr($phone, 0, 2) === "08") {
            $phone = substr($phone, 1);
            $phone = "62" . $phone;
        }

        return $phone;
    }
}

if (!function_exists('readable_date')) {
    function readable_date($date)
    {
        $bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sept", "Okt", "Nov", "Des"];
        $dateFormatted = date("d m Y", strtotime($date));
        $splited = explode(" ", $dateFormatted);
        $idBulan = $bulan[intval($splited[1]) - 1];
        return "{$splited[0]} {$idBulan} $splited[2]";
    }
}
if (!function_exists('readable_time')) {
    function readable_time($date)
    {
        return date("H:i", strtotime($date));
    }
}
