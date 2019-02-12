<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YsSequence
 *
 * @ORM\Table(name="qy_sequence")
 * @ORM\Entity
 */
class YsSequence
{
    /**
     * @var int
     *
     * @ORM\Column(name="current_val", type="integer", nullable=false, options={"default"="10000"})
     */
    private $currentVal = '10000';

    /**
     * @var int
     *
     * @ORM\Column(name="increment_val", type="integer", nullable=false, options={"default"="1"})
     */
    private $incrementVal = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="seq_name", type="string", length=50)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $seqName;



    /**
     * Set currentVal.
     *
     * @param int $currentVal
     *
     * @return YsSequence
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
     * @return YsSequence
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
