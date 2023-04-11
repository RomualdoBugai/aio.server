<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'O :attribute precisa ser \'true\' ou \'false\'.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'O :attribute não é uma data válida.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'O :attribute precisa conter :digits digitos.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'O :attribute não é um endereço de e-mail válido.',
    'exists'               => ':attribute não encontrado.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'Valor não esperado.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'O :attribute precisa ser do tipo númerico e inteiro.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'O :attribute não pode ser maior que :max.',
        'file'    => 'O :attribute não pode ser maior que :max kilobytes.',
        'string'  => 'O :attribute não pode ser maior que :max caracteres.',
        'array'   => 'O :attribute não pode ser maior que :max itens.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute precisa ser no mínimo :min.',
        'file'    => 'The :attribute precisa ser no mínimo :min kilobytes.',
        'string'  => 'The :attribute precisa ter no mínimo :min caracteres.',
        'array'   => 'The :attribute precisa ter no mínimo :min itens.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'O :attribute precisa ser numérico.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'O campo :attribute é obrigatório.',
    'required_if'          => 'O campo :attribute é obrigatório se o campo :other for :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'O :attribute precisa ser do tipo texto.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'O :attribute já está sendo usado.',
    'url'                  => 'O :attribute precisa ser no formado de uma URL, ex.: http://exemplo.com.br/',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'user'          => 'usuário',
        'user_id'       => 'id do usuário',
        'enterprise'    => 'empresa',
        'enterprise_id' => 'empresa',
        'email'         => 'e-mail',
        'password'      => 'senha',
        'name'          => 'nome',
        'app'           => 'aplicativo',
        'startTrial'    => 'iniciar período de testes',
        'localSession'  => 'sessão local',
        'fantasy_name'  => 'nome fantasia',
        'is_matrix'     => 'matriz',
        'legal_nature'  => 'natureza jurídica',
        'last_update'   => 'última atualização',
        'is_active'     => 'atívo',
        'status'        => 'situação',
    ],

];
