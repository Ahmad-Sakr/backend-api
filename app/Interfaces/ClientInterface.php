<?php


namespace App\Interfaces;


use App\Http\Requests\v1\ClientRequest;
use App\Models\Branch;
use App\Models\Client;

interface ClientInterface
{
    public function getAllClients(ClientRequest $request, Branch $branch);

    public function getClientById(ClientRequest $request, Branch $branch, Client $client);

    public function storeClient(ClientRequest $request, Branch $branch);

    public function deleteClient(ClientRequest $request, Branch $branch, Client $client);
}
