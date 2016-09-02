<?php

/*
 * This file is part of the LightSAML-Core package.
 *
 * (c) Milos Tomic <tmilos@lightsaml.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LightSaml\Store\EntityDescriptor;

use LightSaml\Error\LightSamlXmlException;
use LightSaml\Model\Metadata\EntitiesDescriptor;
use LightSaml\Model\Metadata\EntityDescriptor;

class XmlEntityDescriptorStore implements EntityDescriptorStoreInterface
{
    /**
     * @var string
     */
    private $xmlcontent;

    /** @var EntityDescriptor|EntitiesDescriptor */
    private $object;

    /**
     * @param string $filename
     */
    public function __construct($content)
    {
        $this->xmlcontent = $content;
    }

    /**
     * @param string $entityId
     *
     * @return EntityDescriptor|null
     */
    public function get($entityId)
    {
        if (null == $this->object) {
            $this->load();
        }

        if ($this->object instanceof EntityDescriptor) {
            if ($this->object->getEntityID() == $entityId) {
                return $this->object;
            } else {
                return null;
            }
        } else {
            return $this->object->getByEntityId($entityId);
        }
    }

    /**
     * @param string $entityId
     *
     * @return bool
     */
    public function has($entityId)
    {
        return $this->get($entityId) != null;
    }

    /**
     * @return array|EntityDescriptor[]
     */
    public function all()
    {
        if (null == $this->object) {
            $this->load();
        }

        if ($this->object instanceof EntityDescriptor) {
            return [$this->object];
        } else {
            return $this->object->getAllEntityDescriptors();
        }
    }

    private function load()
    {
		try {
			$this->object = EntitiesDescriptor::loadXml($this->xmlcontent);
		} catch (LightSamlXmlException $ex) {
			$this->object = EntityDescriptor::loadXml($this->xmlcontent);
		}
    }
}

