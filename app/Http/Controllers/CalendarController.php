<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Carbon\Carbon;

class CalendarController extends Controller
{
    //
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari query string atau pakai sekarang
        $month = (int) $request->get("month", now()->month);
        $year = (int) $request->get("year", now()->year);

        // Ambil semua post untuk bulan dan tahun tersebut
        $posts = Post::whereMonth("post_at", $month)
            ->whereIn("status", ["published", "revision"])
            ->whereYear("post_at", $year)
            ->get(["id", "title", "post_at"]);

        // Nama bulan untuk dropdown
        $months = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember",
        ];

        // Range tahun (2020 - 5 tahun ke depan)
        $yearRange = range(2024, now()->year + 5);

        // Data kalender
        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Minggu

        // Siapkan array tanggal
        $calendar = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->toDateString();
            $calendar[$day] = [
                "date" => $date,
                "posts" => $posts->filter(
                    fn($p) => $p->post_at->isSameDay($date),
                ),
            ];
        }

        return view(
            "calendar.page",
            compact(
                "posts",
                "month",
                "year",
                "months",
                "yearRange",
                "calendar",
                "startDayOfWeek",
                "daysInMonth",
            ),
        );
    }

    public function list(Request $request)
    {
        $date = $request->input("date");

        if (!$date) {
            return redirect()
                ->route("calendar.page")
                ->with("error", "Tanggal tidak ditemukan.");
        }

        $posts = Post::whereDate("post_at", $date)
            ->whereIn("status", ["published", "revision"])
            ->orderBy("post_at", "desc")
            ->get();

        return view("calendar.list", compact("date", "posts"));
    }
}
