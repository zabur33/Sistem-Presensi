<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show attendance bar chart with 30-day dummy data.
     */
    public function attendance()
    {
        // Generate labels 1..30
        $labels = range(1, 30);

        // Generate dummy attendance values (0,1,2)
        $values = [];
        foreach ($labels as $i) {
            $values[] = random_int(0, 2);
        }

        return view('reports.attendance', [
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}


