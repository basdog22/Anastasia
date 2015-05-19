<?php namespace MaddHatter\LaravelFullcalendar;

use DateTime;

/**
 * Class SimpleEvent
 *
 * Simple DTO that implements the Event interface
 *
 * @package MaddHatter\LaravelFullcalendar
 */
class SimpleEvent implements Event
{

    /**
     * @var
     */
    public $title;

    public $description;

    /**
     * @var bool
     */
    public $isAllDay;

    /**
     * @var DateTime
     */
    public $start;

    /**
     * @var DateTime
     */
    public $end;
    public $complete;
    public $id;

    /**
     * @param string          $title
     * @param string          $description
     * @param bool            $isAllDay
     * @param string|DateTime $start If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param string|DateTime $end   If string, must be valid datetime format: http://bit.ly/1z7QWbg
     */
    public function __construct($title,$description, $isAllDay, $start, $end,$complete,$id)
    {
        $this->title    = $title;
        $this->description    = $description;
        $this->isAllDay = $isAllDay;
        $this->complete = $complete;
        $this->start    = $start instanceof DateTime ? $start : new DateTime($start);
        $this->end      = $start instanceof DateTime ? $end : new DateTime($end);
        $this->id      = $id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return $this->isAllDay;
    }

    public function isComplete()
    {
        return $this->complete;
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }
}