<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Project;

class DocEditorController extends Controller
{
    public function show(Project $project)
    {   
        $defaults = [
            'project_title_upper'   => $project->project_title,
            'approved_budget' => number_format($project->amount,2),
        ];
        return view('docs.resolution', compact('defaults'));
    }
    public function export(Request $request)
    {
        $args      = $request->input('args', []);
        $tableRows = $request->input('table_rows', []);
        $template  = public_path('docs/BAC Resolution Declaring LCRB.docx');
        $outPath   = storage_path('app/tmp/' . uniqid('out_') . '.docx');

        if (!file_exists($template)) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        @mkdir(storage_path('app/tmp'), 0755, true);

        $scriptPath = resource_path('scripts/fill_docx.py');

        $process = new \Symfony\Component\Process\Process([
            'python3', $scriptPath, $template, json_encode($args), json_encode($tableRows), $outPath
        ]);
        $process->run();

        if (!$process->isSuccessful() || !file_exists($outPath)) {
            return response()->json(['error' => 'Export failed: ' . $process->getErrorOutput()], 500);
        }

        return response()->download($outPath, 'BAC Resolution Declaring LCRB.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

   public function preview(Request $request)
    {
        $args      = $request->input('args', []);
        $tableRows = $request->input('table_rows', []);
        $template  = public_path('docs/BAC Resolution Declaring LCRB.docx');
        $tmpDir    = storage_path('app/tmp/');
        $scriptPath = resource_path('scripts/fill_docx.py');

        @mkdir($tmpDir, 0755, true);

        $filledDocx = $tmpDir . uniqid('preview_') . '.docx';

        $fill = new \Symfony\Component\Process\Process([
            '/usr/bin/python3', $scriptPath, $template, json_encode($args), json_encode($tableRows), $filledDocx
        ]);
        $fill->run();

        if (!$fill->isSuccessful() || !file_exists($filledDocx)) {
            return response()->json([
                'error'  => 'Fill failed',
                'stderr' => $fill->getErrorOutput(),
                'stdout' => $fill->getOutput(),
            ], 500);
        }

        $convert = new \Symfony\Component\Process\Process([
            '/opt/libreoffice26.2/program/soffice', '--headless', '--convert-to', 'pdf',
            $filledDocx, '--outdir', $tmpDir
        ]);
        $convert->setTimeout(30);
        $convert->run();
        @unlink($filledDocx);

        $actualPdf = $tmpDir . pathinfo($filledDocx, PATHINFO_FILENAME) . '.pdf';

        if (!file_exists($actualPdf)) {
            return response()->json([
                'error'        => 'PDF conversion failed',
                'stderr'       => $convert->getErrorOutput(),
                'stdout'       => $convert->getOutput(),
                'pdf_expected' => $actualPdf,
                'tmp_files'    => scandir($tmpDir),
            ], 500);
        }

        return response()->file($actualPdf, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
        ])->deleteFileAfterSend(true);
    }
}