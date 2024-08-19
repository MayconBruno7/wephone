<?php

class Ambiente
{
    /**
     * Load
     *
     * @return void
     */
    public function Load()
    {
        // analisa e carrega o conteúdo do arquivo .env em um array
        $content = parse_ini_file('..' . DS . '.env', true);

        foreach ($content as $key => $value) {
            if (gettype($content[$key]) != 'array') {
                $_ENV[$key] = $value;
            }
        }

        $_ENV['ENVIRONMENT'] = $content['ENVIRONMENT'];

        // Carrega as configurações de ambiente

        foreach ($content[$content['ENVIRONMENT']] as $key => $value) {
            $_ENV[$key] = $value;
        }

        return null;
    }
}