<?php

namespace NikhilPandey\Esewa;

class Esewa
{
    protected $id;
    protected $amount;
    protected $referenceId;

    public function with(array $params)
    {
        $this->id = isset($params['id']) ? $params['id'] : null;
        $this->amount = isset($params['amount']) ? $params['amount'] : null;
        $this->referenceId = isset($params['reference_id']) ? $params['reference_id'] : null;

        return $this;
    }

    public function isPaid()
    {
        return false;
    }
}
