<?php

namespace App\Yszc\AdminBundle\Entity;

/**
 * MySequence
 */
class MySequence
{
    /**
     * @var int
     */
    private $currentVal = '10000';

    /**
     * @var int
     */
    private $incrementVal = '1';

    /**
     * @var string
     */
    private $seqName;


    /**
     * Set currentVal.
     *
     * @param int $currentVal
     *
     * @return MySequence
     */
    public function setCurrentVal($currentVal)
    {
        $this->currentVal = $currentVal;

        return $this;
    }

    /**
     * Get currentVal.
     *
     * @return int
     */
    public function getCurrentVal()
    {
        return $this->currentVal;
    }

    /**
     * Set incrementVal.
     *
     * @param int $incrementVal
     *
     * @return MySequence
     */
    public function setIncrementVal($incrementVal)
    {
        $this->incrementVal = $incrementVal;

        return $this;
    }

    /**
     * Get incrementVal.
     *
     * @return int
     */
    public function getIncrementVal()
    {
        return $this->incrementVal;
    }

    /**
     * Get seqName.
     *
     * @return string
     */
    public function getSeqName()
    {
        return $this->seqName;
    }
}
