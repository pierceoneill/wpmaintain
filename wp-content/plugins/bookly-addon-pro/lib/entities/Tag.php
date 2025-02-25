<?php
namespace BooklyPro\Lib\Entities;

use Bookly\Lib;

class Tag extends Lib\Base\Entity
{
    /** @var string */
    protected $tag;
    /** @var int */
    protected $color_id = 0;

    protected static $table = 'bookly_tags';

    protected static $schema = array(
        'id' => array( 'format' => '%d' ),
        'tag' => array( 'format' => '%s' ),
        'color_id' => array( 'format' => '%d' ),
    );

    /**************************************************************************
     * Entity Fields Getters & Setters                                        *
     **************************************************************************/

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     *
     * @return $this
     */
    public function setTag( $tag )
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getColorId()
    {
        return $this->color_id;
    }

    /**
     * @param int|null $color_id
     *
     * @return $this
     */
    public function setColorId( $color_id )
    {
        $this->color_id = $color_id;

        return $this;
    }

    /**************************************************************************
     * Overridden Methods                                                     *
     **************************************************************************/
}