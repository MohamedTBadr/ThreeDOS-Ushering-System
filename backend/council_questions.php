<?php
/**
 * Council-Specific Interview Questions
 * Updated with exact council names
 */

$COUNCIL_QUESTIONS = [
    'Marketing' => [
        'soft_skills' => [
            [
                'id' => 'mkt_soft_1',
                'question' => 'Tell me about a time you had to persuade someone to accept your idea.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Marketing'
            ],
            [
                'id' => 'mkt_soft_2',
                'question' => 'How do you handle criticism of your creative work?',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Marketing'
            ],
            [
                'id' => 'mkt_soft_3',
                'question' => 'Describe a situation where you had to work under tight deadlines for a campaign.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Marketing'
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'mkt_tech_1',
                'question' => 'What social media platforms are you most familiar with? How would you use them for marketing?',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => 'Marketing'
            ],
            [
                'id' => 'mkt_tech_2',
                'question' => 'Explain what SEO is and why it\'s important for digital marketing.',
                'category' => 'Tech Skills',
                'difficulty' => 'Medium',
                'council' => 'Marketing'
            ]
        ]
    ],

    'Backend Development' => [
        'soft_skills' => [
            [
                'id' => 'backend_soft_1',
                'question' => 'Tell me about a time you had to persuade someone to accept your idea.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Backend Development'
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'backend_tech_1',
                'question' => 'Explain the difference between let, const, and var in JavaScript.',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => 'Backend Development'
            ]
        ]
    ],

    'Frontend Development' => [
        'soft_skills' => [
            [
                'id' => 'frontend_soft_1',
                'question' => 'Tell me about a time you had to manage multiple tasks simultaneously.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Frontend Development'
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'frontend_tech_1',
                'question' => 'What event management tools or software have you used?',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => 'Frontend Development'
            ]
        ]
    ],

    'CEO' => [
        'soft_skills' => [
            [
                'id' => 'ceo_soft_1',
                'question' => 'Tell me about a time you helped a peer understand a difficult concept.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'CEO'
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'ceo_tech_1',
                'question' => 'What academic resources and tools do you use for research and studying?',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => 'CEO'
            ]
        ]
    ],

    'Stock Market' => [
        'soft_skills' => [
            [
                'id' => 'stock_soft_1',
                'question' => 'Tell me about a time you helped a peer understand a difficult concept.',
                'category' => 'Soft Skills',
                'difficulty' => 'Medium',
                'council' => 'Stock Market'
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'stock_tech_1',
                'question' => 'What academic resources and tools do you use for research and studying?',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => 'Stock Market'
            ]
        ]
    ]
];

/**
 * Get questions for a specific council
 */
function getQuestionsForCouncil($councilName)
{
    global $COUNCIL_QUESTIONS;

    if (isset($COUNCIL_QUESTIONS[$councilName])) {
        return $COUNCIL_QUESTIONS[$councilName];
    }

    // default fallback
    return [
        'soft_skills' => [
            [
                'id' => 'default_soft_1',
                'question' => 'Tell me about yourself and why you want to join this council.',
                'category' => 'Soft Skills',
                'difficulty' => 'Easy',
                'council' => $councilName,
            ]
        ],
        'tech_skills' => [
            [
                'id' => 'default_tech_1',
                'question' => 'What skills do you have that are relevant to this council?',
                'category' => 'Tech Skills',
                'difficulty' => 'Easy',
                'council' => $councilName,
            ]
        ]
    ];
}

/**
 * Get all questions combined
 */
function getAllQuestionsForCouncil($councilName)
{
    $questions = getQuestionsForCouncil($councilName);
    return array_merge($questions['soft_skills'], $questions['tech_skills']);
}
