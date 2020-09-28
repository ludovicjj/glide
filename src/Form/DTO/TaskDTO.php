<?php


namespace App\Form\DTO;


class TaskDTO
{
    public $title;

    public function __construct($title = null)
    {
        $this->title = $title;
    }
}