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
            'list_of_bidders_upper' => 'awardedBid.company_name',
            'winning_bidder_upper'   => 'awardedBid.company_name',
        ],
        'formatAmount' => ['approved_budget', 'bid_amount', 'row_a_amount'], 
        'formatWords' => ['responsive_bidders'],
        'inputPatterns' => ['resolution_number' => '9999-99-999',],
    ],

    'evaluation-report' => [
        'fileName'     => 'Bid Evaluation Report.docx',
        'downloadName' => 'Bid Evaluation Report.docx',
        'file'         => 'Bid Evaluation Report.docx',

        'hints' => [
            'company_name'         => 'e.g. ABC CONSTRUCTION CORP.',
            'contract_read_amount' => 'e.g. 1,250,000.00',
            'contract_calculated_amount' => 'e.g. 1,250,000.00',
            'date_of_publication'        => 'e.g. June 5, 2026',
            'date_of_eligibility'        => 'e.g. June 5, 2026',
            'num_of_eligibility'         => 'Two (2)',
            'period_of_availability'     => 'e.g. June 3 - July 1, 2026',
            'num_of_docs_issued'         => 'Two (2)',
            'date_of_conference'         => 'e.g. June 5, 2026',
            'orig_date_time'=> 'e.g. August 4, 2025, at 8:30am',
            'extension'=> 'e.g. August 4, 2025, at 8:30 am ',
            'bid_opening_date_time'=> 'e.g. July 3, 2026, at 11:00am',
            'num_submitted_bids'=> 'e.g. One (1)',
            'orig_specified'=> 'e.g. 120',
            'extension_revision'=> 'e.g. Date',
            
        ],

        'requiredArgs' => [
            'project_title_upper'            => 'Project Title',
            'company_name_upper'             => 'Awardee',
            'approved_budget'                => 'amount',
            'contract_calculated_amount'     => 'Contract Amount',
            'date_of_publication_capitalize' => 'Date of Publication',
            'date_of_eligibility_capitalize'        => 'Date of Eligibility Check',
            'num_of_eligibility_capitalize'         => 'Number of eligibility envelopes received',
            'period_of_availability_capitalize'     => 'Period of availability of Bid Docs',
            'num_of_docs_issued_capitalize'         => 'Number of Bid Docs issued',
            'date_of_conference_capitalize'         => 'Date of Conference',
            'orig_date_time_capitalize'             => 'Original Date, Time',
            'bid_opening_date_time_capitalize'                 => 'Bid Opening date, time',
            'num_submitted_bids_capitalize'                    => 'Number of bids submitted',
            'orig_specified'                        => 'Originally specified',
        ],

        'optionalArgs' => [
            'extension_capitalize', 
            'extension_revision_capitalize'
        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [
            'company_name_upper'         => 'Company Name',
            'contract_read_amount'       => 'Contract Amount (As Read)',
            'contract_calculated_amount' => 'Contract Amount (As Calculated)',
            'contract_amount_words'      => 'Contract Amount in Words',
            'responsive_bidders'         => 'Number of Responsive Bidders',
            'approved_budget'            => 'Approved Budget',
            'date_of_publication'        => '2.2a Date of Publication',
            'date_of_eligibility'        => '2.3a Date of Eligibility Check',
            'num_of_eligibility_capitalize'         => '2.3b Number of eligibility envelopes received',
            'period_of_availability_capitalize'     => '2.4a Period of availability of Bid Docs',
            'num_of_docs_issued_capitalize'         => '2.4b Number of Bid Docs issued',
            'date_of_conference_capitalize'         => '2.5a Date of Conference',
            'orig_date_time_capitalize'             => '3.1a Original date, time',
            'extension_capitalize'                  => '3.1b Extensions, if any',
            'bid_opening_date_time_capitalize'                 => '3.2 Bid Opening date, time',
            'num_submitted_bids_capitalize'                    => '3.3 Numbers of bids submitted',
            'orig_specified'                        => '3.4a Originally Specified',
            'extension_revision_capitalize'                        => '3.4b Extension/Revisions if any',
        ],

        'defaults' => [
            'project_title_upper' => 'project_title',
            'approved_budget'     => 'amount',
            'company_name_upper'        => 'awardedBid.company_name',
            'responsive_bidders'   => 'totalResponsiveBidders',
            'contract_read_amount' => 'awardedBid.bid_amount',
            'contract_calculated_amount' => 'awardedBid.bid_amount',
            'contract_amount_words' => 'contract_amount_in_words',
        ],
        'formatAmount' => ['approved_budget', 'bid_amount', 'contract_read_amount', 'contract_calculated_amount'], 
        'formatWords' => ['responsive_bidders'],
    ],


    'contract-form' => [
        'fileName'      => 'NGPA_Contract-Form.docx',
        'downloadName'  => 'NGPA_Contract-Form.docx',
        'file'          => 'NGPA_Contract-Form.docx',
        
        'hints' => [
            
        ],

        'requiredArgs' => [

        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [

        ],

        'defaults'=> [
            'project_title_upper'           => 'project_title',
            'proprietor_upper'              => 'awardedBid.proprietor',
            'company_name_upper'            => 'awardedBid.company_name',
            'contract_amount_words'         => 'contract_amount_in_words',
            
        ],

        'formatAmount' => [], 
        'formatWords' => [],

    ],


    'award-notice' => [
        'fileName'      => 'Notice of Award.docx    ',
        'downloadName'  => 'Notice of Award.docx',
        'file'          => 'Notice of Award.docx',
        
        'hints' => [
            'proprietor'                => 'e.g. Juan B. Dela Cruz',
            'company_name'              => 'e.g. JUAN CONSTRUCTION',
            'project_title'             => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'contract_amount_in_words'  => 'e.g. One million',
            'bid_amount'                => 'e.g. 940,000',
            'date'                      => 'MM/DD/YY',
            
        ],

        'requiredArgs' => [
            'proprietor_upper'                      => 'Proprietor',
            'company_name_upper'                    => 'Company Name',
            'project_title'                         => 'Project Title',
            'contract_amount_in_words'              => 'Contract Amount in Words',
            'bid_amount'                            => 'Contract Amount',
            'date' => 'Date',
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [
            'proprietor_upper'            => 'Proprietor/Owner',
            'company_name_upper'          => 'Company Name',
            'project_title'               => 'Project Title',
            'contract_amount_in_words'    => 'Contract Amount in Words',
            'bid_amount'                  => 'Contract Amount',
            'date' => 'Date',

        ],

        'defaults'=> [
            'proprietor_upper'              => 'awardedBid.proprietor',
            'company_name_upper'            => 'awardedBid.company_name',
            'project_title'                 => 'project_title',
            'contract_amount_in_words'      => 'contract_amount_in_words',
            'bid_amount'                    => 'awardedBid.bid_amount',
        ],


        'formatAmount'  => ['bid_amount'], 
        'formatWords'   => [],

    ],

    'notice-post-qualification' => [
        'fileName'      => 'Notice of Post-Qualification.docx',
        'downloadName'  => 'Notice of Post-Qualification.docx',
        'file'          => 'Notice of Post-Qualification.docx',
        
        'hints' => [
            'title'                   => 'e.g. MR, MS, MRS',
            'proprietor'              => 'e.g. Juan B. Dela Cruz',
            'company_name'            => 'e.g. JUAN CONSTRUCTION',
            'project_title'           => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'date'                    => 'e.g. MM/DD/YY',
        ],

        'requiredArgs' => [
            'title_upper'                   => 'Title',
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [
            'title_upper'                   => 'Title',
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'defaults'=> [
            'proprietor_upper'              => 'awardedBid.proprietor',
            'company_name_upper'            => 'awardedBid.company_name',
            'project_title_upper'           => 'project_title',
        ],


        'formatAmount' => [], 
        'formatWords' => [],

    ],

    'notice-proceed' => [
        'fileName'      => 'Notice to Proceed.docx',
        'downloadName'  => 'Notice to Proceed.docx',
        'file'          => 'Notice to Proceed.docx',
        
        'hints' => [
            'proprietor'              => 'e.g. Juan B. Dela Cruz',
            'company_name'            => 'e.g. JUAN CONSTRUCTION',
            'project_title'           => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'date'                    => 'e.g. MM/DD/YY',
        ],

        'requiredArgs' => [
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [
            'title_upper'                   => 'Title',
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'defaults'=> [
            'proprietor_upper'              => 'awardedBid.proprietor',
            'company_name_upper'            => 'awardedBid.company_name',
            'project_title_upper'           => 'project_title',
        ],


        'formatAmount' => [], 
        'formatWords' => [],

    ],

    'notif-lcb' => [
        'fileName'      => 'Notification of Lowest Calculated Bid.docx',
        'downloadName'  => 'Notification of Lowest Calculated Bid.docx',
        'file'          => 'Notification of Lowest Calculated Bid.docx',
        
        'hints' => [
            'title'                   => 'e.g. MR, MS, MRS',
            'proprietor'              => 'e.g. Juan B. Dela Cruz',
            'company_name'            => 'e.g. JUAN CONSTRUCTION',
            'project_title'           => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'date'                    => 'e.g. MM/DD/YY',
        ],

        'requiredArgs' => [
            'title_upper'                   => 'Title',
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [
            'proprietor_upper'              => 'Proprietor/Owner',
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'date'                          => 'Date'
        ],

        'defaults'=> [
            'proprietor_upper'              => 'awardedBid.proprietor',
            'company_name_upper'            => 'awardedBid.company_name',
            'project_title_upper'           => 'project_title',
        ],


        'formatAmount' => [], 
        'formatWords' => [],

    ],

    'post-quali-eval' => [
        'fileName'      => 'Post-Qualification Evaluation Report.docx',
        'downloadName'  => 'Post-Qualification Evaluation Report.docx',
        'file'          => 'Post-Qualification Evaluation Report.docx',
        
        'hints' => [
            'company_name_upper'            => 'e.g. JUAN CONSTRUCTION',
            'project_title_upper'           => 'e.g. Concreting of Canal and Sidewalk at Housing Project, Brgy. San Antonio, Casiguran, Sorsogon',
            'bid_amount'                    => 'e.g. 800,000.00'
        ],

        'requiredArgs' => [
            'company_name_upper'            => 'Company Name',
            'project_title_upper'           => 'Project Title',
            'bid_amount'                    => 'Contract Amount'
        
        ],

        'optionalArgs' => [

        ],

        'requiredTableFields' => [],
        'tablesConfig'        => [],

        'labels' => [

        ],

        'defaults'=> [
            'company_name_upper'            => 'awardedBid.company_name',
            'project_title_upper'           => 'project_title',
            'bid_amount'                    => 'awardedBid.bid_amount'
        ],


        'formatAmount' => ['bid_amount'], 
        'formatWords' => [],

    ],

];