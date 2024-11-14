<?php
require_once('../../config.php');
require_admin();

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/auto_saml_cohort/status.php');
$PAGE->set_title('Auto SAML Cohort Status');
$PAGE->set_heading('Auto SAML Cohort Status');

// Processar ação de sincronização
$action = optional_param('action', '', PARAM_ALPHA);
if ($action === 'sync' && confirm_sesskey()) {
    require_once($CFG->dirroot . '/cohort/lib.php');
    
    // Obter configuração
    $cohortid = get_config('local_auto_saml_cohort', 'cohortid');
    $users = $DB->get_records('user', array('auth' => 'saml2'));
    $count = 0;
    
    foreach ($users as $user) {
        try {
            $exists = $DB->record_exists('cohort_members', 
                array('cohortid' => $cohortid, 'userid' => $user->id));
                
            if (!$exists) {
                cohort_add_member($cohortid, $user->id);
                $count++;
            }
        } catch (Exception $e) {
            \core\notification::error("Erro ao processar usuário {$user->username}: " . $e->getMessage());
        }
    }
    
    if ($count > 0) {
        \core\notification::success("$count usuários foram adicionados à coorte.");
    } else {
        \core\notification::info("Nenhum novo usuário precisou ser adicionado à coorte.");
    }
    
    redirect($PAGE->url);
}

echo $OUTPUT->header();

// Verificar configuração
$cohortid = get_config('local_auto_saml_cohort', 'cohortid');
echo "<h3>Configuração</h3>";
echo "<p>Cohort ID configurado: " . ($cohortid ? $cohortid : 'Não configurado') . "</p>";

// Verificar coorte
if ($cohortid) {
    $cohort = $DB->get_record('cohort', array('id' => $cohortid));
    echo "<p>Coorte encontrada: " . ($cohort ? 'Sim - ' . $cohort->name : 'Não') . "</p>";
}

// Verificar usuários SAML2
$saml2users = $DB->get_records('user', array('auth' => 'saml2'));
echo "<h3>Usuários SAML2</h3>";
echo "<p>Total de usuários SAML2: " . count($saml2users) . "</p>";

// Adicionar botão de sincronização
echo "<div class='mb-3'>";
echo html_writer::tag('form', 
    html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey())) .
    html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'action', 'value' => 'sync')) .
    html_writer::tag('button', get_string('syncusers', 'local_auto_saml_cohort'), 
        array('type' => 'submit', 'class' => 'btn btn-primary')),
    array('method' => 'post', 'action' => new moodle_url('/local/auto_saml_cohort/status.php'))
);
echo "</div>";

if ($cohortid && $saml2users) {
    echo "<h3>Usuários na Coorte</h3>";
    echo "<ul>";
    foreach ($saml2users as $user) {
        $ismember = $DB->record_exists('cohort_members', 
            array('cohortid' => $cohortid, 'userid' => $user->id));
        echo "<li>{$user->username}: " . ($ismember ? 'Na coorte' : 'Não está na coorte') . "</li>";
    }
    echo "</ul>";
}

echo $OUTPUT->footer();