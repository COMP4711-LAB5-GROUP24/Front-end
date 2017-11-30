<?php
if(!class_exists('PHPUnit_Framework_TestCase')) {
  class_alias('PHPUnit\Framework\TestCase','PHPUnit_Framework_TestCase');
}
/* run phpunit from command line directly */
 class TaskListTest extends PHPUnit_Framework_TestCase
  {
    private $CI;
    private $t;
    //only CI setup goes here
    public function setUp()
    {
      // Load CI instance normally
      $this->CI = &get_instance();
    }
    public function testMoreUncompletedTasks()
    {
      $undone = 0;
      $done = 0;
      foreach ($this->CI->tasks->all() as $task)
      {
        if ($task->status != 2)
          $undone++;
        else 
          $done++;
      }
      $this->assertTrue($done < $undone);
    }
  }