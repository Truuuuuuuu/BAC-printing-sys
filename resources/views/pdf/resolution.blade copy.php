<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    @vite('resources/css/app.css')

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            padding-bottom: 0;
        }

        @page {
            margin-top: 1.10in;
            /* page 2+ top margin */
        }

        @page :first {
            margin-top: 0;
            /* page 1 has no top margin — header handles it */
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: white;
        }

        /* ── HEADER ── */
        .header {
            height: 180px;
            position: relative;
            overflow: hidden;
            font-size: 18.67px;
        }

        /* ── CONTENT ──
           margin-bottom must be large enough to always clear the fixed footer.
           Footer is ~60px tall, sits 0.30in (≈28.8px) from bottom.
           Total reserved = 60 + 28.8 ≈ 89px → use 1.20in (≈115px) to be safe.
           Also applied as padding so background fills correctly.
        */
        .content {
            margin-left: 1.20in;
            margin-right: 1.20in;
            margin-top: 0.20in;
            font-size: 16px;


        }

        p {
            margin-bottom: 12pt;
            text-align: justify;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 12pt;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 4px 8px;
        }

        table thead th {
            text-align: center;
            font-weight: bold;
        }

        .alpha-list {
            list-style: none;
            counter-reset: alpha;
            padding-left: 0.5in;
            margin-bottom: 12pt;
        }

        .alpha-list li {
            counter-increment: alpha;
            margin-bottom: 6pt;
            text-align: justify;
            
        }

        .alpha-list li::before {
            content: counter(alpha, lower-alpha) ") ";
            margin-right: 30px;
        }

    </style>
</head>

<body>

    {{-- HEADER --}}
    <header class="relative h-[180px] overflow-hidden">
        <img src="{{ public_path('LGU-bg.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 flex flex-col items-center justify-center text-center leading-tight">
            <h3 class="text-[18.7px]">Republic of the Philippines</h3>
            <h3 class="text-[18.7px]">Province of Sorsogon</h3>
            <h1 class="text-[18.7px] font-bold uppercase">Municipality of Casiguran</h1>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="content">

        <p class="text-[18.7px] text-center font-bold">
            BAC Resolution Declaring LCRB and Recommending Approval
        </p>

        <p class="text-[16px] text-center font-bold">
            RESOLUTION NO. – PB-CW-0000-00-000
        </p>

        <p class="indent-12">
            WHEREAS, the <strong>Local Government Unit of Casiguran</strong> posted and advertised the Invitation to Bid
            for <strong>{{ strtoupper('Project Title') }}</strong> at the PhilGEPS's (Philippine Government
            Electronic Procurement System) website on <strong>DATE</strong> and posted in a conspicuous place from
            <strong>DATE – DATE</strong>. The Approved Budget for the Contract is Php
            <strong>{{ ('Amount') }}</strong>.
        </p>

        <p class="indent-12">
            WHEREAS, in response to the said advertisements and publication, there were forty-eight (48) interested
            bidders who submitted letter of intent and six (6) of these bidders purchased bidding documents and
            submitted bid proposals on time, namely: <strong>{{ $bid->company_name }}</strong>
        </p>

        <p class="indent-12">
            Whereas, upon evaluation of financial documents it appeared that the bid proposal of
            <strong>{{ $bid->company_name }}</strong> is considered the lowest calculated bid.
        </p>

        <table>
            <thead>
                <tr>
                    <th style="width: 55%;">Name of Bidder</th>
                    <th style="width: 25%;">Bid Amount <br> (As Read)</th>
                    <th style="width: 20%;">% Variance from ABC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <p>
            WHEREAS, the detailed evaluation of bids conducted from <strong>DATE</strong> resulted in the following:
        </p>

        <table>
            <thead>
                <tr>
                    <th style="width: 55%;">Name of Bidder</th>
                    <th style="width: 25%;">Bid Amount (As Calculated)</th>
                    <th style="width: 20%;">% Variance from ABC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <p>
            WHEREAS, upon post-qualification or careful examination, validation and verification of all the
            eligibility, technical and financial requirements submitted by the Bidders with the Lowest Calculated
            Bid, <strong>{{ $bid->company_name }}</strong>, its bid has been found to be responsive;
        </p>

        

        <p class="uppercase">
            NOW, THEREFORE, We, the Members of the Bids and Awards Committee, hereby RESOLVE as it is hereby RESOLVED:
        </p>

            <p class="uppercase">
            NOW, THEREFORE, We, the Members of the Bids and Awards Committee, hereby RESOLVE as it is hereby RESOLVED:
        </p>


        <ol class="alpha-list">
            <li>
                To declare <strong>{{ $bid->company_name }}</strong> as the Bidder with the Lowest Calculated
                Responsive Bid for the <strong>{{ strtoupper('Project Title') }}</strong>,
            </li>
            <li>
                To recommend for approval by the <strong>Municipal Mayor – Maria Minez R. Hamor</strong> of the <strong>Local Government
                Unit of Casiguran</strong> the foregoing findings.
            </li>
        </ol>

        <p>
            RESOLVED, at the BAC’s Office Municipal Hall, Casiguran, Sorsogon this ___________________.
        </p>

        <div class="flex flex-col gap-[64px] mt-[112px] mb-[48px]">
            <div class="flex justify-around">
                <div class="flex flex-col items-center justify-center">
                    <h3 class="underline font-bold italic">NORINA R. DUQUE</h3>
                    <h4>BAC Member</h4>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <h3 class="underline font-bold italic">MERLIN H. GABITAN</h3>
                    <h4>BAC Member</h4>
                </div>
            </div>

            <div class="flex justify-around">
                <div class="flex flex-col items-center justify-center">
                    <h3 class="underline font-bold italic">AVA AISA DE CASTRO-RENIVA</h3>
                    <h4>BAC Member</h4>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <h3 class="underline font-bold italic">ALONA A. SAYSON</h3>
                    <h4>BAC Member</h4>
                </div>
            </div>

            <div class="flex flex-col justify-center items-center">
                <h3 class="underline font-bold italic">ARLIE H. CORAÑES</h3>
                <h4>BAC Chairman</h4>
            </div>
        </div>


        <h4 class="mb-[48px]">Approved by:</h4>

        <div class="flex justify-start mb-[16px]">
            <div class=" flex flex-col items-center">
                <h3 class="uppercase font-bold">MARIA MINEZ R. HAMOR</h3>
                <h4 class="-mt-2">Municipal Mayor</h4>
            </div>


        </div>

        <h3>Date approved: _____________________</h3>

    </main>

    {{-- FOOTER: fixed, repeats on every printed page via Chromium --}}
    {{-- <footer class="footer">
        If the BAC determines that the bidder with the Lowest Calculated Bid passes all the criteria for
        post-qualification, it shall declare the said bidder as the bidder with the Lowest Calculated Responsive Bid,
        and the head of the Procuring Entity concerned shall award the contract to the said bidder. (IRR-A Section 34.3)
        The TWG, with the assistance of the Secretariat, if necessary, shall prepare the BAC Resolution declaring the
        LCRB.
    </footer> --}}

</body>

</html>