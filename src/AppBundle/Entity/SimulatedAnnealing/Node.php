<?php


namespace AppBundle\Entity\SimulatedAnnealing;


class Node
{
    private $solution;
    public static $visited;

    /**
     * Node constructor.
     * @param $solution
     */
    public function __construct(Solution $solution)
    {
        $this->solution = $solution;
    }


    public function generateNeighbours(){
        $neighbours = array();
        $assistantIndex = 0;
        foreach($this->solution->getAssistants() as $assistant){
            $schoolIndex = 0;
            foreach($this->solution->getSchools() as $school){
                foreach($school->getCapacity() as $day=>$capacity){

                    //Check if the school has capacity for more assistants
                    $cap = array_key_exists($day, $school->getAssistants()) ? sizeof($school->getAssistants()[$day]) : 0;
                    $freeCapacity = $capacity - $cap;
                    if($freeCapacity < 1) continue;

                    //Create a deep copy of the current solution
                    $sTime = round(microtime(true) * 1000);
                    $schoolsCopy = $this->solution->deepCopySchools();
                    dump("Deep copy school time: ".((round(microtime(true) * 1000) - $sTime)/1000));
                    $assistantsCopy = $this->solution->deepCopyAssistants($schoolsCopy);
                    $newSolution = new Solution($schoolsCopy);
                    $newSolution->setAssistants($assistantsCopy);

                    //Move the assistant from current school to a new school and add the new solution to the neighbour list
                    $newSolution->moveAssistant($assistantsCopy[$assistantIndex], $schoolsCopy[$schoolIndex], $assistant->getAssignedDay(), $day);
                    $node = new Node($newSolution);
                    $neighbours[] = $node;
                    // TODO: Filter out bad nodes to improve efficiency
                }
                $schoolIndex++;
            }
            $assistantIndex++;
        }
        return $neighbours;
    }

    /**
     * @return Solution
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * @param Solution $solution
     */
    public function setSolution($solution)
    {
        $this->solution = $solution;
    }


}