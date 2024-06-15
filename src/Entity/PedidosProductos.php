<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pedidos_productos')]
class PedidosProductos
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Pedidos::class, inversedBy: 'pedidosProductos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pedidos $pedido = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Productos::class, inversedBy: 'pedidosProductos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Productos $producto = null;

    #[ORM\Column]
    private ?int $unidades = null;

    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }

    public function setPedido(?Pedidos $pedido): static
    {
        $this->pedido = $pedido;
        return $this;
    }

    public function getProducto(): ?Productos
    {
        return $this->producto;
    }

    public function setProducto(?Productos $producto): static
    {
        $this->producto = $producto;
        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): static
    {
        $this->unidades = $unidades;
        return $this;
    }
}
