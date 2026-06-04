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
        <h1 class="text-xl font-bold uppercase tracking-wide">Bids List</h1>
        <p class="text-gray-500 text-xs mt-1">Generated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    {{-- Table --}}
    <table class="w-full border-collapse text-sm">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 px-4 py-2 text-left">Project Title</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Approved Budget</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Bidder</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Proprietor</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Contract Amount</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bids as $index => $bid)
            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                <td class="border border-gray-200 px-4 py-2">{{ $bid->project->project_title }}</td>
                <td class="border border-gray-200 px-4 py-2 text-left">
                    ₱{{ number_format($bid->project->amount, 2) }}
                </td>
                <td class="border border-gray-200 px-4 py-2">{{ $bid->company_name }}</td>
                <td class="border border-gray-200 px-4 py-2">{{ $bid->proprietor }}</td>
                <td class="border border-gray-200 px-4 py-2 text-left">
                    ₱{{ number_format($bid->bid_amount, 2) }}
                </td>
                <td class="border border-gray-200 px-4 py-2">{{ $bid->address }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-400">No bids found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    

</body>
</html>