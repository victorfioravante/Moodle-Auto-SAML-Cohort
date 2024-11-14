<?php
namespace local_auto_saml_cohort;

defined('MOODLE_INTERNAL') || die();

class observer {
    // Função existente para novos usuários
    public static function user_created(\core\event\user_created $event) {
        self::add_user_to_cohort($event->objectid);
    }
    
    // Nova função para login
    public static function user_loggedin(\core\event\user_loggedin $event) {
        self::add_user_to_cohort($event->objectid);
    }
    
    // Função compartilhada para adicionar usuário à coorte
    private static function add_user_to_cohort($userid) {
        global $DB, $CFG;
        
        require_once($CFG->dirroot . '/cohort/lib.php');
        
        $user = \core_user::get_user($userid);
        
        if ($user->auth === 'saml2') {
            $cohortid = get_config('local_auto_saml_cohort', 'cohortid');
            
            if (!empty($cohortid)) {
                try {
                    // Verificar se usuário já está na coorte
                    $exists = $DB->record_exists('cohort_members', 
                        array('cohortid' => $cohortid, 'userid' => $userid));
                    
                    if (!$exists) {
                        // Adicionar à coorte
                        cohort_add_member($cohortid, $userid);
                        
                        // Log da ação
                        $cohort = $DB->get_record('cohort', array('id' => $cohortid));
                        if ($cohort) {
                            $event = \core\event\cohort_member_added::create(array(
                                'context' => \context_system::instance(),
                                'objectid' => $cohort->id,
                                'relateduserid' => $userid,
                            ));
                            $event->trigger();
                        }
                    }
                } catch (\Exception $e) {
                    debugging('Error adding user to cohort: ' . $e->getMessage(), DEBUG_DEVELOPER);
                }
            }
        }
    }
}