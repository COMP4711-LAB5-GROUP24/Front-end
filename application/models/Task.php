<?php
class Task extends Entity {
    protected $task;
    protected $priority = 1;
    protected $size = 1;
    protected $group = 1;
    protected $deadline;
    protected $status = 1;
    protected $flag = 1;


    public function setTask($task)
    {
        if (!isValidTask($task))
            return false;
        $this->task = $task;
        return true;
    }

    public function setPriority($priority)
    {
        if (!isValidPriority($priority))
            return false;
        $this->priority = $priority;
        return true;
    }

    public function setSize($size)
    {
        if (!isValidSize($size))
            return false;

        $this->size = $size;
        return true;
    }

    public function setGroup($group)
    {
        if (!isValidGroup($group))
            return false;
        $this->group = $group;
        return true;
    }

}

/* property validation functions */
function isValidTask($task)
{
    $pattern = '/^[a-z0-9 \-_]{1,64}$/i';
    return preg_match($pattern, $task);
}

function isValidPriority($priority)
{
    return is_int($priority) && $priority > 0 && $priority < 4; 
}

function isValidSize($size)
{
    return is_int($size) && $size > 0 && $size < 4; 
}

function isValidGroup($group)
{
    return is_int($group) && $group > 0 && $group < 5;
}
