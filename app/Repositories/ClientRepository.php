<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\ClientRequest;
use App\Http\Resources\v1\ClientResource;
use App\Interfaces\ClientInterface;
use App\Models\Branch;
use App\Models\Client;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ClientRepository implements ClientInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllClients(ClientRequest $request, Branch $branch)
    {
        return $this->success(ClientResource::collection($branch->clients),
            "List of Countries of Branch " . $branch->display_name,
            Response::HTTP_OK);
    }

    public function getClientById(ClientRequest $request, Branch $branch, Client $client)
    {
        return $this->success(new ClientResource($client));
    }

    public function storeClient(ClientRequest $request, Branch $branch)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            foreach ($data["data"] as $record) {
                $client = $branch->clients()->where('ref', $record['ref'])->first();
                $balances = [];
                if(array_key_exists('balances', $record))
                {
                    $balances = $record['balances'];
                    unset($record['balances']);
                }
                if($client) {
                    //Update Existing Record
                    $client->update($record);
                    //Save Balances
                    $client->balances()->delete();
                    $client->balances()->createMany($balances);
                }
                else {
                    //Save New Record
                    $record['company_id'] = $branch->company_id;
                    $client = $branch->clients()->create($record);
                    $client->balances()->createMany($balances);
                }
            }

            DB::commit();
            return $this->success([], ResponseMessages::CREATED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteClient(ClientRequest $request, Branch $branch, Client $client)
    {
        DB::beginTransaction();
        try {
            $client->balances()->delete();
            $client->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
