<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('calculate_working_days')) {
    function calculate_working_days($start_date, $working_days = 15) {
        $current_date = strtotime($start_date);
        $days_added = 0;

        while ($days_added < $working_days) {
            $current_date = strtotime("+1 day", $current_date);

            // Jika hari ini bukan Sabtu atau Minggu
            if (date('N', $current_date) < 6) {
                $days_added++;
            }
        }

        return date('Y-m-d', $current_date);
    }
}
?>
