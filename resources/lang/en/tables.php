<?php

// A table with nothing to show because of a search or filters says so, and
// offers the way back. Override in lang/vendor/kinetics/{locale}/tables.php.
return [

    'empty' => [

        'search' => [
            'heading' => 'No results for “:search”',
            'description' => 'Check the spelling, or try a different word.',
        ],

        'filters' => [
            'heading' => 'No :model match these filters',
            'description' => 'Try changing the filters, or clear them.',
        ],

        'actions' => [
            'clear_search' => 'Clear search',
            'clear_filters' => 'Clear filters',
            'clear_search_and_filters' => 'Clear search and filters',
        ],

    ],

];
