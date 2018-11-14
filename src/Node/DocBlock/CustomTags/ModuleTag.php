<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\CustomTags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

class ModuleTag extends BaseTag implements StaticMethod
{
    /**
     * @var string
     */
    protected $name = 'module';

    /**
     * @param \phpDocumentor\Reflection\DocBlock\Description|null $description
     */
    public function __construct(?Description $description = null)
    {
        $this->description = $description;
    }

    /**
     * @param string $body
     * @param \phpDocumentor\Reflection\DocBlock\DescriptionFactory|null $descriptionFactory
     * @param \phpDocumentor\Reflection\Types\Context|null $context
     *
     * @return \ArchitectureSniffer\Node\DocBlock\CustomTags\ModuleTag
     */
    public static function create($body, ?DescriptionFactory $descriptionFactory = null, ?Context $context = null): self
    {
        Assert::notNull($descriptionFactory);

        return new static($descriptionFactory->create($body, $context));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->description;
    }
}
