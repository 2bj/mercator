<?php

namespace App\Events;

use App\Conversation\Flows\AbstractFlow;
use App\Entities\User;
use Illuminate\Queue\SerializesModels;

class FlowRunned
{
    use SerializesModels;

    protected $user;
    protected $flow;
    protected $state;
    protected $options;

    public function __construct(User $user, AbstractFlow $flow, string $state, array $options = [])
    {
        $this->user = $user;
        $this->flow = $flow;
        $this->state = $state;
        $this->options = $options;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFlow(): AbstractFlow
    {
        return $this->flow;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

}
