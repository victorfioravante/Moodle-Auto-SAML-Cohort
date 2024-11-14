<?php
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\core\event\user_created',
        'callback' => '\local_auto_saml_cohort\observer::user_created',
        'internal' => false
    ),
    array(
        'eventname' => '\core\event\user_loggedin',
        'callback' => '\local_auto_saml_cohort\observer::user_loggedin',
        'internal' => false
    )
);