<?php
define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/cohort/lib.php');

// Obter último usuário SAML2
$user = $DB->get_record('user', array('auth' => 'saml2'), '*', IGNORE_MULTIPLE);

if ($user) {
    echo "Testando com usuário: " . $user->username . "\n";
    $observer = new \local_auto_saml_cohort\observer();
    
    $event = \core\event\user_created::create(array(
        'objectid' => $user->id,
        'context' => \context_system::instance(),
        'other' => array('username' => $user->username)
    ));
    
    $observer::user_created($event);
} else {
    echo "Nenhum usuário SAML2 encontrado\n";
}