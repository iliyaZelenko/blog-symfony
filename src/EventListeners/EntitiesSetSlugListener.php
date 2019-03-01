<?php

namespace App\EventListeners;

use App\Entity\Resources\SluggableInterface;
use App\Exceptions\AppException;
use App\Utils\Contracts\Slugger\SluggerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
//use Doctrine\ORM\Event\PreFlushEventArgs;

class EntitiesSetSlugListener
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }

    # TODO same for preUpdate
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof SluggableInterface) {
            // slug attribute => property attribute
            $attr = $entity->getSlugAttributes();

            // ставит slug attribute для каждого property attribute
            foreach ($attr as $slugAttr => $propAttr) {
                // example for "slug": "setSlug"
                $methodSetSlug = 'set' . ucfirst($slugAttr);
                // example for "title": "getTitle"
                $methodGetProp = 'get' . ucfirst($propAttr);

                // TODO проверять изменился ли аттрибут
                foreach ([$methodSetSlug, $methodGetProp] as $method) {
                    if (!method_exists($entity, $method)) {
                        throw new AppException(
                            "Please, add a \"$method\" method to the " . get_class($entity) . ' class.'
                        );
                    }

                    $slug = $this->slugger->slugify(
                        $entity->{$methodGetProp}()
                    );
                    $entity->{$methodSetSlug}($slug);
                }
            }
        }
    }
}
