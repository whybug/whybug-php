<?php
namespace Adri\Wtf;

class Solution
{
    protected $upVotes;
    protected $downVotes;
    protected $description;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->replaceVariables($this->description);
    }

    public static function fromArray(array $data)
    {
        $solution = new self;
        $solution->description = $data['description'];
        $solution->upVotes = $data['upVotes'];
        $solution->downVotes = $data['downVotes'];
    }

    protected function replaceVariables($description)
    {
        // find variables
        // replace variables
        // warn if unknown variables
        return $description;
    }

}
