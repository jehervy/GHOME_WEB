<?php

namespace GHOME\CoreBundle\Form\DataTransformer;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms entities into choice keys.
     *
     * @param object $entity A collection of entities, a single entity or NULL
     * @return mixed An single key or NULL
     */
    public function reverseTransform($entity)
    {
        var_dump($entity);
        if (null === $entity || '' === $entity)
        {
            return '';
        }

        if (!is_object($entity))
        {
            throw new UnexpectedTypeException($entity, 'object');
        }

        if (is_array($entity))
        {
            throw new \InvalidArgumentException('Expected an object, but got a collection. Did you forget to pass "multiple=true" to an entity field?');
        }

        return $entity->getId();
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param  mixed $key   A single key or NULL
     * @return array  A collection of entities or NULL
     */
    public function transform($key)
    {
        if ('' === $key || null === $key)
        {
            return null;
        }
        
        if (!($entity = $this->manager->find($key)))
        {
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $key));
        }

        return $entity;
    }
}