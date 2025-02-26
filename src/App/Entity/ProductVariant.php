<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;
use Sylius\Component\Product\Model\ProductVariantInterface;
use App\Entity\ProductInventory;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_product_variant")
 */ 
class ProductVariant extends BaseProductVariant implements ProductVariantInterface
{
    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\ProductInventory", mappedBy="variant",cascade={"all"})
     */
    protected $inventories;

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\EbayField", mappedBy="variant",cascade={"all"})
     */
    protected $ebayFields;

    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\AmazonField", mappedBy="variant",cascade={"all"})
     */
    protected $amazonFields;
    
    public function __construct()
    {
        parent::__construct();
        $this->inventories = new ArrayCollection;
    }

    public function getInventories(): ?Collection
    {
        return $this->inventories;
    }

    public function setInventories(?Collection $inventories): self
    {
        
        $this->inventories = $inventories;

        return $this;
    }

    public function addInventory(ProductInventory $inventory): void
    {

        foreach(debug_backtrace() as $v){
            ECHO 'file:'.$v['file'].'<br/>';
            ECHO 'function:'.$v['function'].'<br/>';
            ECHO 'line:'.$v['line'].'<br/>';

        }
        if (!$this->hasInventory($inventory)) {
            $this->inventories->add($inventory);
            $inventory->setProductVariant($this);
            $inventory->setProduct($this->getProduct());
        }
    }

    public function removeInventory(ProductInventory $inventory): void
    {
        if ($this->hasInventory($inventory)) {
            $inventory->setProductVariant(null);
            $this->inventories->removeElement($inventory);
        }
    }

    public function hasInventory(ProductInventory $inventory): bool
    {
        return $this->inventories->contains($inventory);
    }

    public function getEbayFields(): ?Collection
    {
        return $this->ebayFields;
    }

    public function setEbayFields(?Collection $ebayFields): self
    {
        
        $this->EbayField = $ebayFields;

        return $this;
    }
    
    public function addEbayField(EbayField $ebayField): void
    {
        if (!$this->hasEbayField($ebayField)) {
            $this->ebayFields->add($ebayField);
            $ebayField->setProductVariant($this);
            $ebayField->setProduct($this->getProduct());
        }
    }

    public function removeEbayField(EbayField $ebayField): void
    {
        if ($this->hasEbayField($ebayField)) {
            $ebayField->setProductVariant(null);
            $this->ebayFields->removeElement($ebayField);
        }
    }

    public function hasEbayField(EbayField $ebayField): bool
    {
        return $this->ebayFields->contains($ebayField);
    }

    public function getAmazonFields(): ?Collection
    {
        return $this->amazonFields;
    }

    public function setAmazonFields(?Collection $amazonFields): self
    {
        
        $this->AmazonFields = $amazonFields;

        return $this;
    }
    public function addAmazonField(AmazonField $amazonField): void
    {
        if (!$this->hasAmazonField($amazonField)) {
            $this->amazonFields->add($amazonField);
            $amazonField->setProductVariant($this);
            $amazonField->setProduct($this->getProduct());
        }
    }

    public function removeAmazonField(AmazonField $amazonField): void
    {
        if ($this->hasAmazonField($amazonField)) {
            $amazonField->setProductVariant(null);
            $this->amazonFields->removeElement($amazonField);
        }
    }

    public function hasAmazonField(AmazonField $amazonField): bool
    {
        return $this->amazonFields->contains($amazonField);
    }
}