<?php
declare(strict_types=1);

namespace App\Services\Clients;

use App\Models;

readonly class ClientsService
{
    /**
     * @throws \Exception
     */
    public function getOrCreateClient(string $identifier, string $firstName, string $lastName): Models\Client
    {
        $client = Models\Client::where('identifier', $identifier)->first();

        if ($client) {
            if ($client->first_name !== $firstName || $client->last_name !== $lastName) {
                throw new \Exception("Несъответствие в данните на клиента за това ЕГН.");
            }
            return $client;
        }

        return Models\Client::create([
            'identifier' => $identifier,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
    }
}
