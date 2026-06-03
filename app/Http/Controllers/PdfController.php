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

        return Pdf::view('pdf.projects', compact('projects'))
            ->inline();
    }

    public function bids()
    {
        $bids = Bid::all();

        return Pdf::view('pdf.bids', compact('bids'))
            ->inline();
    }
}