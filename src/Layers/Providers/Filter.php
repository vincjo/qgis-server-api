<?php
namespace Sigapp\Layers\Providers;

class Filter
{
    /**
     * @param string $serverside    SQL filter set in QGIS project
     * @param string $clientside    WMS / WFS user filter set in request params
     * @param boolean $condition    true if both filters come after a WHERE clause
     */
    public function __construct(?string $serverside, ?string $clientside, bool $condition = false)
    {
        $this->condition = $condition;
        $this->serverside = $this->escapeString($serverside);
        $this->clientside = $this->escapeString($clientside);
    }

    public function get() 
    {
        return $this->check()->compile();
    }

    private function escapeString($string)
    {
        return '(' . preg_replace("#\n|\t|\r#"," ", $string) . ')';
    }

    public function check()
    {
        if (strlen($this->serverside) <= 3) {
            $this->serverside = false;
        }
        if (strlen($this->clientside) <= 3) {
            $this->clientside = false;
        }
        return $this;
    }

    public function compile()
    {
        if (!$this->serverside && !$this->clientside) {
            return null;
        } 
        else {
            $clause = ($this->condition) ? " AND " : " WHERE ";
        }
        if ($this->serverside && $this->clientside) {
           return $clause . $this->serverside . " AND " . $this->clientside;
        } 
        elseif ($this->serverside && !$this->clientside) {
            return $clause . $this->serverside;
        }
        else {
            return $clause . $this->clientside;
        }
    }
}