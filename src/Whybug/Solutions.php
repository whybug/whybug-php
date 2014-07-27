<?php
namespace Whybug;

class Solutions {

    protected $solutions;

    /**
     * @param Solution[] $solutions
     */
    public function __construct(array $solutions)
    {
        $this->solutions = $solutions;
    }

    /**
     * @param string $response
     *
     * @return Solutions
     */
    public static function fromResponse($response)
    {
        if (!$response) {
            return new self(array());
        }

        $data = json_decode($response, true);

        if (empty($data['solutions'])) {
            $data['solutions'] = array();
        }

        return new self(array_map('\Whybug\Solution::fromArray', $data['solutions']));
    }

    /**
     * @return Solution[]
     */
    public function getSolutions()
    {
        return $this->solutions;
    }
}
