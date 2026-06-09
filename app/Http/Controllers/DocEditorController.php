<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Symfony\Component\Process\Process;

class DocEditorController extends Controller
{
    // ── Serve raw template file (replaces the hardcoded route closure) ────────

    public function file(Project $project, string $template = 'bac-resolution')
    {
        $def = $this->resolveTemplate($template);

        return response()->file($def['template'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    // ── Page ──────────────────────────────────────────────────────────────────

    public function show(Project $project, string $template = 'bac-resolution')
    {
        $def = $this->resolveTemplate($template);

        $config = [
            'docUrl'     => route('doc.doc-template',       [$project, $template]),
            'exportUrl'  => route('doc.editor-export',  [$project, $template]),
            'previewUrl' => route('doc.editor-preview', [$project, $template]),

            'fileName'     => $def['fileName'],
            'downloadName' => $def['downloadName'],
            'storageKey'   => "{$template}_{$project->id}",

            'hints'        => $def['hints']        ?? [],
            'tablesConfig' => $def['tablesConfig'] ?? [],
            'defaults'     => $this->resolveDefaults($def, $project),
            'defaultRows'  => $this->resolveDefaultRows($def, $project),

            'inputPatterns' => $def['inputPatterns'] ?? [],
            'labels' => $def['labels'] ?? [],
        ];

            $titles = [
                'bac-resolution'            => 'BAC Resolution Declaring LCRB',
                'evaluation-report'         => 'Bid Evaluation Report',
                'contract-form'             => 'NGPA Contract-Form',
                'award-notice'              => 'Notice of Award',
                'notice-post-qualification' => 'Notice of Post-Qualification',
                'notice-proceed'            => 'Notice to Proceed',
                'notif-lcb'                 => 'Notification of Lowest Calculated Bid',
                'post-quali-eval'           => 'Post-Qualification Evaluation Report',

            ];
            $title = $titles[$template] ?? 'BAC Printing System';


        return view('docs.template', compact('config', 'title'));
    }

    // ── Export ────────────────────────────────────────────────────────────────

    public function export(Request $request, Project $project, string $template = 'bac-resolution')
    {
        $def       = $this->resolveTemplate($template);
        $args      = $request->input('args', []);
        $tableRows = $request->input('table_rows', []);

        $errors = $this->validateInputs($def, $args, $tableRows);

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $outPath = $this->tempPath('out_', '.docx');

        [$ok, $errorMsg] = $this->runPythonFill($def['template'], $args, $tableRows, $outPath);

        if (!$ok) {
            return response()->json(['error' => "Export failed: {$errorMsg}"], 500);
        }

        return response()->download($outPath, $def['downloadName'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(Request $request, Project $project, string $template = 'bac-resolution')
    {
        
        $def       = $this->resolveTemplate($template);
        $args      = $request->input('args', []);
        $tableRows = $request->input('table_rows', []);

        $filledDocx = $this->tempPath('preview_', '.docx');

        [$ok, $errorMsg] = $this->runPythonFill($def['template'], $args, $tableRows, $filledDocx);

        if (!$ok) {
            return response()->json(['error' => 'Fill failed', 'stderr' => $errorMsg], 500);
        }

        $tmpDir = storage_path('app/tmp/');
        $pdf    = $this->convertToPdf($filledDocx, $tmpDir);
        @unlink($filledDocx);

        if (!$pdf) {
            return response()->json(['error' => 'PDF conversion failed'], 500);
        }

        return response()->file($pdf, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
        ])->deleteFileAfterSend(true);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function resolveTemplate(string $slug): array
    {
        $def = config("doc_templates.{$slug}");
        abort_if(!$def, 404, "Unknown document template: {$slug}");

        // Resolve the full filesystem path here, not in the config file.
        // public_path() cannot be called during config boot.
        $def['template'] = public_path('docs/' . $def['file']);

        return $def;
    }

    /**
     * Map the template's 'defaults' dot-paths to actual model values.
     * e.g. 'project_title_upper' => 'project_title' → $project->project_title
     * Supports dot-notation for relations: 'bid.contractor.name'
     */
    private function resolveDefaults(array $def, Project $project): array
    {
        $defaults = [];
        $formatAmount = $def['formatAmount'] ?? [];
        $formatWords  = $def['formatWords']  ?? [];
        


        foreach ($def['defaults'] ?? [] as $placeholder => $modelPath) {
            $value = data_get($project, $modelPath, '');

            if (in_array($placeholder, $formatAmount) && is_numeric($value)) {
                $value = number_format((float) $value, 2);
            }

            if (in_array($placeholder, $formatWords) && is_numeric($value)) {
                $value = $this->formatNumberToWords((int) $value);
            }

            $defaults[$placeholder] = $value;
        }

        foreach ($def['literals'] ?? [] as $placeholder => $value) {
            $defaults[$placeholder] = $value;
        }
        return $defaults;
    }

    private function formatNumberToWords(int $number): string
    {
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $words = $formatter->format($number); 
        return "{$words} ({$number})";
    }

    private function resolveDefaultRows(array $def, Project $project): array
    {
        $project->load('awardedBid');
        $formatAmount = $def['formatAmount'] ?? [];

        $defaultRows = [];
        foreach ($def['defaultRows'] ?? [] as $group => $fields) {
            $row = [];
            foreach ($fields as $fieldKey => $modelPath) {
                $value = data_get($project, $modelPath, '');

                if (in_array($fieldKey, $formatAmount) && is_numeric($value)) {
                    $value = number_format((float) $value, 2);
                }
                $row[$fieldKey] = $value;
            }
            $defaultRows[$group] = [$row]; 
        }
        return $defaultRows;
    }

    private function validateInputs(array $def, array $args, array $tableRows): array
    {
        $errors = [];

        foreach ($def['requiredArgs'] ?? [] as $key => $label) {
            if (empty(trim($args[$key] ?? ''))) {
                $errors[] = "{$label} is required.";
            }
        }

        

        foreach ($def['requiredTableFields'] ?? [] as $group => $fields) {
            $rows = $tableRows[$group] ?? [];

            if (empty($rows)) {
                $errors[] = 'Table ' . strtoupper($group) . ' must have at least one row.';
                continue;
            }

            foreach ($rows as $i => $row) {
                $rowNum = $i + 1;
                foreach ($fields as $key => $label) {
                    if (empty(trim($row[$key] ?? ''))) {
                        $errors[] = "{$label} (Row {$rowNum}) is required.";
                    }
                }
            }
        }

        return $errors;
    }

    private function runPythonFill(string $templatePath, array $args, array $tableRows, string $outPath): array
    {
        if (!file_exists($templatePath)) {
            return [false, "Template not found: {$templatePath}"];
        }

        @mkdir(dirname($outPath), 0755, true);

        $process = new Process([
            'python3',
            resource_path('scripts/fill_docx.py'),
            $templatePath,
            json_encode($args),
            json_encode($tableRows),
            $outPath,
        ]);
        $process->run();

        if (!$process->isSuccessful() || !file_exists($outPath)) {
            return [false, $process->getErrorOutput()];
        }

        return [true, ''];
    }

    private function convertToPdf(string $docxPath, string $outDir): ?string
    {
        $process = new Process([
            '/opt/libreoffice26.2/program/soffice',
            '--headless', '--convert-to', 'pdf',
            $docxPath, '--outdir', $outDir,
        ]);
        $process->setTimeout(30);
        $process->run();

        $pdf = $outDir . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';

        return file_exists($pdf) ? $pdf : null;
    }

    private function tempPath(string $prefix, string $ext): string
    {
        $dir = storage_path('app/tmp/');
        @mkdir($dir, 0755, true);
        return $dir . uniqid($prefix) . $ext;
    }
}