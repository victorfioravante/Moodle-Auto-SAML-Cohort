<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    // Adicionar página de configurações
    $settings = new admin_settingpage('local_auto_saml_cohort_settings', 
        get_string('pluginname', 'local_auto_saml_cohort'));
    
    // Adicionar ao menu de plugins locais
    $ADMIN->add('localplugins', $settings);
    
    // Adicionar seleção de coorte
    $cohorts = $DB->get_records_menu('cohort', array(), 'name ASC', 'id, name');
    $settings->add(new admin_setting_configselect(
        'local_auto_saml_cohort/cohortid',
        get_string('selectcohort', 'local_auto_saml_cohort'),
        get_string('selectcohort_desc', 'local_auto_saml_cohort'),
        '',
        $cohorts
    ));

    // Adicionar link para página de status
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_auto_saml_cohort_status',
        get_string('status', 'local_auto_saml_cohort'),
        new moodle_url('/local/auto_saml_cohort/status.php')
    ));
}