<?php

namespace App\Services;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class Log
{

    public function __construct() {
        \Debugbar::disable();
    }

    /**
     * make form
     * @access  public
     * @param   array  $input
     * @param   string $guarded
     * @return  object
     * @version 1.0
     * @author William Novak
     */
    public static function save($input, $controller) {
        if ( $controller == null ) {
            return false;
        }

        if ( !in_array($controller, ['enterprise', 'person', 'user']) ) {
            return false;
        }

        switch ($controller) {
            
            case 'enterprise':
                $log = new \App\Models\Log\EnterpriseLog;
            break;

        }

        $log::create($input);

        return true;

    }
}
