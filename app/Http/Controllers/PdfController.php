<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfController extends Controller
{
    public function projects()
    {
        $projects = Project::all();

        return Pdf::view('pdf.projects-list', compact('projects'))
            ->inline();
    }

    public function bids()
    {
        $bids = Bid::all();

        return Pdf::view('pdf.bids-list', compact('bids'))
            ->inline();
    }

    public function resolution(Bid $bid)
    {
        return pdf::view('pdf.resolution', compact('bid'))
            ->paperSize(216, 330, 'mm')
            ->footerView('pdf.partials.footer')
            ->margins(0, 0, 20, 0) 
            ->inline();
    }
}