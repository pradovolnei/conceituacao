<?php
namespace Tests\Mockers;

class UserMocker
{
    public static function getOnlyUser(): object
    {
        return (object) [
            "id" => 7,
            "name" => "Admin User",
            "email" => "admin@admin.com.br",
            "email_verified_at" => null,
            "created_at" => "2025-03-20T02:46:48.000000Z",
            "updated_at" => "2025-03-20T02:46:48.000000Z",
            "profiles" => [
                [
                    "id" => 1,
                    "name" => "Administrador",
                    "created_at" => "2025-03-20T01:00:23.000000Z",
                    "updated_at" => "2025-03-20T01:00:23.000000Z",
                    "pivot" => [
                        "user_id" => 7,
                        "profile_id" => 1
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getUsersWithPaginate(): array
    {
        return [
            'current_page' => 1,
            'data' => [
                "id" => 7,
                "name" => "Admin User",
                "email" => "admin@admin.com.br",
                "email_verified_at" => null,
                "created_at" => "2025-03-20T02:46:48.000000Z",
                "updated_at" => "2025-03-20T02:46:48.000000Z",
                "profiles" => [
                    [
                        "id" => 1,
                        "name" => "Administrador",
                        "created_at" => "2025-03-20T01:00:23.000000Z",
                        "updated_at" => "2025-03-20T01:00:23.000000Z",
                        "pivot" => [
                            "user_id" => 7,
                            "profile_id" => 1
                        ]
                    ]
                ],
                [
                    "id" => 7,
                    "name" => "Admin Financial ",
                    "email" => "admin@admin.com.br",
                    "email_verified_at" => null,
                    "created_at" => "2025-03-20T02:46:48.000000Z",
                    "updated_at" => "2025-03-20T02:46:48.000000Z",
                    "profiles" => [
                        [
                            "id" => 1,
                            "name" => "Financial",
                            "created_at" => "2025-03-20T01:00:23.000000Z",
                            "updated_at" => "2025-03-20T01:00:23.000000Z",
                            "pivot" => [
                                "user_id" => 7,
                                "profile_id" => 1
                            ]
                        ]
                    ]
                ]
            ],
            'to' => 2,
            'total' => 2
        ];
    }
}
