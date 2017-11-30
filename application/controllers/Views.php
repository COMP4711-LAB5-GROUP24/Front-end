<?php class Views extends Application
{

    public function index()
    {
        $this->data['pagetitle'] = 'Ordered TODO List';
        //$tasks = $this->tasks->all();   // get all the tasks
        $this->data['content'] = 'Ok'; // so we don't need pagebody
        $this->data['leftside'] = $this->makePrioritizedPanel();
        $this->data['rightside'] = $this->makeCategorizedPanel();
        //$this->data['leftside'] = 'by_priority';
        //$this->data['rightside'] = 'by_category';

        $this->render('template_secondary'); 
    }


    /* 
     *  Approach 1: obtain all the tasks data from the model and prioritize them in 
     *  the controller. Replaced by approach 2. 
     
     function makePrioritizedPanel($tasks) {
        //$parms = ['display_tasks' => []];
        foreach ($tasks as $task)
        {
            if ($task->status != 2)
                $undone[] = $task;
        }
        // order them by priority
        usort($undone, "orderByPriority");

        foreach ($undone as $task)
            $task->priority = $this->app->priority($task->priority);

        foreach ($undone as $task)
            $converted[] = (array) $task;

        // and then pass them on
        $parms = ['display_tasks' => $converted];
        return $this->parser->parse('by_priority', $parms, true);
    }
     */



    /**
     * Generate the tasks panel with the given prioritized tasks data. 
     */
    function makePrioritizedPanel() 
    {
        $parms = ['display_tasks' => $this->tasks->getPrioritizedTasks()];
        $role = $this->session->userdata('userrole');
        $parms['completer'] = ($role == ROLE_OWNER) ? '/views/complete' : '#';
        
        return $this->parser->parse('by_priority', $parms, true);   
    }

    
    function makeCategorizedPanel()
    {
        $parms = ['display_tasks' => $this->tasks->getCategorizedTasks()];
        return $this->parser->parse('by_category', $parms, true);
    }
    function complete() {
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER) redirect('/views');
    
        // loop over the post fields, looking for flagged tasks
        foreach($this->input->post() as $key=>$value) {
            if (substr($key,0,4) == 'task') {
                // find the associated task
                $taskid = substr($key,4);
                $task = $this->tasks->get($taskid);
                $task->status = 2; // complete
                $this->tasks->update($task);
            }
        }
        $this->index();
    }

}

/*
 *  Approach 1: obtain all the tasks data from the model and prioritize them in 
 *  the controller. Replaced by approach 2. 
 *
    // return -1, 0, or 1 of $a's priority is higher, equal to, or lower than $b's
    function orderByPriority($a, $b)
    {
        if ($a->priority > $b->priority)
            return -1;
        elseif ($a->priority < $b->priority)
            return 1;
        else
            return 0;
    }
 */



?>

