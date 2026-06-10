<?php

return [

    // BAC Resolution Declaring LCRB
    'bac-resolution' => [
        'fileName' => 'BAC Resolution Declaring LCRB.docx',
        'downloadName' => 'BAC Resolution Declaring LCRB.docx',
        'file' => 'BAC Resolution Declaring LCRB.docx',

        'hints' => [
            'total_interested_bidders' => 'e.g. 5',
            'responsive_bidders' => 'e.g. 6',
            'project_title' => 'e.g. Construction of Barangay Hall',
            'approved_budget' => 'e.g. 400,000.00',
            'resolution_number' => '0000-00-000',
            'winning_bidder' => 'e.g. ABC CONSTRUCTION',
            'philGEPS_posting_date' => 'MM/DD/YY',
            'conspicuous_place_posting_date' => 'MM/DD/YY-MM/DD/YY',
            'list_of_bidders' => 'e.g. ABC CONSTRUCTION, JUAN COMPANY, ...',
            'eligible_bidders' => 'e.g. 7',

        ],

        'requiredArgs' => [
            'resolution_number' => 'Resolution Number',
            'total_interested_bidders_wordNumLower' => 'Total Interested Bidders',
            'responsive_bidders_wordNumLower' => 'Number of Responsive Bidders',
            'project_title_upper' => 'Project Title',
            'approved_budget_formatAmount' => 'Approved Budget',
            'winning_bidder_upper' => 'Winning Bidder',
            'philGEPS_posting_date' => 'PhilGEPS Posting Date',
            'conspicuous_place_posting_date' => 'Conspicuous Place Posting Date',
            'bidders_upper' => 'List of Bidders',
            'bid_evaluation_date' => 'Bid Evaluation Date',
            'eligible_bidders_numWord'  => 'Eligible Bidders',
            'resolution_date'   => 'Resolution Date',
            'approved_date'=> 'Date Approved',
        ],

        'requiredTableFields' => [
            'a' => [
                'row_a_bidder_upper' => 'Table 1 Bidder Name',
                'row_a_amount_formatAmount' => 'Table 1 Bid Amount',
            ],
            'b' => [
                'row_b_bidder_upper' => 'Table 2 Bidder Name',
                'row_b_amount_formatAmount' => 'Table 2 Bid Amount',
            ],
        ],

        'tablesConfig' => [
            'a' => [
                'label' => 'Table 1 — Bid Amount (As Read)',
                'fields' => [
                    'row_a_bidder_upper' => ['label' => 'Name of Bidder',       'placeholder' => 'e.g. ABC CONSTRUCTION', 'type' => 'text'],
                    'row_a_amount_formatAmount' => ['label' => 'Bid Amount (As Read)', 'placeholder' => 'e.g. 100,000.00', 'type' => 'number'],
                    'row_a_variance' => ['label' => '% Variance from ABC',  'placeholder' => 'e.g. 2.5%', 'type' => 'number'],
                ],
            ],
            'b' => [
                'label' => 'Table 2 — Bid Amount (As Calculated)',
                'fields' => [
                    'row_b_bidder_upper' => ['label' => 'Name of Bidder',             'placeholder' => 'e.g. ABC CONSTRUCTION', 'type' => 'text'],
                    'row_b_amount_formatAmount' => ['label' => 'Bid Amount (As Calculated)', 'placeholder' => 'e.g. 100,000.00', 'type' => 'number'],
                    'row_b_variance' => ['label' => '% Variance from ABC',         'placeholder' => 'e.g. 2.5%', 'type' => 'text'],
                ],
            ],
        ],

        'defaultRows' => [
            'a' => [
                'row_a_bidder_upper' => 'awardedBid.company_name',
                'row_a_amount_formatAmount' => 'awardedBid.bid_amount',
                'row_a_variance' => 'variancePercentage',
            ],
            'b' => [
                'row_b_bidder_upper' => 'awardedBid.company_name',
                'row_b_amount_formatAmount' => 'awardedBid.bid_amount',
                'row_b_variance' => 'variancePercentage',
            ],
        ],

        'fieldTypes' => [

            'philGEPS_posting_date' => [
                'type' => 'date',
            ],
            'conspicuous_place_posting_start_date' => [
                'type' => 'date',
            ],
            'conspicuous_place_posting_end_date' => [
                'type' => 'date',
            ],

            'approved_budget_formatAmount_formatAmount' => [
                'type' => 'number',
            ],

            'total_interested_bidders_wordNumLower' => [
                'type' => 'number',
            ],

            'responsive_bidders_wordNumLower' => [
                'type' => 'number',
            ],

            'bid_evaluation_date' => [
                'type'=> 'date',
            ],

            'eligible_bidders_numWord' => [
                'type'=> 'number',
            ],

            'resolution_date' => [
                'type'=> 'date',
            ],

            'approved_date' => [
                'type'=> 'date',
            ],

        ],

        'inputPatterns' => ['resolution_number' => '9999-99-999'],
    
        'defaults' => [
            'project_title_upper' => 'project_title',
            'approved_budget_formatAmount' => 'amount',
            'responsive_bidders_wordNum' => 'total_responsive_bidders',
            'list_of_bidders_upper' => 'awardedBid.company_name',
            'winning_bidder_upper' => 'awardedBid.company_name',
            'eligible_bidders_numWord' => 'total_responsive_bidders'
        ],
        // 'formatAmount' => ['approved_budget', 'bid_amount', 'row_a_amount', 'row_b_amount'],
        // 'formatWords' => ['responsive_bidders'],
        
    ],

    'evaluation-report' => [
        'fileName' => 'Bid Evaluation Report.docx',
        'downloadName' => 'Bid Evaluation Report.docx',
        'file' => 'Bid Evaluation Report.docx',

        'hints' => [
            'company_name' => 'e.g. ABC CONSTRUCTION CORP.',
            'contract_read_formatAmount' => 'e.g. 1,250,000.00',
            'contract_calculated_formatAmount' => 'e.g. 1,250,000.00',
            'eligibility' => 'e.g. 5',
            'issued_docs' => 'e.g. 5',
            'conference_date' => 'e.g. June 5, 2026',
            'responsive_bidders' => 'e.g. 6',
            'orig' => 'e.g. August 4, 2025, at 8:30am',
            'extension' => 'e.g. August 4, 2025, at 8:30 am ',
            'bid_opening' => 'e.g. July 3, 2026, at 11:00am',
            'num_submitted_bids' => 'e.g. 5',
            'orig_specified' => 'e.g. 120',
            'extension_revision' => 'e.g. Date',

        ],

        'requiredArgs' => [
            'project_title_upper' => 'Project Title',
            'company_name_upper' => 'Awardee',
            'approved_budget_formatAmount' => 'amount',
            'publication_date' => 'Date of Publication',
            'eligibility_date' => 'Date of Eligibility Check',
            'eligibility_wordNumUpper' => 'Number of eligibility envelopes received',
            'start_dateMonthDay' => 'Start period of availability of Bid Docs',
            'availability_date' => 'Period of availability of Bid Docs',
            'issued_docs_wordNumUpper' => 'Number of Bid Docs issued',
            'conference_date' => 'Date of Conference',
            'responsive_bidders_wordNumLower' => 'Total Responsive Bidders',
            'orig_dateTime' => 'Original Date, Time',
            'bid_opening_dateTime' => 'Bid Opening date, time',
            'num_submitted_bids_wordNumUpper' => 'Number of bids submitted',
            'orig_specified' => 'Originally specified',
            'contract_read_formatAmount' => 'Contract Amount (As Read)',
            'contract_calculated_formatAmount' => 'Contract Amount (As Calculated)',
        ],

        'optionalArgs' => [
            'extension_dateTime',
            'extension_revision_capitalize',
        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [
            'company_name_upper' => 'Company Name',
            'contract_read_formatAmount' => 'Contract Amount (As Read)',
            'contract_calculated_formatAmount' => 'Contract Amount (As Calculated)',
            'contract_amount_words' => 'Contract Amount in Words',
            'responsive_bidders' => 'Number of Responsive Bidders',
            'approved_budget_formatAmount' => 'Approved Budget',
            'publication_date' => '2.2a Date of Publication',
            'eligibility_date' => '2.3a Date of Eligibility Check',
            'eligibility_wordNumUpper' => '2.3b Number of eligibility envelopes received',
            'start_dateMonthDay' => '2.4a Start Period of availability of Bid Docs',
            'availability_date' => '2.4a End Period of availability of Bid Docs',
            'issued_docs_wordNumUpper' => '2.4b Number of Bid Docs issued',
            'conference_date' => '2.5a Date of Conference',
            'responsive_bidders_wordNumLower'   => 'Total Responsive Bidders',
            'orig_dateTime' => '3.1a Original date, time',
            'extension_dateTime' => '3.1b Extensions, if any',
            'bid_opening_dateTime' => '3.2 Bid Opening date, time',
            'num_submitted_bids_wordNumUpper' => '3.3 Numbers of bids submitted',
            'orig_specified' => '3.4a Originally Specified',
            'extension_revision_capitalize' => '3.4b Extension/Revisions if any',
        ],
        'fieldTypes' => [
            'approved_budget_formatAmount' => [
                'type' => 'number',
            ],

            'publication_date' => [
                'type' => 'date',
            ],
            'eligibility_date' => [
                'type' => 'date',
            ],
            'eligibility_wordNumUpper'=> [
                'type' => 'number'
            ],

            'start_dateMonthDay'=> [
                'type'=> 'date'
            ],

            'availability_date'=> [
                'type'=> 'date'
            ],

            'issued_docs_wordNumUpper'=> [
                'type'=> 'number'
            ],

            'conference_date' => [
                'type' => 'date'
            ],

            'responsive_bidders_wordNumLower'=> [
                'type'  => 'number'
            ],

            'orig_dateTime' => [
                'type'=> 'datetime'
            ],
            'bid_opening_dateTime' => [
                'type'=> 'datetime'
            ],
            'extension_dateTime' => [
                'type'=> 'datetime'
            ],

            'num_submitted_bids_wordNumUpper' => [
                'type'=> 'number'
            ],

            'orig_specified' => [
                'type'=> 'number'
            ],

            'contract_read_formatAmount' => [
                'type' => 'number'
            ],

            'contract_calculated_formatAmount' => [
                'type' => 'number'
            ],

        ],

        'defaults' => [
            'project_title_upper' => 'project_title',
            'approved_budget_formatAmount' => 'amount',
            'company_name_upper' => 'awardedBid.company_name',
            'responsive_bidders' => 'totalResponsiveBidders',
            'contract_read_formatAmount' => 'awardedBid.bid_amount',
            'contract_calculated_formatAmount' => 'awardedBid.bid_amount',
            'contract_amount_words' => 'contract_amount_in_words',
        ],
        'formatWords' => ['responsive_bidders'],
    ],

    'contract-form' => [
        'fileName' => 'NGPA_Contract-Form.docx',
        'downloadName' => 'NGPA_Contract-Form.docx',
        'file' => 'NGPA_Contract-Form.docx',

        'hints' => [

        ],

        'requiredArgs' => [

        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [

        ],

        'defaults' => [
            'project_title_upper' => 'project_title',
            'proprietor_upper' => 'awardedBid.proprietor',
            'company_name_upper' => 'awardedBid.company_name',
            'contract_amount_words' => 'contract_amount_in_words',

        ],

        'fieldTypes' => [
            'month' => ['type' => 'select', 'options' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']],
            'year' => ['type' => 'select', 'options' => ['2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033']],

            'resolution_date' => [
                'type' => 'date',
            ],
            'procurement_mode' => [
                'type' => 'select',
                'options' => [
                    'Public Bidding',
                    'Direct Contracting',
                    'Negotiated Procurement',
                    'Shopping',
                ],
            ],
            'remarks' => [
                'type' => 'textarea',
            ],
        ],

        'formatAmount' => [],
        'formatWords' => [],

    ],

    //Notice of Award
    'award-notice' => [
        'fileName' => 'Notice of Award.docx    ',
        'downloadName' => 'Notice of Award.docx',
        'file' => 'Notice of Award.docx',

        'hints' => [
            'proprietor' => 'e.g. Juan B. Dela Cruz',
            'company_name' => 'e.g. JUAN CONSTRUCTION',
            'project_title' => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'contract_amount_in_words' => 'e.g. One million',
            'bid_formatAmount' => 'e.g. 940,000',

        ],

        'requiredArgs' => [
            'proprietor_upper' => 'Proprietor',
            'company_name_upper' => 'Company Name',
            'project_title' => 'Project Title',
            'contract_amount_in_words' => 'Contract Amount in Words',
            'bid_formatAmount' => 'Contract Amount',
            'notice_date' => 'Date',
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title' => 'Project Title',
            'contract_amount_in_words' => 'Contract Amount in Words',
            'bid_formatAmount' => 'Contract Amount',
            'notice_date' => 'Date',

        ],

        'fieldTypes' => [
            'bid_formatAmount' => [
                'type' => 'number'
            ],

            'notice_date' => [
                'type' => 'date'
            ],
        ],

        'defaults' => [
            'proprietor_upper' => 'awardedBid.proprietor',
            'company_name_upper' => 'awardedBid.company_name',
            'project_title' => 'project_title',
            'contract_amount_in_words' => 'contract_amount_in_words',
            'bid_formatAmount' => 'awardedBid.bid_amount',
        ],

        'formatWords' => [],

    ],

    'notice-post-qualification' => [
        'fileName' => 'Notice of Post-Qualification.docx',
        'downloadName' => 'Notice of Post-Qualification.docx',
        'file' => 'Notice of Post-Qualification.docx',

        'hints' => [
            'title' => 'e.g. MR, MS, MRS',
            'proprietor' => 'e.g. Juan B. Dela Cruz',
            'company_name' => 'e.g. JUAN CONSTRUCTION',
            'project_title' => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
        ],

        'requiredArgs' => [
            'title_upper' => 'Title',
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'notice_date' => 'Date',
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [
            'title_upper' => 'Title',
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'notice_date' => 'Date',
        ],

        'fieldTypes' => [
            'title_upper' => ['type' => 'select', 'options' => ['MR','MRS','MS','MISS','DR', 'HON','ENGR','ATTY','ARCH']],
            'notice_date' => [
                'type'=> 'date'
            ],
            
        ],

        'defaults' => [
            'proprietor_upper' => 'awardedBid.proprietor',
            'company_name_upper' => 'awardedBid.company_name',
            'project_title_upper' => 'project_title',
        ],

        'formatAmount' => [],
        'formatWords' => [],

    ],

    'notice-proceed' => [
        'fileName' => 'Notice to Proceed.docx',
        'downloadName' => 'Notice to Proceed.docx',
        'file' => 'Notice to Proceed.docx',

        'hints' => [
            'proprietor' => 'e.g. Juan B. Dela Cruz',
            'company_name' => 'e.g. JUAN CONSTRUCTION',
            'project_title' => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'date' => 'e.g. MM/DD/YY',
        ],

        'requiredArgs' => [
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'date' => 'Date',
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [
            'title_upper' => 'Title',
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'date' => 'Date',
        ],

        'defaults' => [
            'proprietor_upper' => 'awardedBid.proprietor',
            'company_name_upper' => 'awardedBid.company_name',
            'project_title_upper' => 'project_title',
        ],

        'formatAmount' => [],
        'formatWords' => [],

    ],

    'notif-lcb' => [
        'fileName' => 'Notification of Lowest Calculated Bid.docx',
        'downloadName' => 'Notification of Lowest Calculated Bid.docx',
        'file' => 'Notification of Lowest Calculated Bid.docx',

        'hints' => [
            'title' => 'e.g. MR, MS, MRS',
            'proprietor' => 'e.g. Juan B. Dela Cruz',
            'company_name' => 'e.g. JUAN CONSTRUCTION',
            'project_title' => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'date' => 'e.g. MM/DD/YY',
        ],

        'requiredArgs' => [
            'title_upper' => 'Title',
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'date' => 'Date',
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [
            'proprietor_upper' => 'Proprietor/Owner',
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'date' => 'Date',
        ],

        'defaults' => [
            'proprietor_upper' => 'awardedBid.proprietor',
            'company_name_upper' => 'awardedBid.company_name',
            'project_title_upper' => 'project_title',
        ],

        'formatAmount' => [],
        'formatWords' => [],

    ],

    'post-quali-eval' => [
        'fileName' => 'Post-Qualification Evaluation Report.docx',
        'downloadName' => 'Post-Qualification Evaluation Report.docx',
        'file' => 'Post-Qualification Evaluation Report.docx',

        'hints' => [
            'company_name_upper' => 'e.g. JUAN CONSTRUCTION',
            'project_title_upper' => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'bid_amount' => 'e.g. 800,000.00',
        ],

        'requiredArgs' => [
            'company_name_upper' => 'Company Name',
            'project_title_upper' => 'Project Title',
            'bid_amount' => 'Contract Amount',

        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig' => [],

        'labels' => [

        ],

        'defaults' => [
            'company_name_upper' => 'awardedBid.company_name',
            'project_title_upper' => 'project_title',
            'bid_amount' => 'awardedBid.bid_amount',
        ],

        'formatAmount' => ['bid_amount'],
        'formatWords' => [],

    ],

    'fieldTypes' => [
        'day' => ['type' => 'text'],
        'month' => ['type' => 'select', 'options' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']],
        'year' => ['type' => 'date'],

        'resolution_date' => [
            'type' => 'date',
        ],
        'procurement_mode' => [
            'type' => 'select',
            'options' => [
                'Public Bidding',
                'Direct Contracting',
                'Negotiated Procurement',
                'Shopping',
            ],
        ],
        'remarks' => [
            'type' => 'textarea',
        ],
    ],

];
