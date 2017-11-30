<?php
/**
 * A tasks list data model class that use csv file as persistance.
 */
class Tasks extends XML_Model {
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(APPPATH . '../data/tasks.xml', 'id');
    }
    /**
     * Return all the tasks data ordered by category. 
     */
    function getCategorizedTasks()
    {
        // extract the undone tasks
        foreach ($this->all() as $task)
        {
            if ($task->status != 2)
                $undone[] = $task;
        }
        // substitute the category name, for sorting
        foreach ($undone as $task)
            $task->group = $this->app->group($task->group);
        // order them by category
        usort($undone, "orderByCategory");
        // convert the array of task objects into an array of associative objects       
        foreach ($undone as $task)
            $converted[] = (array) $task;
        return $converted;
    }
    /**
     * Return all the tasks data ordered by category. 
     */
    function getPrioritizedTasks()
    {
        foreach ($this->all() as $task)
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
        return $converted;
    }

    // provide form validation rules
    public function rules() {
        $config = array(
            ['field' => 'task', 'label' => 'TODO task', 'rules' => 'alpha_numeric_spaces|max_length[64]'],
            ['field' => 'priority', 'label' => 'Priority', 'rules' => 'integer|less_than[4]'],
            ['field' => 'size', 'label' => 'Task size', 'rules' => 'integer|less_than[4]'],
            ['field' => 'group', 'label' => 'Task group', 'rules' => 'integer|less_than[5]'],
        );
        return $config;
    }
}
// return -1, 0, or 1 of $a's category name is earlier, equal to, or later than $b's
function orderByCategory($a, $b)
{
    if ($a->group < $b->group)
        return -1;
    elseif ($a->group > $b->group)
        return 1;
    else
        return 0;
}
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
