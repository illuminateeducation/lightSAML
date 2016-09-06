<?php

/*
 * This file is part of the LightSAML-Core package.
 *
 * (c) Milos Tomic <tmilos@lightsaml.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace LightSaml\Provider\EntityDescriptor;

use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Model\Metadata\EntityDescriptor;

class XmlEntityDescriptorProvider implements EntityDescriptorProviderInterface
{
    /** @var string */
    private $xmlcontent;

    /** @var EntityDescriptor|null */
    private $entityDescriptor;

    /**
     * @param $filecontent
     */
    public function __construct($filecontent)
    {
        $this->xmlcontent = $filecontent;
    }

    /**
     * @return EntityDescriptor
     */
    public function get()
    {
        if (null == $this->entityDescriptor) {
            $this->entityDescriptor = new EntityDescriptor();
            $deserializationContext = new DeserializationContext();
            $deserializationContext->getDocument()->loadXML($this->xmlcontent);
            $this->entityDescriptor->deserialize($deserializationContext->getDocument()->firstChild, $deserializationContext);
        }

        return $this->entityDescriptor;
    }
}
