<?php


return [

    'bac-resolution' => [
        'fileName'     => 'BAC Resolution Declaring LCRB.docx',
        'downloadName' => 'BAC Resolution Declaring LCRB.docx',
        'file'         => 'BAC Resolution Declaring LCRB.docx',

        'hints' => [
            'total_interested_bidders'       => 'e.g. twenty-one (21)',
            'number_of_responsive_bidders'   => 'e.g. six (6)',
            'project_title'                  => 'e.g. Construction of Barangay Hall',
            'approved_budget'                => 'e.g. 400,000.00',
            'resolution_number'              => '0000-00-000',
            'winning_bidder'                 => 'e.g. ABC CONSTRUCTION',
            'philGEPS_posting_date'          => 'MM/DD/YY',
            'conspicuous_place_posting_date' => 'MM/DD/YY-MM/DD/YY',
            'bidders'                        => 'e.g. ABC CONSTRUCTION, JUAN COMPANY, ...',      
        ],

        'requiredArgs' => [
            'resolution_number'              => 'Resolution Number',
            'total_interested_bidders_lower' => 'Total Interested Bidders',
            'number_of_responsive_bidders'   => 'Number of Responsive Bidders',
            'project_title_upper'            => 'Project Title',
            'approved_budget'                => 'Approved Budget',
            'winning_bidder_upper'           => 'Winning Bidder',
            'philGEPS_posting_date'          => 'PhilGEPS Posting Date',
            'conspicuous_place_posting_date' => 'Conspicuous Place Posting Date',
            'list_of_bidders_upper'          => 'List of Bidders',
        ],

        'requiredTableFields' => [
            'a' => [
                'row_a_bidder_upper' => 'Table 1 Bidder Name',
                'row_a_amount'       => 'Table 1 Bid Amount',
            ],
            'b' => [
                'row_b_bidder_upper' => 'Table 2 Bidder Name',
                'row_b_amount'       => 'Table 2 Bid Amount',
            ],
        ],

        'tablesConfig' => [
            'a' => [
                'label'  => 'Table 1 — Bid Amount (As Read)',
                'fields' => [
                    'row_a_bidder_upper' => ['label' => 'Name of Bidder',       'placeholder' => 'e.g. ABC CONSTRUCTION'],
                    'row_a_amount'       => ['label' => 'Bid Amount (As Read)', 'placeholder' => 'e.g. 100,000.00'],
                    'row_a_variance'     => ['label' => '% Variance from ABC',  'placeholder' => 'e.g. 2.5%'],
                ],
            ],
            'b' => [
                'label'  => 'Table 2 — Bid Amount (As Calculated)',
                'fields' => [
                    'row_b_bidder_upper' => ['label' => 'Name of Bidder',             'placeholder' => 'e.g. ABC CONSTRUCTION'],
                    'row_b_amount'       => ['label' => 'Bid Amount (As Calculated)', 'placeholder' => 'e.g. 100,000.00'],
                    'row_b_variance'     => ['label' => '% Variance from ABC',         'placeholder' => 'e.g. 2.5%'],
                ],
            ],
        ],

        'defaultRows' => [
            'a' => [
                'row_a_bidder_upper' => 'awardedBid.company_name',
                'row_a_amount'       => 'awardedBid.bid_amount',
                'row_a_variance'     => 'variancePercentage',
            ],
            // 'b' => [
            //     'row_b_bidder_upper' => 'awardedBid.company_name',
            //     'row_b_amount'       => 'awardedBid.bid_amount',
            // ],
        ],

        'defaults' => [
            'project_title_upper' => 'project_title',
            'approved_budget'     => 'amount',
            'responsive_bidders'  => 'total_responsive_bidders',
            'list_of_bidders_upper' => 'awardedBid.company_name'
        ],
        'formatAmount' => ['approved_budget', 'bid_amount', 'row_a_amount'], 
        'formatWords' => ['responsive_bidders'],
        'inputPatterns' => ['resolution_number' => '9999-99-999',],
    ],

    'evaluation-report' => [
        'fileName'     => 'Bid Evaluation Report.docx',
        'downloadName' => 'Bid Evaluation Report.docx',
        'file'         => 'Bid Evaluation Report.docx',

        // 'hints' => [
        //     'awardee'         => 'e.g. ABC CONSTRUCTION CORP.',
        //     'contract_amount' => 'e.g. 1,250,000.00',
        //     'award_date'      => 'MM/DD/YYYY',
        // ],

        'requiredArgs' => [
            'project_title_upper' => 'Project Title',
            // 'awardee_upper'       => 'Awardee',
            'approved_budget'     => 'amount',
            'bid_amount'     => 'Contract Amount',
        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'defaults' => [
            'project_title_upper' => 'project_title',
            'approved_budget'     => 'amount',
            'company_name_upper'        => 'awardedBid.company_name',
            'responsive_bidders'   => 'totalResponsiveBidders',
        ],
        'formatAmount' => ['approved_budget', 'bid_amount'], 
        'formatWords' => ['responsive_bidders'],
    ],

];