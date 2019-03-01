<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

// TODO @Gedmo\Tree(type="materializedPath")
// @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")

/**
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeLeft()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lft;

    /**
     * @Gedmo\TreeRight()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $depth;

    /**
     * @Gedmo\TreeRoot()
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent()
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", orphanRemoval=true)
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(self $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

// Это не помогло
//    /**
//     * @return mixed
//     */
//    public function getLft()
//    {
//        return $this->lft;
//    }
//
//    /**
//     * @param mixed $lft
//     */
//    public function setLft($lft): void
//    {
//        $this->lft = $lft;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getRgt()
//    {
//        return $this->rgt;
//    }
//
//    /**
//     * @param mixed $rgt
//     */
//    public function setRgt($rgt): void
//    {
//        $this->rgt = $rgt;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getDepth()
//    {
//        return $this->depth;
//    }
//
//    /**
//     * @param mixed $depth
//     */
//    public function setDepth($depth): void
//    {
//        $this->depth = $depth;
//    }
}
