<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text", length=100)
     */
    private $title;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="book")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="book")
     */
    private $category; 

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(name="publishedAt", type="date")
     */
    protected $publishedAt;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getId(){
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getAuthor(){
        return $this->author;
    }

    public function setAuthor($author){
        $this->author = $author;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
 
    public function setPrice(float $price): self
    {
        $this->price = $price;
 
        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }
 
    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
 
        return $this;
    }
}
