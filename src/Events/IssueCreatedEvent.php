<?php

namespace Azuriom\Plugin\Jirai\Events;


use Azuriom\Plugin\Jirai\Models\JiraiIssue;

class IssueCreatedEvent
{

    /**
     * @var JiraiIssue
     */
    public $issue;

    public function __construct(JiraiIssue $issue)
    {
        $this->issue = $issue;
    }

}
