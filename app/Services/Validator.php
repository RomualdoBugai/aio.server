<?php

namespace App\Services;

/**
 *
 * @author William Novak
 * @version 1.0
 */
class Validator
{

    public $status  = false;

    public $message = [];

    /**
     * validate input data
     * @access  public
     * @param   array  $input
     * @param   array  $rules
     * @param   array  $messages
     * @version 1.0
     * @author  William Novak <williamnvk@gmail.com>
     * @return  object App\Services\Validator
     */
    public function __construct($input, $rules, $messages, $niceNames = [])
    {
        $result['message'] = [];
        $result['fail']  = false;

        if (!is_array($input) || count($input) <= 0)
        {
            $result['message'][] = 'empty or invalid data';
            $result['fail']    = true;
        }

        if (!is_array($rules) || count($rules) <= 0)
        {
            $result['message'][] = 'empty or invalid rules';
            $result['fail']    = true;
        }

        /*
        if (!is_array($messages) || count($messages) <= 0)
        {
            $result['message'][] = 'empty or invalid messates';
            $result['fail']    = true;
        }
        */

        # if params is invalid, then
        if ($result['fail'] == true)
        {
            $this->status  = false;
            $this->message = $result['message'];
        } else {
            # else params is valid, then
            $validation = \Validator::make($input, $rules, $messages);
            if (count($niceNames) > 0)
            {
                $validation->setAttributeNames($niceNames);
            }
            $this->status  = (boolean) ( $validation->fails() === true ? false : true );
            $this->message = (array)   $validation->errors()->all();
        }
        return;
    }
}
