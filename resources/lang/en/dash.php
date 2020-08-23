<?php

return [
    'national_data'                => 'National data',
    'regions'                      => 'Regions',
    'date'                         => 'Date',
    'ill'                          => 'Currently infected',
    'healed'                       => 'Healed',
    'dead'                         => 'Dead',
    'ill_pro_capite'               => 'Currently infected per 10000 citizens',
    'infected_pro_capite'          => 'Infected per 10000 citizens',
    'dead_pro_capite'              => 'Dead per 10000 citizens',
    'tested_pro_capite'     => 'Tested cases per 10000 citizens',
    'infected'                     => 'Infected (tot.)',
    'new_ill'                      => 'New currently infected people',
    'new_infected'                 => 'New infected people',
    'new_weighted_infected'        => 'New infected people per test',
    'hospitalized_home'            => 'Quarantined at home',
    'hospitalized'                 => 'Hospitalized (total)',
    'hospitalized_light'           => 'Hospitalized (light symptoms)',
    'hospitalized_severe'          => 'Hospitalized (intensive care)',
    'tests'                 => 'Performed tests',
    'tested'                       => 'Tested cases',
    'lethality'                    => 'Lethality',
    'lethality_note'               => 'Note - The lethality depends on the probably underestimated number of infected people. Handle it with care. And wash your hands after doing it.',
    'emergency_phone_note'         => 'The emergency phone number for this region is: :phone',
    'ill_healed_dead'              => 'Infected, healed, dead',
    'ill_variations'               => 'Variations',
    'ill_variations_note'          => 'New currently infected people are the new infected people minus the new infected and dead people',
    'ill_weighted_variations'      => 'Nuovi infetti (weighted on tests number)',
    'change_scale'                 => 'Change scale',
    'ill_by_severity'              => 'Infected people grouped by hospitalization',
    'ill_by_severity_lines'        => 'Infected people grouped by hospitalization - trend',
    'data'                         => 'Data',
    'data_source'                  => 'Data source: :source.',
    'created_backed'               => 'Created by :created  and backed by :backed.',
    'git_repos'                    => 'This is a Open Source website and <a href=":url">it is available on GitHub</a>. You can <a href=":url/issues">report issues here</a>.',
    'notices'                      => [
        'partial_data' => 'Partial data for region: :region',
        'no_data'      => 'No data for region: :region',
    ],
    'last_update'                  => 'Last update: :date',
    'datetime_format'              => 'm/d/Y H:i',
];
