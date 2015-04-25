<?php

namespace MediaOrganizer;

/**
 * Interface Visitable
 * @package MediaOrganizer
 */
interface Visitable
{

    /**
     * Accept a visitor
     *
     * @param FileVisitor $visitor
     * @return mixed
     */
    public function accept(FileVisitor $visitor);
}
