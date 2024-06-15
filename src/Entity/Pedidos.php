<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $enviado = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurantes $restaurante = null;

    /**
     * @var Collection<int, Productos>
     */
    #[ORM\ManyToMany(targetEntity: Productos::class, inversedBy: 'pedidos')]
    private Collection $productos;

    public function __construct()
    {
        $this->productos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEnviado(): ?int
    {
        return $this->enviado;
    }

    public function setEnviado(int $enviado): static
    {
        $this->enviado = $enviado;

        return $this;
    }

    public function getRestaurante(): ?Restaurantes
    {
        return $this->restaurante;
    }

    public function setRestaurante(?Restaurantes $restaurante): static
    {
        $this->restaurante = $restaurante;

        return $this;
    }

    /**
     * @return Collection<int, Productos>
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(Productos $producto): static
    {
        if (!$this->productos->contains($producto)) {
            $this->productos->add($producto);
        }

        return $this;
    }

    public function removeProducto(Productos $producto): static
    {
        $this->productos->removeElement($producto);

        return $this;
    }
}
