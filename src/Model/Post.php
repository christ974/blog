<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post
{
    private $id;
    private $slug;
    private $name;
    private $content;
    private $create_at;
    private $categories = [];

    public function getName(): ?string//récup le nom
    {
        return $this->name;
    }
    
    public function getFormatedContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }
    public function getExcerpt(): ?string//récup l'extrait
    {
        if($this->content === null)
        {
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }

    public function getCreateAt() : DateTime
    {
        return new DateTime($this->create_at);
    }

    public function getSlug(): ?string{
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


}