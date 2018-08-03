<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Order;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
       // $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admins.admin');
    }

    public function report()
    {
        $private_songs = Song::where('upload_type','private')->count();

        $sold_songids = Order::where('paid_order', 1)->pluck('song_id');
        $sold_songs = Song::whereIn('id', $sold_songids)->count();

        $total_amount_collec = Order::where('paid_order', 1)->pluck('total_amount');
        $total_amount = array_sum($total_amount_collec->toArray());

        $ta = $total_amount_collec->toArray();
        $size = sizeof($ta);
        $total_payment_to_artist = 0;
        for( $i = 0; $i < $size; $i++) {

            $total_payment_to_artist = $total_payment_to_artist + 90 / 100 * $ta[$i];

        }

        $total_profit = 0;
        for( $i = 0; $i < $size; $i++) {

            $total_profit =$total_profit + 10 / 100 * $ta[$i];
        }

        $report = array('private_songs' => $private_songs, 'sold_songs' => $sold_songs, 'total_amount' => $total_amount , 'total_payment' => $total_payment_to_artist , 'total_profit' => $total_profit);

        return view('admins.show_reports')->withReport($report);

    }
}
