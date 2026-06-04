<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @vite('resources/css/app.css')

     <style>
        /* Repeat thead on every page */
        thead {
            display: table-row-group !important; /* prevents repeating on next pages */
        }

        /* Prevent row from splitting across pages */
        /* tr {
            page-break-inside: avoid;
        } */

        /* Add margin on every page including subsequent ones */
        @page {
            margin: 15mm 10mm 15mm 10mm; /* top right bottom left */
        }

        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="bg-white text-gray-800 text-sm font-sans p-10">

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-xl font-bold uppercase tracking-wide">Projects List</h1>
        <p class="text-gray-500 text-xs mt-1">Generated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    {{-- Table --}}
    <table class="w-full border-collapse text-sm">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 px-4 py-2 text-left">Project Title</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Project Amount</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Bidding Date</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $index => $project)
            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                <td class="border border-gray-200 px-4 py-2 capitalize">{{ $project->project_title }}</td>
                <td class="border border-gray-200 px-4 py-2 text-right">
                    ₱{{ number_format($project->amount, 2) }}
                </td>
                <td class="border border-gray-200 px-4 py-2 text-center">
                    {{ \Carbon\Carbon::parse($project->bidding_date)->format('Y-m-d') }}
                </td>
                <td class="border border-gray-200 px-4 py-2 text-center">
                    <span class="
                        px-2 py-1 rounded-xl text-xs font-semibold capitalize 
                        {{ $project->status === 'awarded' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $project->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        {{ $project->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-400">No projects found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>


</body>
</html>