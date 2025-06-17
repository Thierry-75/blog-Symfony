<?php

namespace App\Message;

final class SendEmailNotification
{
private $from;
private $to;
private $subject;
private $template;
private $context;

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $template
     * @param $context
     */
    public function __construct($from, $to, $subject, $template, $context)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }




}
