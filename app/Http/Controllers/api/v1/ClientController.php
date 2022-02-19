<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ClientRequest;
use App\Interfaces\ClientInterface;
use App\Models\Branch;
use App\Models\Client;

class ClientController extends Controller
{
    protected $clientInterface;

    public function __construct(ClientInterface $clientInterface)
    {
        $this->clientInterface = $clientInterface;
    }

    public function index(ClientRequest $request, Branch $branch)
    {
        return $this->clientInterface->getAllClients($request, $branch);
    }

    public function store(ClientRequest $request, Branch $branch)
    {
        return $this->clientInterface->storeClient($request, $branch);
    }

    public function show(ClientRequest $request, Branch $branch, Client $client)
    {
        return $this->clientInterface->getClientById($request, $branch, $client);
    }

    public function destroy(ClientRequest $request, Branch $branch, Client $client)
    {
        return $this->clientInterface->deleteClient($request, $branch, $client);
    }
}
