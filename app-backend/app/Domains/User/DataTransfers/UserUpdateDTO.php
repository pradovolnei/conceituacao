<?php
namespace App\Domains\User\DataTransfers;


class UserUpdateDTO
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $email;


    public static function create(array $values): self
    {
        $dto = new self();

        foreach ($values as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }

        return $dto;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
