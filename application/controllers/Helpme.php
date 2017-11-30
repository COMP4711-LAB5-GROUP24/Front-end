<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Helpme extends Application
{

    public function index() {
        $this->data['pagebody'] = 'homepage';
        /*
        $this->data['pagetitle'] = 'Help Wanted!';
        $stuff = file_get_contents('../data/jobs.md');
        $htmlstuff = $this->parsedown->parse($stuff);
        
        $this->data['content'] = $this->parser->parse_string($htmlstuff,$parameters,true);
        */
        $this->data['pagetitle'] = 'Help Wanted!';
        $stuff = file_get_contents('../data/jobs.md');
        $this->data['content'] = $this->parsedown->parse($stuff);
        $this->render(); 

    }

}
?>
