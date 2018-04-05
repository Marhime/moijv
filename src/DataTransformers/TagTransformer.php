<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 05/04/2018
 * Time: 12:43
 */

namespace App\DataTransformers;


use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var TagRepository
     */
    private $tagrepo;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagrepo = $tagRepository;
    }

    public function transform($tagCollection)
    {
        // array_map (function, array) transformation d'un array en string
        $tagArray = $tagCollection->toArray();
        $nameArray = [];
        foreach ($tagArray as $tag)
        {
            $nameArray[] = $tag->getName();
        }

//        array_map(function ($tag){ return $tag->getName(); }, $tagArray);

        return implode(',', $nameArray);
    }

    public function reverseTransform($tagString)
    {
        $tagArray = array_unique(explode(',', $tagString));
        $tagCollection = new ArrayCollection();
        foreach ($tagArray as $tagName) {
            $tagCollection->add($this->tagrepo->getCorrespondingTag($tagName));
        }
        return $tagCollection;

    }
}