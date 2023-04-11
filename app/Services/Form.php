<?php

namespace App\Services;

/**
 *
 * @author William Novak
 * @version 1.1
 */
class Form
{

    public function __construct()
    {
        \Debugbar::disable();
    }

    /**
     * make form
     * @access  public
     * @param   array  $fillable
     * @param   array  $guarded
     * @param   array  $data
     * @return  object
     * @version 1.1
     * @author William Novak
     */
    public static function make($fillable, $guarded, $controller, $input)
    {
        $form   = [];
        foreach($fillable as $field) {
            $form[$field] = [
                'visible'   =>  true,
                'label'     =>  (string) message($controller, "form.attributes." . $field),
                'value'     =>  ( isset($input[$field]) ? $input[$field] : null )
            ];
        }
        unset($field);
        foreach($guarded as $field) {
            $form[$field] = [
                'visible'   =>  false,
                'label'     =>  (string) message($controller, "form." . $field),
                'value'     =>  ( isset($input[$field]) ? $input[$field] : null )
            ];
        }
        unset($field);
        $form['submit'] = ['value' => (string) message($controller, "form.submit")];
        return $form;
    }
}
