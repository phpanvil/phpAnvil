<?php

return array(
    'environment' => array(
        'type'  => 1,
        'name'  => 'Development',
        'label' => array(
            'name' => 'dev',
            'type' => 1
        )
    ),

    'timezone'    => 'UTC',

    'html'        => array(
        'iconFilename' => 'favicon.ico'
    ),

    'security'    => array(
        'session'  => array(
            'minutesTimeout' => 30
        ),
        'password' => array(
            'minLength'            => 8,
            'maxLength'            => 20,
            'minUppercase'         => 1,
            'minLowercase'         => 1,
            'minNumbers'           => 1,
            'minSymbols'           => 1,
            'symbols'              => '$%&!?',
            'maxRepeat'            => 1,
            'daysExpire'           => 90,
            'daysExpireWarning1'   => 7,
            'daysExpireWarning2'   => 3,
            'daysExpireWarning3'   => 1,
            'maxAttempts1'         => 5,
            'minutesDisabled1'     => 15,
            'maxAttempts2'         => 5,
            'minutesDisabled2'     => 30,
            'maxAttempts3'         => 15,
            'minutesDisabled3'     => 0,
            'maxHistory'           => 3,
            'daysInnactiveDisable' => 120
        )
    )
);
